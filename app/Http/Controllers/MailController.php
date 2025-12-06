<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class MailController extends Controller
{
    public function index()
    {
        // Traemos solo usuarios habilitados, excluyendo id=1
        $users = User::whereNull('deleted_at')
                     ->where('id', '<>', 1)
                     ->get();

        return view('emails.index', compact('users'));
    }

   public function send(Request $request)
{
    $request->validate([
        'users' => 'required|array',
        'subject' => 'required|string',
        'message' => 'required|string',
        'attachments.*' => 'file|mimes:pdf,jpg,jpeg,png,gif,doc,docx|max:10240', // 10MB max
    ]);

    // Filtramos usuarios seleccionados, excluyendo id=1
    $users = User::whereIn('id', $request->users)
                 ->where('id', '<>', 1)
                 ->whereNull('deleted_at')
                 ->get();

    foreach ($users as $user) {
        Mail::send([], [], function ($mail) use ($user, $request) {
            $mail->to($user->email, $user->nombre . ' ' . $user->apellido)
                 ->subject($request->subject)
                 ->html($request->message); // ✅ cambia setBody() por html()

            // Adjuntar archivos si existen
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $mail->attach($file->getRealPath(), [
                        'as' => $file->getClientOriginalName(),
                        'mime' => $file->getClientMimeType(),
                    ]);
                }
            }
        });
    }

    return redirect()->back()->with('success', 'Correos enviados correctamente.');
}

}
