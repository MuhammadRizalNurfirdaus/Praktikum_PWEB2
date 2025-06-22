{{-- resources/views/user/partials/sidebar.blade.php --}}
<div class="list-group shadow-sm mb-4">
    <a href="{{ route('user.dashboard') }}"
        class="list-group-item list-group-item-action {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
        <i class="bi bi-house-door-fill me-2"></i> Dashboard Saya
    </a>
    <a href="{{ route('profile.edit') }}"
        class="list-group-item list-group-item-action {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
        <i class="bi bi-person-badge-fill me-2"></i> Profil Saya
    </a>
    <a href="{{ route('user.orders.index') }}"
        class="list-group-item list-group-item-action {{ request()->routeIs('user.orders.index') || request()->routeIs('user.orders.show') ? 'active' : '' }}">
        <i class="bi bi-receipt-cutoff me-2"></i> Riwayat Pesanan
        @php
            $user = Auth::user();
            // Pengecekan $user untuk menghindari error jika diakses oleh tamu (meskipun route dilindungi)
            $pendingOrdersCount = $user
                ? $user
                    ->orders()
                    ->whereNotIn('status', ['delivered', 'cancelled', 'failed'])
                    ->count()
                : 0;
        @endphp
        @if ($pendingOrdersCount > 0)
            <span class="badge bg-warning text-dark float-end">{{ $pendingOrdersCount }}</span>
        @endif
    </a>
    {{-- Logout bisa diletakkan di navbar utama saja, atau tambahkan di sini jika perlu --}}
    {{-- <a class="list-group-item list-group-item-action text-danger" href="{{ route('logout') }}"
       onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
        <i class="bi bi-box-arrow-right me-2"></i> Log Out
    </a>
    <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form> --}}
</div>
