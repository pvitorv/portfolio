<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string|max:5000',
        ], [
            'name.required' => 'Informe seu nome.',
            'email.required' => 'Informe seu e-mail.',
            'email.email' => 'E-mail inválido.',
            'message.required' => 'Escreva sua mensagem.',
        ]);

        // Mensagens chegam no e-mail de serviço (MAIL_FROM_ADDRESS) para você responder pessoalmente
        $to = config('mail.from.address') ?: User::first()?->email;
        if (! $to) {
            return back()->with('error', 'Contato não configurado. Tente mais tarde.');
        }

        try {
            Mail::to($to)->send(new ContactMessageMail(
                $validated['name'],
                $validated['email'],
                $validated['message']
            ));
            return back()->with('success', 'Mensagem enviada. Obrigado!');
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Não foi possível enviar. Tente novamente ou use o e-mail diretamente.');
        }
    }
}
