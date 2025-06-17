@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-800 dark:text-gray-200']) }}> {{-- Warna teks lebih terang di dark mode --}}
    {{ $value ?? $slot }}
</label>