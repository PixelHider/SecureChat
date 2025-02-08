<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-6">{{ __("Welcome to the Admin Dashboard") }}</h3>

                    <!-- Statistics Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div class="bg-blue-500 text-white rounded-lg p-6 shadow-md">
                            <h4 class="text-lg font-semibold">Total Users</h4>
                            <p class="text-2xl mt-2">{{ $totalUsers }}</p>
                        </div>
                        <div class="bg-green-500 text-white rzounded-lg p-6 shadow-md">
                            <h4 class="text-lg font-semibold">Total Cases</h4>
                            <p class="text-2xl mt-2">{{ $totalCases }}</p>
                        </div>
                        <div class="bg-red-500 text-white rounded-lg p-6 shadow-md">
                            <h4 class="text-lg font-semibold">Total Messages</h4>
                            <p class="text-2xl mt-2">{{ $totalMessages }}</p>
                        </div>
                    </div>

                    <!-- Tabbed Navigation -->
                    <div class="flex space-x-4 mb-6">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded-md focus:outline-none hover:bg-blue-700 transition duration-200" id="usersTab" onclick="showTab('users')">
                            Users
                        </button>
                        <button class="px-4 py-2 bg-green-600 text-white rounded-md focus:outline-none hover:bg-green-700 transition duration-200" id="casesTab" onclick="showTab('cases')">
                            Cases
                        </button>
                        <button class="px-4 py-2 bg-red-600 text-white rounded-md focus:outline-none hover:bg-red-700 transition duration-200" id="messagesTab" onclick="showTab('messages')">
                            Messages
                        </button>
                        <!-- Pending users -->
                        <button class="px-4 py-2 bg-yellow-600 text-white rounded-md focus:outline-none hover:bg-yellow-700 transition duration-200" id="pendingTab" onclick="showTab('pending')">
                            Pending Users
                        </button>

                    </div>

                    <!-- Tab Content -->
                    <div id="tabContent">
                        <!-- Users Tab Content -->
                        <div id="users" class="hidden">
                            <h5 class="font-semibold text-lg mb-4">All Users</h5>
                            <table id="usersTable" class="w-full text-sm">
                                <thead>
                                    <tr class="text-left bg-gray-200 dark:bg-gray-600">
                                        <th class="py-2 px-4">User ID</th>
                                        <th class="py-2 px-4">Username</th>
                                        <th class="py-2 px-4">Email</th>
                                        <th class="py-2 px-4">Role</th>
                                        <th class="py-2 px-4">Status</th>
                                        <th class="py-2 px-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                    <tr class="border-b dark:border-gray-500">
                                        <td class="py-2 px-4">{{ $user->id }}</td>
                                        <td class="py-2 px-4">{{ $user->username }}</td>
                                        <td class="py-2 px-4">{{ $user->email }}</td>
                                        <td class="py-2 px-4">{{ $user->role }}</td>
                                        <td class="py-2 px-4">
                                            @if ($user->status == 'active')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="py-2 px-4">
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition duration-300"
                                                    onclick="confirmDelete(this.form);">
                                                    <i class="fas fa-trash mr-2"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Cases Tab Content -->
                        <div id="cases" class="hidden">
                            <h5 class="font-semibold text-lg mb-4">All Cases</h5>
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
                                    @foreach ($cases as $case)
                                    <tr class="border-b dark:border-gray-500">
                                        <td class="py-2 px-4">{{ $case->id }}</td>
                                        <td class="py-2 px-4">{{ $case->title }}</td>
                                        <td class="py-2 px-4">{{ $case->status }}</td>
                                        <td class="py-2 px-4">
                                            <form action="{{ route('admin.cases.destroy', $case->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition duration-300"
                                                    onclick="confirmDelete(this.form);">
                                                    <i class="fas fa-trash mr-2"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Messages Tab Content -->
                        <div id="messages" class="hidden">
                            <h5 class="font-semibold text-lg mb-4">All Messages</h5>
                            <table id="messagesTable" class="w-full text-sm">
                                <thead>
                                    <tr class="text-left bg-gray-200 dark:bg-gray-600">
                                        <th class="py-2 px-4">Message ID</th>
                                        <th class="py-2 px-4">Sender</th>
                                        <th class="py-2 px-4">Subject</th>
                                        <th class="py-2 px-4">Date</th>
                                        <th class="py-2 px-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($messages as $message)
                                    <tr class="border-b dark:border-gray-500">
                                        <td class="py-2 px-4">{{ $message->id }}</td>
                                        <td class="py-2 px-4">{{ $message->sender->username }}</td>
                                        <td class="py-2 px-4">{{ $message->subject }}</td>
                                        <td class="py-2 px-4">{{ $message->created_at }}</td>
                                        <td class="py-2 px-4">
                                            <form action="{{ route('admin.messages.destroy', $message->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition duration-300"
                                                    onclick="confirmDelete(this.form);">
                                                    <i class="fas fa-trash mr-2"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Pending users tab content  -->
                        <div id="pending" class="hidden">
                            <h5 class="font-semibold text-lg mb-4">Pending Users</h5>
                            <table id="pendingTable" class="w-full text-sm">
                                <thead>
                                    <tr class="text-left bg-gray-200 dark:bg-gray-600">
                                        <th class="py-2 px-4">User ID</th>
                                        <th class="py-2 px-4">Username</th>
                                        <th class="py-2 px-4">Email</th>
                                        <th class="py-2 px-4">Role</th>
                                        <th class="py-2 px-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pendingUsers as $user)
                                    <tr class="border-b dark:border-gray-500">
                                        <td class="py-2 px-4">{{ $user->id }}</td>
                                        <td class="py-2 px-4">{{ $user->username }}</td>
                                        <td class="py-2 px-4">{{ $user->email }}</td>
                                        <td class="py-2 px-4">{{ $user->role }}</td>
                                        <td class="py-2 px-4">
                                            <a href="{{ route('admin.users.approve', $user->id) }}"
                                               class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition duration-300">
                                                <i class="fas fa-eye mr-2"></i> Approve
                                            </a>
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition duration-300"
                                                    onclick="confirmDelete(this.form);">
                                                    <i class="fas fa-trash mr-2"></i> Delete
                                                </button>
                                            </form>
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

    <!-- JavaScript to toggle between tabs -->
    <script>
        function showTab(tabName) {
            const usersTab = document.getElementById('users');
            const casesTab = document.getElementById('cases');
            const messagesTab = document.getElementById('messages');
            const pendingTab = document.getElementById('pending');

            usersTab.classList.add('hidden');
            casesTab.classList.add('hidden');
            messagesTab.classList.add('hidden');
            pendingTab.classList.add('hidden');

            if (tabName === 'users') {
                usersTab.classList.remove('hidden');
            } else if (tabName === 'cases') {
                casesTab.classList.remove('hidden');
            } else if (tabName === 'messages') {
                messagesTab.classList.remove('hidden');
            }else if (tabName === 'pending') {
                pendingTab.classList.remove('hidden');
            }
        }

        // Initialize DataTables
        $(document).ready(function() {
            $('#usersTable').DataTable({
                paging: true,
                searching: true
            });
            $('#casesTable').DataTable({
                paging: true,
                searching: true
            });
            $('#messagesTable').DataTable({
                paging: true,
                searching: true
            });
            $('#pendingTable').DataTable({
                paging: true,
                searching: true
            });
        });

        // confirmDelete function using sweetalert2
        function confirmDelete(form) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
</x-app-layout>
