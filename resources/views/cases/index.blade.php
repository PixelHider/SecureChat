<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ $header_title ?? 'Cases' }}

        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (Auth::user()->role === 'victim')
                    <!-- Create Case Button  -->
                    <div class="mb-6">
                        <a href="{{ route('victim.cases.create') }}" class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition duration-300 text-center">
                            <i class="fas fa-plus mr-2"></i>Create New Case
                        </a>
                    </div>
                    @endif

                    <!-- Search Bar and Sort By Status Inline -->
                    <div class="flex items-center justify-between mb-6">
                        <!-- Search Bar -->
                        <input
                            type="text"
                            id="searchBar"
                            placeholder="Search cases..."
                            class="px-4 py-2 w-full sm:w-2/3 border rounded-md bg-gray-100 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-600 dark:focus:ring-blue-300" />

                        <!-- Sort by Open/Closed -->
                        <select id="sortByOpen" class="ml-4 px-4 py-2 w-full sm:w-1/3 border rounded-md bg-gray-100 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-600 dark:focus:ring-blue-300">
                            <option value="all">Sort by Status</option>
                            <option value="Open">Open</option>
                            <option value="Closed">Closed</option>
                        </select>
                    </div>

                    <!-- No Results case -->
                    <div id="noResultscase" class="hidden text-center text-gray-500 dark:text-gray-400 mb-4">
                        <p>No results found.</p>
                    </div>

                    @if($cases->isEmpty())
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-2 text-center">No cases found.</h3>
                    @endif

                    <!-- Cases Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="casesContainer">
                        @foreach ($cases as $case)
                        <div class="caseCard bg-white dark:bg-gray-700 rounded-lg shadow-lg overflow-hidden transform transition-transform duration-300 hover:scale-105 hover:shadow-xl" data-subject="{{ $case->subject }}" data-sender="{{ $case->sender->username }}" data-recipient="{{ $case->case_to }}" data-open="{{ $case->status === 'open' ? '1' : '0' }}">
                            <div class="p-6">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-2">{{ $case->title }}</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Case from: <span class="font-semibold">{{ $case->sender->username }}</span></p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Case to: <span class="font-semibold">{{ $case->case_to }}</span></p>
                                    </div>
                                </div>

                                <!-- Date and Status Section -->
                                <div class="flex justify-between items-center mt-4">
                                    <!-- Date -->
                                    <span class="text-xs text-gray-500 dark:text-gray-300">
                                        {{ $case->created_at->format('d M, Y') }}
                                    </span>

                                    <!-- Status -->
                                    <span class="text-xs font-semibold 
                                            {{ $case->status === 'open' ? 'text-green-600 bg-green-100' : 'text-red-600 bg-red-100' }} 
                                            rounded-full px-3 py-1">
                                        {{ $case->status === 'open' ? 'Open' : 'Closed' }}
                                    </span>
                                </div>

                                <!-- "View Case" Button -->
                                <div class="mt-4 text-right">
                                    @if (Auth::user()->role === 'police')
                                    <a href="{{ route('police.cases.show', $case->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300 inline-flex items-center">
                                        <i class="fas fa-eye mr-2"></i> <span>View Case</span>
                                    </a>
                                    @endif
                                    @if (Auth::user()->role === 'journalist')
                                    <a href="{{ route('journalist.cases.show', $case->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300 inline-flex items-center">
                                        <i class="fas fa-eye mr-2"></i> <span>View Case</span>
                                    </a>
                                    @endif
                                    @if (Auth::user()->role === 'victim')
                                    <a href="{{ route('victim.cases.show', $case->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300 inline-flex items-center">
                                        <i class="fas fa-eye mr-2"></i> <span>View Case</span>
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

    <!-- Search and Sort Scripts -->
    <script>
        // Search Bar Script
        document.getElementById("searchBar").addEventListener("input", function() {
            const searchQuery = this.value.toLowerCase();
            const caseCards = document.querySelectorAll(".caseCard");
            const noResultscase = document.getElementById("noResultscase");
            let visibleCards = 0;

            caseCards.forEach(function(card) {
                const subject = card.getAttribute("data-subject").toLowerCase();
                const sender = card.getAttribute("data-sender").toLowerCase();
                const recipient = card.getAttribute("data-recipient").toLowerCase();

                if (subject.includes(searchQuery) || sender.includes(searchQuery) || recipient.includes(searchQuery)) {
                    card.style.display = "block";
                    visibleCards++;
                } else {
                    card.style.display = "none";
                }
            });

            noResultscase.classList.toggle("hidden", visibleCards > 0);
        });

        // Sort by Open/Closed Script
        document.getElementById("sortByOpen").addEventListener("change", function() {
            const filterValue = this.value;
            const caseCards = document.querySelectorAll(".caseCard");
            let visibleCards = 0;

            caseCards.forEach(function(card) {
                const isOpen = card.getAttribute("data-open") === "1";

                if (filterValue === "all" || (filterValue === "Open" && isOpen) || (filterValue === "Closed" && !isOpen)) {
                    card.style.display = "block";
                    visibleCards++;
                } else {
                    card.style.display = "none";
                }
            });

            const noResultscase = document.getElementById("noResultscase");
            noResultscase.classList.toggle("hidden", visibleCards > 0);
        });
    </script>
</x-app-layout>
