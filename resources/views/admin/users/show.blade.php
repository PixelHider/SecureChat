<!-- view user details  -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <p class="text-gray-600 dark:text-gray-400"><strong>Name:</strong> {{ $user->username }}</p>
                    <p class="text-gray-600 dark:text-gray-400"><strong>Email:</strong> {{ $user->email }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>