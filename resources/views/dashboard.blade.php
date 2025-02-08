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
                    <h3 class="text-lg font-semibold mb-6">{{ __("Welcome to Your Dashboard") }}</h3>

                    <!-- Dashboard Stats (Cases & Messages) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">
                        <!-- Active Cases Card -->
                        <div class="bg-white dark:bg-gray-700 border border-gray-300 rounded-lg shadow-xl p-6 flex flex-col items-center">
                            <div class="w-16 h-16 rounded-full bg-blue-500 text-white flex items-center justify-center mb-4">
                                <i class="fas fa-gavel text-3xl"></i>
                            </div>
                            <h4 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Active Cases</h4>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">Manage ongoing investigations</p>
                            @if(Auth::user()->role === 'police')
                            <a href="{{ route('cases.for.police') }}" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-300 text-center">
                                View Cases
                            </a>
                            @endif
                            @if(Auth::user()->role === 'journalist')
                            <a href="{{ route('cases.for.journalist') }}" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-300 text-center">
                                View Cases
                            </a>
                            @endif

                        </div>

                        <!-- New Messages Card -->
                        <div class="bg-white dark:bg-gray-700 border border-gray-300 rounded-lg shadow-xl p-6 flex flex-col items-center">
                            <div class="w-16 h-16 rounded-full bg-green-500 text-white flex items-center justify-center mb-4">
                                <i class="fas fa-comment-dots text-3xl"></i>
                            </div>
                            <h4 class="text-xl font-semibold text-gray-800 dark:text-gray-100">New Messages</h4>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">Check for private messages</p>
                            @if(Auth::user()->role === 'police')
                            <a href="police/messages" class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition duration-300 text-center">
                                View Messages
                            </a>
                            @endif
                            @if(Auth::user()->role === 'journalist')
                            <a href="journalist/messages" class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition duration-300 text-center">
                                View Messages
                            </a>
                            @endif
                        </div>

                        <!-- Closed Cases Card -->
                        <div class="bg-white dark:bg-gray-700 border border-gray-300 rounded-lg shadow-xl p-6 flex flex-col items-center">
                            <div class="w-16 h-16 rounded-full bg-gray-500 text-white flex items-center justify-center mb-4">
                                <i class="fas fa-check-circle text-3xl"></i>
                            </div>
                            <h4 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Closed Cases</h4>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">View closed investigations</p>
                            <a href='{{ route("$role.cases.closed") }}' class="w-full bg-gray-600 text-white py-2 px-4 rounded-md hover:bg-gray-700 transition duration-300 text-center">
                                View Closed Cases
                            </a>
                        </div>
                    </div>

                    <!-- Recent Cases & Messages Table -->
                    <div class="bg-white dark:bg-gray-700 rounded-lg shadow-xl p-6">
                        <h4 class="text-xl font-semibold mb-6">Recent Cases and Messages</h4>

                        <!-- Tabbed Navigation -->
                        <div class="flex space-x-4 mb-6">
                            <button class="px-4 py-2 bg-blue-600 text-white rounded-md focus:outline-none hover:bg-blue-700 transition duration-200" id="casesTab" onclick="showTab('cases')">
                                Cases
                            </button>
                            <button class="px-4 py-2 bg-green-600 text-white rounded-md focus:outline-none hover:bg-green-700 transition duration-200" id="messagesTab" onclick="showTab('messages')">
                                Messages
                            </button>
                        </div>

                        <!-- Tab Content -->
                        <div id="tabContent">
                            <!-- Cases Tab Content -->
                            <div id="cases" class="hidden">
                                <h5 class="font-semibold text-lg mb-4">Active Cases</h5>
                                <table id="casesTable" class="w-full text-sm">
                                    <thead>
                                        <tr class="text-left bg-gray-200 dark:bg-gray-600">
                                            <th class="py-2 px-4">Case ID</th>
                                            <th class="py-2 px-4">Title</th>
                                            <th class="py-2 px-4">Status</th>
                                            <th class="py-2 px-4">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recent_cases as $case)
                                        <!-- Example Row -->
                                        <tr class="border-b dark:border-gray-500">
                                            <td class="py-2 px-4">{{ $case->case_number}}</td>
                                            <td class="py-2 px-4">{{ $case->title }}</td>
                                            <td class="py-2 px-4">
                                                @if($case->status === 'open')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ $case->status }}</span>
                                                @elseif($case->status === 'In Progress')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">{{ $case->status }}</span>
                                                @elseif($case->status === 'closed')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">{{ $case->status }}</span>
                                                @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ $case->status }}</span>
                                                @endif
                                            </td>

                                            <td class="py-2 px-4">
                                                <a href='{{ route("$role.cases.show", $case->id) }}'
                                                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition duration-300">
                                                    <i class="fas fa-eye mr-2"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                        <!-- More Rows can be added dynamically -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- Messages Tab Content -->
                            <div id="messages" class="hidden">
                                <h5 class="font-semibold text-lg mb-4">New Messages</h5>
                                <table id="messagesTable" class="w-full text-sm">
                                    <thead>
                                        <tr class="text-left bg-gray-200 dark:bg-gray-600">
                                            <th class="py-2 px-4">Sender</th>
                                            <th class="py-2 px-4">Subject</th>
                                            <th class="py-2 px-4">Date</th>
                                            <th class="py-2 px-4">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recent_messages as $recent_message)
                                        <!-- Example Row -->
                                        <tr class="border-b dark:border-gray-500">
                                            <td class="py-2 px-4">{{ $recent_message->sender->username }}</td>
                                            <td class="py-2 px-4">{{ $recent_message->subject }}</td>
                                            <td class="py-2 px-4">{{ $recent_message->created_at }}</td>
                                            <td class="py-2 px-4">
                                                <a href='{{ route("$role.messages.show", $recent_message->id) }}'
                                                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition duration-300">
                                                    <i class="fas fa-eye mr-2"></i> View
                                                </a>
                                            </td>

                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript to toggle between cases and messages -->
    <script>
        function showTab(tabName) {
            const casesTab = document.getElementById('cases');
            const messagesTab = document.getElementById('messages');

            if (tabName === 'cases') {
                casesTab.classList.remove('hidden');
                messagesTab.classList.add('hidden');
            } else {
                messagesTab.classList.remove('hidden');
                casesTab.classList.add('hidden');
            }
        }

        // Initialize DataTables
        $(document).ready(function() {
            $('#casesTable').DataTable({
                paging: true,
                searching: true,
                order: [
                    [0, 'desc']
                ], // Default sorting by Case ID (desc)
                lengthChange: false
            });

            $('#messagesTable').DataTable({
                paging: true,
                searching: true,
                order: [
                    [2, 'desc']
                ], // Default sorting by Date (desc)
                lengthChange: false
            });
        });
    </script>
</x-app-layout>