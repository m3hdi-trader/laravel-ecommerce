<?php

namespace App\Channels;

use Ghasedak\GhasedakApi;
use Illuminate\Notifications\Notification;

class SmsChannel
{
    public function send($notifiable, Notification $notification)
    {
        return 'done!';
        dd($notifiable, $notification->code);
        $api = new GhasedakApi("env('GHASEDAKAPI_KEY')");

        $receptor = $notifiable->cellphone;
        $type = 1;
        $tempale = "Ghasedak";
        $param1 = $notification->code;
        try {
            $api->Verify($recepto, $type, $tempale, $param1);
        } catch (\Throwable $th) {
            // dd($th);
        }
    }
}
