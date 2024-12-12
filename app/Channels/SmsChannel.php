<?php

namespace App\Channels ;

use App\Class\Ghasedak;
use Illuminate\Notifications\Notification;

class SmsChannel
{

    public function send($notifiable , Notification $notification)
    {
        $ghasedakSms = new Ghasedak;

        $mobile = $notifiable->cellphone;

        $code = $notification->code ;

        return ;
        $ghasedakSms->sendOTPSms($code , $mobile , 'Ghasedak');
    }


}

?>
