<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Message Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Message Header -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-2">{{ $message->subject }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">From: <span class="font-semibold">{{ $message->sender->username }}</span></p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">To: <span class="font-semibold">{{ $message->receiver->username }}</span></p>
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-300 ml-2 bg-purple-600 text-white rounded-full px-3 py-1">
                                Sent on: {{ $message->created_at->format('d M, Y') }}
                            </div>
                        </div>
                        <!-- Status & Actions -->
                        <div>
                            @if ($message->is_read)
                            <span class="text-xs text-gray-500 dark:text-gray-300 ml-2 bg-green-600 text-white rounded-full px-3 py-1">Status: Read</span>
                            @else
                            <span class="text-xs text-gray-500 dark:text-gray-300 ml-2 bg-red-600 text-white rounded-full px-3 py-1">Status: Unread</span>
                            @endif
                        </div>
                    </div>

                    <!-- Message Body -->
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">Message:</h4>
                        <p class="text-gray-600 dark:text-gray-400 whitespace-pre-line">{{ $message->message }}</p>
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
                        <a href='{{ route("msg.for.$role") }}' class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition duration-300 inline-flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Messages
                        </a>
                        <button id="replyButton" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300 inline-flex items-center">
                            <i class="fas fa-envelope mr-2"></i> Reply
                        </button>
                    </div>

                    <!-- Reply Form (hidden initially) -->
                    <div id="replyForm" class="mt-6 hidden">
                        <form action='{{ route("$role.messages.reply", $message->id) }}' method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="replyMessage" class="block text-sm text-gray-600 dark:text-gray-400">Your Reply:</label>
                                <textarea id="replyMessage" name="replyMessage" rows="4" class="w-full border rounded-md bg-gray-100 dark:bg-gray-700 dark:text-white p-2" required></textarea>
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
</script>