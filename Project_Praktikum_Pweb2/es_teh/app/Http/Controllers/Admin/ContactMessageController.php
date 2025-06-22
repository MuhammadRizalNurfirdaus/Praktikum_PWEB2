<?php

namespace App\Http\Controllers\Admin; // Pastikan namespace benar

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $searchQuery = $request->input('search'); // Cari berdasarkan subjek, nama, atau email
        $statusFilter = $request->input('status');
        $itemPerPage = 15;

        $messages = ContactMessage::with('user') // Eager load user jika ada
            ->when($searchQuery, function ($query, $search) {
                return $query->where('subject', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($statusFilter, function ($query, $status) {
                if (!empty($status)) {
                    return $query->where('status', $status);
                }
                return $query;
            })
            ->orderBy('created_at', 'desc')
            ->paginate($itemPerPage);

        $messageStatuses = ['Baru', 'Dibaca', 'Dibalas', 'Ditutup']; // Status yang mungkin

        return view('admin.contacts.index', compact('messages', 'messageStatuses', 'searchQuery', 'statusFilter'));
    }

    public function show(ContactMessage $contactMessage) // Menggunakan route model binding
    {
        // Tandai sebagai 'Dibaca' jika statusnya 'Baru' saat dibuka
        if ($contactMessage->status === 'Baru') {
            $contactMessage->status = 'Dibaca';
            $contactMessage->save();
        }
        $messageStatuses = ['Baru', 'Dibaca', 'Dibalas', 'Ditutup'];
        $contactMessage->load('user'); // Load relasi user jika belum
        return view('admin.contacts.show', compact('contactMessage', 'messageStatuses'));
    }

    public function update(Request $request, ContactMessage $contactMessage)
    {
        $request->validate([
            'status' => ['required', 'string', Rule::in(['Baru', 'Dibaca', 'Dibalas', 'Ditutup'])],
        ]);

        $contactMessage->status = $request->input('status');
        $contactMessage->save();

        return redirect()->route('admin.contacts.show', $contactMessage->id)->with('success', 'Status pesan berhasil diperbarui.');
    }

    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();
        return redirect()->route('admin.contacts.index')->with('success', 'Pesan berhasil dihapus.');
    }
}
