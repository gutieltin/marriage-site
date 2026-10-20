<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    /** GET /api/admin/messages - newest first */
    public function messages(): JsonResponse
    {
        return response()->json(
            Message::orderByDesc('created_at')->get(['id', 'name', 'message', 'created_at'])
        );
    }

    /** DELETE /api/admin/messages/{message} */
    public function destroyMessage(Message $message): JsonResponse
    {
        $message->delete();

        return response()->json(['ok' => true]);
    }

    /** GET /api/admin/messages/export - CSV download */
    public function exportMessages(): StreamedResponse
    {
        // Stop spreadsheet apps from running text that starts with = + - @ as a formula.
        $safe = fn (string $v) => preg_match('/^[=+\-@]/', $v) ? "'" . $v : $v;

        return response()->streamDownload(function () use ($safe) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['name', 'message', 'sent_at']);
            Message::orderBy('created_at')->each(function (Message $m) use ($out, $safe) {
                fputcsv($out, [$safe($m->name), $safe($m->message), $m->created_at]);
            });
            fclose($out);
        }, 'messages.csv', ['Content-Type' => 'text/csv']);
    }
}
