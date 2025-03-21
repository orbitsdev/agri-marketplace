<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\MessageCreated;

class NotificationController extends Controller
{

    public static function sendMessageNotification($type, $title,$message,$senderName,$receiverName,$senderId,$receiver,$route,
    ){

        $receiver->notify(new MessageCreated($type, $title, $message,$senderName,$receiverName,$senderId,$receiver,$route,));
    }
}
