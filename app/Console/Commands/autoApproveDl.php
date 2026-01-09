<?php

namespace App\Console\Commands;

use App\Models\Absensi;
use App\Models\DinasLuar;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        Log::info('Auto Approve DL Command Started');
        try {
            $fcmService = new \App\Services\FcmService();
            $data = DinasLuar::query()
                ->where('status', 'Menunggu')
                ->where('created_at', '<=', now()->subMinutes(5))
                ->with('user:id,fcm_token')
                ->get();


            Log::info('Found ' . $data->count() . ' DL requests to auto-approve');
            foreach ($data as $dl) {
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
                                'keterangan'     => trim($dl->type_dl . ' - ' . ($dl->tujuan ?? 'WFA') . ' - ' . $dl->kegiatan),
                                'dinas_luar_id'  => $dl->id,

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
        } catch (\Throwable $th) {
            Log::error('Auto Approve DL Error: ' . $th->getMessage());
        }
    }
}
