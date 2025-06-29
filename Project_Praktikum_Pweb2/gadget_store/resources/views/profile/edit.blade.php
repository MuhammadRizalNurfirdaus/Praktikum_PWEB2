{{-- AWAL DARI FILE resources/views/profile/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Panel untuk Update Profile Information --}}
            <div
                class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Panel untuk Update Password --}}
            <div
                class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Panel untuk Delete Account --}}
            <div
                class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
{{-- AKHIR DARI FILE resources/views/profile/edit.blade.php --}}
