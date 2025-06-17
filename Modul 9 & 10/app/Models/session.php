// Memulai Session
session()->set([
    'username' => $user['username'],
    'role' => $user['role'],
    'is_logged_in' => true
]);

// Autentikasi Pengguna
if (!session()->get('is_logged_in')) {
    return redirect()->to('/login');
}

// Mengambil Data Pengguna
$user = [
    'username' => 'admin',
    'role' => 'administrator'
];

// Mengakhiri Session
session()->destroy();



