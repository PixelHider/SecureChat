<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-6">{{ __("Available Profiles") }}</h3>

                    <!-- Profiles Section -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        <!-- Profile Card -->
                        @foreach ($profiles as $profile)
                        <div class="bg-white dark:bg-gray-700 border border-gray-300 rounded-lg shadow-xl p-6 flex flex-col items-center">
                            <div class="w-24 h-24 rounded-full bg-blue-500 text-white flex items-center justify-center mb-4">
                                @if ($profile->role == 'police')
                                <i class="fas fa-user-shield"></i>
                                @elseif ($profile->role == 'journalist')
                                <i class="fas fa-newspaper"></i>
                                @elseif ($profile->role == 'victim')
                                <i class="fas fa-user-friends"></i>
                                @endif
                            </div>
                            <h4 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Name: {{ $profile->username }}</h4>
                            <p class="mb-2">
                                <span class="text-gray-500 dark:text-gray-300 font-semibold">Role:</span>
                                <span class="text-gray-800 dark:text-gray-100 bg-gray-200 dark:bg-orange-600 rounded-full px-3 py-1 text-sm">
                                    {{ $profile->role }}
                                </span>
                            </p>
                            <!-- <p class="mb-4">Case Solved: 110</p> -->


                            <a href="{{ route('send.message', $profile->username) }}" class="w-full bg-green-600 text-white text-center py-2 px-4 rounded-md hover:bg-green-700 transition duration-300">Send Private Message</a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>