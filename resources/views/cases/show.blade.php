<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Case Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- case Header -->
                    <div class="mb-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-2">{{ $case->title }}</h3>
                                <p><span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Case ID:</span> {{ $case->case_number }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">From: <span class="font-semibold">{{ $case->sender->username }}</span></p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">To: <span class="font-semibold">{{ $case->case_to }}</span></p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="text-sm text-gray-500 dark:text-gray-300 ml-2 bg-purple-600 text-white rounded-full px-3 py-1">
                                    Sent on: {{ $case->created_at->format('d M, Y') }}
                                </div>
                                @if (Auth::user()->role === 'victim')
                                <form action='{{ route("$role.cases.destroy", $case->id) }}' method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition duration-300"
                                        onclick="confirmDelete(this.form);">
                                        <i class="fas fa-trash mr-2"></i> Delete
                                    </button>
                                </form>

                                <form action='{{ route("$role.cases.close", $case->id) }}' method="POST" style="display: inline;">
                                    @csrf
                                    <button type="button"
                                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition duration-300"
                                        onclick="confirmClose(this.form);">
                                        <i class="fas fa-times mr-2"></i> Close Case
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                        <!-- Status -->
                        <div>
                            @if ($case->status === 'open')
                            <span class="text-xs text-gray-500 dark:text-gray-300 ml-2 bg-green-600 text-white rounded-full px-3 py-1">Status: Open</span>
                            @else
                            <span class="text-xs text-gray-500 dark:text-gray-300 ml-2 bg-red-600 text-white rounded-full px-3 py-1">Status: Closed</span>
                            @endif
                        </div>
                    </div>


                    <!-- case Body -->
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">Case:</h4>
                        <p class="text-gray-600 dark:text-gray-400 whitespace-pre-line">{{ $case->description }}</p>
                    </div>

                    @if (count($replies) > 0)
                    <!-- Replies Section -->
                    <div class="mt-6">
                        <h4 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Replies:</h4>
                        @foreach ($replies as $reply)
                        <div class="mb-6 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow-sm">
                            <div class="flex justify-between mb-2">
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400"><strong>From:</strong> {{ $reply->sender->username }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Sent on:</strong> {{ $reply->created_at->format('d M, Y') }}</p>
                                </div>
                                <div>
                                    @if ($reply->sender->id === Auth::id())
                                    <span class="text-xs text-gray-500 dark:text-gray-300 bg-blue-600 text-white rounded-full px-3 py-1">You</span>
                                    @endif
                                </div>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400 whitespace-pre-line">{{ $reply->replyMessage }}</p>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex items-center space-x-4 float-right mb-4">
                        <a href='{{ route("cases.for.$role") }}' class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition duration-300 inline-flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i> Back to cases
                        </a>
                        <button id="replyButton" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300 inline-flex items-center">
                            <i class="fas fa-envelope mr-2"></i> Reply
                        </button>
                    </div>

                    <!-- Reply Form (hidden initially) -->
                    <div id="replyForm" class="mt-6 hidden">
                        <form action='{{ route("$role.cases.reply", $case->id) }}' method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="replyMessage" class="block text-sm text-gray-600 dark:text-gray-400">Your Reply:</label>
                                <textarea id="replyMessage" name="replyMessage" rows="4" class="w-full border rounded-md bg-gray-100 dark:bg-gray-700 dark:text-white p-2" required></textarea>
                                @error('replyMessage')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition duration-300 inline-flex items-center">
                                    <i class="fas fa-paper-plane mr-2"></i> Send Reply
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>

<!-- JavaScript to toggle the reply form -->
<script>
    document.getElementById('replyButton').addEventListener('click', function() {
        const replyForm = document.getElementById('replyForm');
        if (replyForm.classList.contains('hidden')) {
            replyForm.classList.remove('hidden');
        } else {
            replyForm.classList.add('hidden');
        }
    });

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

    // close case
    function confirmClose(form) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, close it!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });

    }
</script>