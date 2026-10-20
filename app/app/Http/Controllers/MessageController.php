<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /** POST /api/messages - a guest leaves a message for the couple. */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'message' => ['required', 'string', 'max:1000'],
            'website' => ['nullable', 'string'], // hidden field: only bots fill it in
        ]);

        // Honeypot: pretend it worked, but save nothing.
        if (! empty($data['website'])) {
            return response()->json(['ok' => true]);
        }

        Message::create([
            'name' => trim($data['name']),
            'message' => trim($data['message']),
        ]);

        return response()->json(['ok' => true], 201);
    }
}
