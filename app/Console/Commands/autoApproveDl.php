<?php

namespace App\Console\Commands;

use App\Models\DinasLuar;
use Illuminate\Console\Command;

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
        $data =  DinasLuar::where('status', 'Menunggu')->with('user')->get();
        $fcmService = new \App\Services\FcmService();
        foreach ($data as $dl) {
            if ($dl->created_at->addMinutes(5)->lessThanOrEqualTo(now())) {
                $dl->update([
                    'status' => 'Disetujui'
                ]);
                $fcm_token = $dl->user->fcm_token ?? null;
                $title = 'Selamat Bertugas !';
                $message = $dl->type_dl . ' Anda pada tanggal ' . $dl->tanggal_mulai . ' sampai ' . $dl->tanggal_selesai . ' telah disetujui';
                if ($fcm_token != null) {
                    $fcmService->sendNotification($fcm_token, $title, $message);
                }
            }
        }
    }
}
