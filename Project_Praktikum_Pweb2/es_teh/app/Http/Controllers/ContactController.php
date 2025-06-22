<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Mail;
// use App\Mail\NewContactMessageNotification;
use Illuminate\Support\Facades\Log; // Untuk logging error jika perlu

class ContactController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        return view('contact.create', compact('user'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        $contactMessageData = $validatedData; // Ambil data yang sudah divalidasi
        if (Auth::check()) {
            $contactMessageData['user_id'] = Auth::id();
        }
        $contactMessageData['status'] = 'Baru';

        $contactMessage = ContactMessage::create($contactMessageData);

        log_activity(
            "Pesan kontak baru diterima dari {$contactMessage->name} (Subjek: {$contactMessage->subject}).",
            $contactMessage, // Model ContactMessage sebagai subject
            [], // Tidak ada properti tambahan spesifik untuk contoh ini
            'Kontak' // Nama log
        );

        // Opsional: Kirim notifikasi email ke admin
        // try {
        //     $adminEmail = config('mail.admin_address', 'admin@example.com'); // Ambil dari config atau set default
        //     Mail::to($adminEmail)->send(new NewContactMessageNotification($contactMessage));
        // } catch (\Exception $e) {
        //     Log::error('Gagal mengirim email notifikasi kontak: ' . $e->getMessage());
        // }

        return redirect()->route('contact.page')->with('success', 'Pesan Anda telah berhasil terkirim! Kami akan segera merespons.');
    }
}
