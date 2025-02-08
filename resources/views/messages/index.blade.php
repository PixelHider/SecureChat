<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Messages') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Search Bar and Sort By Status Inline -->
                    <div class="flex items-center justify-between mb-6">
                        <!-- Search Bar -->
                        <input 
                            type="text" 
                            id="searchBar" 
                            placeholder="Search messages..." 
                            class="px-4 py-2 w-full sm:w-2/3 border rounded-md bg-gray-100 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-600 dark:focus:ring-blue-300"
                        />

                        <!-- Sort by Read/Unread -->
                        <select id="sortByRead" class="ml-4 px-4 py-2 w-full sm:w-1/3 border rounded-md bg-gray-100 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-600 dark:focus:ring-blue-300">
                            <option value="all">Sort by Status</option>
                            <option value="read">Read</option>
                            <option value="unread">Unread</option>
                        </select>
                    </div>

                    <!-- No Results Message -->
                    <div id="noResultsMessage" class="hidden text-center text-gray-500 dark:text-gray-400 mb-4">
                        <p>No results found.</p>
                    </div>

                    <!-- Messages Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="messagesContainer">
                        @foreach ($messages as $message)
                            <div class="messageCard bg-white dark:bg-gray-700 rounded-lg shadow-lg overflow-hidden transform transition-transform duration-300 hover:scale-105 hover:shadow-xl" data-subject="{{ $message->subject }}" data-sender="{{ $message->sender->username }}" data-recipient="{{ $message->receiver->username }}" data-read="{{ $message->is_read }}">
                                <div class="p-6">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-2">{{ $message->subject }}</h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Sender: <span class="font-semibold">{{ $message->sender->username }}</span></p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Receiver: <span class="font-semibold">{{ $message->receiver->username }}</span></p>
                                        </div>
                                    </div>

                                    <!-- Date and Status Section -->
                                    <div class="flex justify-between items-center mt-4">
                                        <!-- Date -->
                                        <span class="text-xs text-gray-500 dark:text-gray-300">
                                            {{ $message->created_at->format('d M, Y') }}
                                        </span>

                                        <!-- Status -->
                                        <span class="text-xs font-semibold 
                                            {{ $message->is_read ? 'text-green-600 bg-green-100' : 'text-red-600 bg-red-100' }} 
                                            rounded-full px-3 py-1">
                                            {{ $message->is_read ? 'Read' : 'Unread' }}
                                        </span>
                                    </div>

                                    <!-- "View Message" Button -->
                                    <div class="mt-4 text-right">
                                        @if (Auth::user()->role === 'police')
                                        <a href="{{ route('police.messages.show', $message->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300 inline-flex items-center">
                                            <i class="fas fa-eye mr-2"></i> <span>View Message</span>
                                        </a>
                                        @endif
                                        @if (Auth::user()->role === 'journalist')
                                        <a href="{{ route('journalist.messages.show', $message->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300 inline-flex items-center">
                                            <i class="fas fa-eye mr-2"></i> <span>View Message</span>
                                        </a>
                                        @endif
                                        @if (Auth::user()->role === 'victim')
                                        <a href="{{ route('victim.messages.show', $message->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300 inline-flex items-center">
                                            <i class="fas fa-eye mr-2"></i> <span>View Message</span>
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.15/dist/sweetalert2.min.js"></script>

    <!-- Search Bar Script -->
    <script>
        document.getElementById("searchBar").addEventListener("input", function() {
            let searchQuery = this.value.toLowerCase();
            let messageCards = document.querySelectorAll(".messageCard");
            let noResultsMessage = document.getElementById("noResultsMessage");
            let visibleCards = 0;

            messageCards.forEach(function(card) {
                let subject = card.getAttribute("data-subject").toLowerCase();
                let sender = card.getAttribute("data-sender").toLowerCase();
                let recipient = card.getAttribute("data-recipient").toLowerCase();

                if (subject.includes(searchQuery) || sender.includes(searchQuery) || recipient.includes(searchQuery)) {
                    card.style.display = "block"; // Show card
                    visibleCards++;
                } else {
                    card.style.display = "none"; // Hide card
                }
            });

            // Show or hide the "No results found" message
            if (visibleCards === 0 && searchQuery !== "") {
                noResultsMessage.classList.remove("hidden");
            } else {
                noResultsMessage.classList.add("hidden");
            }
        });

        // Sort by Read/Unread
        document.getElementById("sortByRead").addEventListener("change", function() {
            let filterValue = this.value;
            let messageCards = document.querySelectorAll(".messageCard");
            let visibleCards = 0;

            messageCards.forEach(function(card) {
                let isRead = card.getAttribute("data-read");

                if (filterValue === "all") {
                    card.style.display = "block";
                    visibleCards++;
                } else if (filterValue === "read" && isRead === "1") {
                    card.style.display = "block";
                    visibleCards++;
                } else if (filterValue === "unread" && isRead === "0") {
                    card.style.display = "block";
                    visibleCards++;
                } else {
                    card.style.display = "none";
                }
            });

            // Show or hide the "No results found" message
            let noResultsMessage = document.getElementById("noResultsMessage");
            if (visibleCards === 0) {
                noResultsMessage.classList.remove("hidden");
            } else {
                noResultsMessage.classList.add("hidden");
            }
        });
    </script>
</x-app-layout>
