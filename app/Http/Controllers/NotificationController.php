<?php

namespace App\Http\Controllers;

use App\Models\DeviceToken;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;

class NotificationController extends Controller
{
    public function index(Notification $notifications)
    {
        return view('notifications.notifications-index', ['notifications' => $notifications->get()]);
    }

    public function notificationedit(Request $request, $id)
    {
        $notifications = Notification::findOrFail($id);
        return view('notifications.notification-edit')->with('notifications', $notifications);
    }

    public function notificationupdate(Request $request, $id)
    {
        $notification = Notification::find($id);
        $notification->title = $request->input('title');
        $notification->message = $request->input('message');

        if ($request->hasFile('image')) {
            if ($notification->image != null) {
                $oldImagePath = public_path("/uploads/notifications/$notification->image");
                if (File::exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $image =  $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $image->move('uploads/notifications/', $filename);
            $notification->image = $filename;
        }

        $notification->save();

        return redirect('/notifications')->with('status', 'Notification updated successfully');
    }

    public function notificationdelete($id)
    {
        $notification = Notification::findOrFail($id);
        if ($notification->image !== null) {
            $oldImagePath = public_path("/uploads/notifications/$notification->image");
            if (File::exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }
        $notification->delete();

        return redirect('/notifications')->with('status', 'Notification deleted successfully');
    }

    public function notificationadd(Request $request)
    {
        $notification = new Notification;

        $notification->title = $request->input('title');
        $notification->message = $request->input('message');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $image->move('uploads/notifications/', $filename);
            $notification->image = $filename;
        } else {
            return $request;
            $notification->image = '';
        }

        $notification->save();


        return redirect('/notifications')->with('status', 'Notification added successfully');
    }

    public function bulksend(Request $request, $id)
    {
        $info = Notification::find($id);
        $fcmKey = DB::table('settings')->find(1)->fcm_key;
        $firebaseToken = DeviceToken::whereNotNull('device_token')->pluck('device_token')->all();

        $SERVER_API_KEY = $fcmKey;

        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $info->title,
                "body" => $info->message,
                "image" => URL::to('/') . '/uploads/notifications/' . $info->image
            ]
        ];
        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);
        $failure = json_decode($response, true)['failure'];

        if ($failure == 0)
            return redirect('/notifications')->with('status', 'Notification sent successfully');
        else
            return redirect('/notifications')->with('status', 'An error occured when sending notification');
    }
}
