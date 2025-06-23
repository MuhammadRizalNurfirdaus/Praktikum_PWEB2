<?php

namespace App\Http\Controllers\Admin; // Pastikan namespace ini benar

use App\Http\Controllers\Controller; // Controller dasar Laravel
use App\Models\ActivityLog;         // Import model ActivityLog Anda
use Illuminate\Http\Request;         // Untuk mengambil input request (misalnya filter di masa depan)

class ActivityLogController extends Controller
{
    /**
     * Display a listing of all activity logs with pagination.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): \Illuminate\View\View
    {
        $itemPerPage = 20; // Tentukan jumlah aktivitas per halaman

        // Anda bisa menambahkan filter di sini jika diperlukan di masa depan
        // Misalnya, filter berdasarkan log_name, causer, atau rentang tanggal

        $activities = ActivityLog::with(['subject', 'causer']) // Eager load relasi untuk efisiensi
            ->orderBy('created_at', 'desc') // Tampilkan yang terbaru dulu
            ->paginate($itemPerPage);

        return view('admin.activities.index', compact('activities'));
        // Kita akan membuat view 'admin.activities.index' di langkah berikutnya
    }

    // Anda bisa menambahkan method lain di sini jika diperlukan, misalnya:
    // public function show(ActivityLog $activityLog) { /* ... */ }
    // public function destroy(ActivityLog $activityLog) { /* ... */ }
}
