<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            {{-- Display Reviews --}}
            @forelse ($reviews as $rev)
                <div class="border rounded-xl p-4 bg-white dark:bg-gray-800 dark:border-gray-700 shadow-sm">
                    <div class="flex items-center mb-2">
                        <div
                            class="h-10 w-10 rounded-full bg-gray-300 dark:bg-neutral-700 flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr($rev->user->name, 0, 1)) }}
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $rev->user->name }}</p>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-1">{{ $rev->title }}</h3>
                    <p class="text-gray-800 dark:text-gray-200 mb-3">{{ $rev->content }}</p>

                    {{-- Nested loop for reviews --}}
                    @forelse ($rev->reviews as $review)
                        <div class="flex justify-between items-center mb-2">
                            <span
                                class="font-semibold text-gray-900 dark:text-gray-100">{{ $review->user->name }}</span>
                            <span>
                                @for ($i = 1; $i <= 5; $i++)
                                    <span
                                        class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}">★</span>
                                @endfor
                            </span>
                        </div>
                        <p class="text-gray-700 dark:text-gray-300 mb-2">{{ $review->review }}</p>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400">No reviews for this post.</p>
                    @endforelse
                </div>
            @empty
                <p class="text-gray-500 dark:text-gray-400">No posts found.</p>
            @endforelse
        </div>
        {{ $reviews->links() }}
    </div>
</x-layouts.app>
