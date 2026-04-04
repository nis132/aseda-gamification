<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PushNotificationController extends Controller
{
    /**
     * Menyimpan data subscription browser ke database
     */
public function subscribe(Request $request)
{
    // Cek apakah data sampai di sini
    // \Log::info($request->all()); 

    $endpoint = $request->endpoint;
    $key = $request->keys['p256dh'];
    $token = $request->keys['auth'];

    // Ini fungsi bawaan dari Trait HasPushSubscriptions
    // Dia akan otomatis menyimpan ke tabel 'push_subscriptions'
    $request->user()->updatePushSubscription($endpoint, $key, $token);

    return response()->json(['success' => true]);
}
}