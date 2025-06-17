@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center w-full pl-3 pr-4 py-2 border-l-4 border-indigo-500 dark:border-indigo-500 text-left text-base font-medium text-indigo-700 dark:text-indigo-200 bg-indigo-100 dark:bg-indigo-900/50 focus:outline-none focus:text-indigo-800 dark:focus:text-indigo-100 focus:bg-indigo-200 dark:focus:bg-indigo-800 focus:border-indigo-700 dark:focus:border-indigo-300 transition duration-150 ease-in-out'
            : 'flex items-center w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600 focus:outline-none focus:text-gray-900 dark:focus:text-white focus:bg-gray-100 dark:focus:bg-gray-700 focus:border-gray-300 dark:focus:border-gray-600 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>