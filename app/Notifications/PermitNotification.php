<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class PermitNotification extends Notification
{
    use Queueable;

    private $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(String $message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return [FcmChannel::class];
    }

    public function toFcm($notifiable)
    {
        return FcmMessage::create()
            // ->setData(['data1' => 'value', 'data2' => 'value2'])
            ->setNotification(\NotificationChannels\Fcm\Resources\Notification::create()
                ->setTitle('Permintaan Izin Keluar')
                ->setBody($this->message . ' mengirimkan permintaan izin untuk keluar.'));
        // return FcmMessage::create()
        //     // ->setData(['data1' => 'value', 'data2' => 'value2'])
        //     ->setNotification(\NotificationChannels\Fcm\Resources\Notification::create()
        //         ->setTitle('Permintaan Izin Keluar')
        //         ->setBody('Siswa anda telah mengirimkan permintaan izin untuk keluar.'));
        // return (new FcmMessage(notification: new FcmNotification(
        //     title: 'Account Activated',
        //     body: 'Your account has been activated.',
        // )));
        // ->data(['data1' => 'value', 'data2' => 'value2'])
        // ->custom([
        //     'android' => [
        //         'notification' => [
        //             'color' => '#0A0A0A',
        //         ],
        //         'fcm_options' => [
        //             'analytics_label' => 'analytics',
        //         ],
        //     ],
        //     'apns' => [
        //         'fcm_options' => [
        //             'analytics_label' => 'analytics',
        //         ],
        //     ],
        // ]);
    }
}
