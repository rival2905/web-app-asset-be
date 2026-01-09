<?php

namespace App\Console\Commands;

use App\Models\Absensi;
use App\Models\DinasLuar;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class autoApproveDl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-approve-dl';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $data = DinasLuar::query()
            ->where('status', 'Menunggu')
            ->with('user:id,fcm_token')
            ->get();

        $fcmService = new \App\Services\FcmService();

        foreach ($data as $dl) {
            // Lebih aman: jangan mutate created_at
            if (!$dl->created_at->copy()->addMinutes(5)->lte(now())) {
                continue;
            }

            DB::transaction(function () use ($dl, $fcmService) {
                $dl->refresh();
                if ($dl->status !== 'Menunggu') {
                    return;
                }

                $dl->update(['status' => 'Disetujui']);

                $start = Carbon::parse($dl->tanggal_mulai)->startOfDay();
                $end   = Carbon::parse($dl->tanggal_selesai)->startOfDay();


                if ($end->lt($start)) {
                    return;
                }

                $days = $start->diffInDays($end);

                for ($i = 0; $i <= $days; $i++) {
                    $tgl = $start->copy()->addDays($i)->toDateString();

                    Absensi::updateOrCreate(
                        [
                            'user_id'  => $dl->user_id,
                            'tanggal'  => $tgl,
                        ],
                        [
                            'keterangan'     => trim($dl->type_dl . ' - ' . $dl->tujuan . ' - ' . $dl->kegiatan),
                            'dinas_luar_id'  => $dl->id,
                            // optional: kalau ada field jenis/status absensi
                            // 'status' => 'DL',
                        ]
                    );
                }

                $fcm_token = optional($dl->user)->fcm_token;

                if ($fcm_token) {
                    $title = 'Selamat Bertugas !';
                    $message = "{$dl->type_dl} Anda pada tanggal {$dl->tanggal_mulai} sampai {$dl->tanggal_selesai} telah disetujui";
                    $fcmService->sendNotification($fcm_token, $title, $message);
                }
            });
        }
    }
}
