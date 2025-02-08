<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Send Private Message') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-6">Send a Private Message</h3>

                    <!-- Message Form -->
                    <form action="{{ route('victim.cases.store') }}" method="POST">
                        @csrf

                        <!-- Case to -->
                        <div class="mb-6">
                            <label for="case_to" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Case to</label>
                            <select name="case_to" id="case_to" class="block mt-1 w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500">
                                <option value="">----------Select----------</option>
                                <option value="police">Police</option>
                                <option value="journalist">Journalist</option>
                            </select>
                            @error('case_to')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Message Subject -->
                        <div class="mb-6">
                            <label for="case_title" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                Case Title
                            </label>
                            <input type="text" id="case_title" name="title" class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-100 dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                            @error('title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Message Body -->
                        <div class="mb-6">
                            <label for="case_description" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                Case Description
                            </label>
                            <textarea id="case_description" name="description" rows="6" class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-100 dark:focus:ring-blue-500 dark:focus:border-blue-500" required></textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Send Message Button -->
                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300 flex items-center">
                                <i class="fas fa-paper-plane mr-2"></i> File a Case
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
