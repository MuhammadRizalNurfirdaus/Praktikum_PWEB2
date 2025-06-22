<?php // PASTIKAN DIMULAI DENGAN INI

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Tambahkan ini jika belum

if (!function_exists('log_activity')) {
    /**
     * Helper untuk mencatat aktivitas.
     *
     * @param string $description Deskripsi aktivitas.
     * @param \Illuminate\Database\Eloquent\Model|null $subject Model yang terkait.
     * @param array $properties Properti tambahan.
     * @param string $logName Nama log.
     * @return \App\Models\ActivityLog|null
     */
    function log_activity(string $description, $subject = null, array $properties = [], string $logName = 'default')
    {
        try {
            $activity = new ActivityLog();
            $activity->log_name = $logName;
            $activity->description = $description;

            if ($subject) {
                // Pastikan $subject adalah instance dari Model dan memiliki getKey()
                if ($subject instanceof \Illuminate\Database\Eloquent\Model) {
                    $activity->subject_id = $subject->getKey();
                    $activity->subject_type = get_class($subject);
                }
            }

            if (Auth::check()) {
                // Pastikan Auth::user() adalah instance dari Model dan memiliki getKey()
                $causer = Auth::user();
                if ($causer instanceof \Illuminate\Database\Eloquent\Model) {
                    $activity->causer_id = $causer->getKey();
                    $activity->causer_type = get_class($causer);
                }
            }

            $activity->properties = $properties; // Properties harus berupa array
            $activity->save();

            return $activity;
        } catch (\Exception $e) {
            Log::error("Gagal mencatat aktivitas: " . $e->getMessage() . " - Data: " . json_encode(compact('description', 'subject', 'properties', 'logName')));
            return null;
        }
    }
}
