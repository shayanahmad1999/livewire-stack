<div class="space-y-6 text-sm text-gray-800 dark:text-gray-200">

    {{-- Review Form --}}
    @auth
        <form wire:submit.prevent="submit"
            class="p-4 border rounded-xl bg-white dark:bg-gray-800 dark:border-gray-700 shadow-md">
            @if (session()->has('message'))
                <div class="text-green-500 mb-2 dark:text-green-400">{{ session('message') }}</div>
            @endif

            <textarea wire:model="review" placeholder="Write your review..."
                class="w-full p-3 border rounded-md bg-gray-50 dark:bg-gray-900 dark:border-gray-600 resize-none focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            @error('review')
                <span class="text-red-500 text-sm dark:text-red-400">{{ $message }}</span>
            @enderror

            <div class="mt-4 flex items-center gap-2">
                <label for="rating" class="font-medium">Rating:</label>
                <select wire:model="rating" id="rating"
                    class="px-2 py-1 border rounded-md bg-gray-50 dark:bg-gray-900 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @for ($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}">{{ $i }} ⭐</option>
                    @endfor
                </select>
            </div>
            @error('rating')
                <span class="text-red-500 text-sm dark:text-red-400">{{ $message }}</span>
            @enderror

            <button type="submit"
                class="mt-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition duration-200 ease-in-out">
                Submit Review
            </button>
        </form>
    @else
        <p class="text-gray-500 dark:text-gray-400">
            Please <a href="{{ route('login') }}" class="underline text-blue-600 dark:text-blue-400">login</a> to write a
            review.
        </p>
    @endauth

    {{-- Display Reviews --}}
    @forelse ($reviews as $rev)
        <div class="border rounded-xl p-4 bg-white dark:bg-gray-800 dark:border-gray-700 shadow-sm">
            <div class="flex justify-between items-center mb-2">
                <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $rev->user->name }}</span>
                <span>
                    @for ($i = 1; $i <= 5; $i++)
                        <span
                            class="{{ $i <= $rev->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}">★</span>
                    @endfor
                </span>
            </div>
            <p class="text-gray-700 dark:text-gray-300">{{ $rev->review }}</p>
        </div>
    @empty
        <p class="text-gray-500 dark:text-gray-400">No reviews available.</p>
    @endforelse

</div>
