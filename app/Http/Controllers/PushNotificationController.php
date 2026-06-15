<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PushNotificationController extends Controller
{
    /**
     * Simpan subscription browser ke database
     */
    public function subscribe(Request $request)
    {
        $endpoint = $request->endpoint;
        $key      = $request->keys['p256dh'];
        $token    = $request->keys['auth'];

        $request->user()->updatePushSubscription($endpoint, $key, $token);

        return response()->json(['success' => true]);
    }

    /**
     * Cek apakah subscription milik user ini masih ada di DB.
     * Dipanggil dari JS sebelum re-subscribe (Bug 4 fix).
     */
    public function verify(Request $request)
    {
        $endpoint = $request->input('endpoint');

        if (!$endpoint) {
            return response()->json(['valid' => false]);
        }

        $exists = $request->user()
            ->pushSubscriptions()
            ->where('endpoint', $endpoint)
            ->exists();

        return response()->json(['valid' => $exists]);
    }
}