<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Transformers\NotificationTransformer;
use App\Http\Requests\Api\NotificationsRequest;

class NotificationsController extends Controller
{
    public function index()
    {
        $notifications = $this->user->notifications()->paginate(20);

        return $this->response->paginator($notifications, new NotificationTransformer());
    }

    public function stats()
    {
        return $this->response->array([
            'unread_count' => $this->user()->notification_count,
        ]);
    }

    public function read()
    {
        $this->user()->markAsRead();

        return $this->response->noContent();
    }

    public function readOne(Notification $notification)
    {
        if(!$notification->read_at)
        {
            $notification->read_at = date('Y-m-d H:i:s');
            $notification->save();
            $this->user()->notifiCount();
        }

        return $this->response->noContent();
    }
}
