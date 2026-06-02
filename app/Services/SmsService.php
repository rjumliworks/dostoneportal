<?php

namespace App\Services;

class SmsService
{
    public function sendSms($to, $message)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.dost9.ph/sms/messages');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $post = array(
            'recipient' => $to,
            'message' => $message,
            'title' => 'DOST-IX OneApp',
            'via' => 'DOST-IX Notification'
        );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        dd($result);
        curl_close($ch);
    }
}
