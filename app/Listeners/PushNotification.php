<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PushNotification
{
    protected $client;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = $client;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(DatabaseNotification $notification)
    {
        // 没有 registration_id 的不推送
        if (!$user->registration_id) {
            return;
        }
        // 推送消息
        $this->client->push()
            ->setPlatform('all')
            ->addRegistrationId($user->registration_id)
            ->setNotificationAlert(strip_tags($notification->data['reply_content']))
            ->send();

    }
}
