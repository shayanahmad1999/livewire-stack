<div
    class="{{ $theme === 'dark' ? 'bg-gray-900 text-white' : 'bg-white text-gray-800' }} p-6 rounded shadow-lg transition-all duration-300">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Your Notifications</h2>
        <div class="space-x-2">
            <button wire:click="markAllAsRead" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Mark All as Read
            </button>
            <button wire:click="toggleTheme"
                class="bg-gray-200 dark:bg-gray-800 text-gray-800 dark:text-white px-4 py-2 rounded hover:bg-gray-300 dark:hover:bg-gray-700">
                Toggle {{ ucfirst($theme === 'light' ? 'dark' : 'light') }} Mode
            </button>
        </div>
    </div>

    @if ($unread->isEmpty() && $read->isEmpty())
        <p class="text-sm">You have no notifications.</p>
    @else
        @if ($unread->isNotEmpty())
            <h3 class="text-lg font-semibold mb-2">🔔 Unread Notifications</h3>
            <ul class="space-y-2 mb-6">
                @foreach ($unread as $notification)
                    <li
                        class="p-4 rounded border {{ $theme === 'dark' ? 'border-gray-700 bg-gray-800' : 'border-gray-300 bg-gray-50' }}">
                        <div class="flex justify-between items-center">
                            <div>{{ $notification->data['message'] }}</div>
                            <button wire:click="markAsRead('{{ $notification->id }}')"
                                class="text-sm text-indigo-600 hover:underline">
                                Mark as Read
                            </button>
                        </div>
                        <small class="text-gray-500">{{ $notification->created_at->diffForHumans() }}</small>
                    </li>
                @endforeach
            </ul>
        @endif

        @if ($read->isNotEmpty())
            <h3 class="text-lg font-semibold mb-2">📘 Read Notifications</h3>
            <ul class="space-y-2">
                @foreach ($read as $notification)
                    <li
                        class="p-4 rounded border {{ $theme === 'dark' ? 'border-gray-700 bg-gray-700 text-gray-300' : 'border-gray-300 bg-gray-100' }}">
                        <div>{{ $notification->data['message'] }}</div>
                        <small class="text-gray-500">{{ $notification->created_at->diffForHumans() }}</small>
                    </li>
                @endforeach
            </ul>
        @endif
    @endif
</div>
