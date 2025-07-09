<!-- Container -->
<div class="max-w-3xl mx-auto p-6 space-y-6">

    <!-- Post Card -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden transition-all duration-300">
        <!-- Post Image -->
        <img src="{{ asset($post->image) }}" alt="Post Image" class="w-full h-64 object-cover">

        <!-- Post Content -->
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-2">{{ $post->title }}</h1>
            <p class="text-gray-700 dark:text-gray-300">
                {{ $post->content }}
            </p>

            <!-- Author -->
            <div class="mt-4 flex items-center gap-3">
                <img src="https://i.pravatar.cc/40" class="w-10 h-10 rounded-full border dark:border-gray-600"
                    alt="User">
                <span class="font-medium text-sm text-gray-600 dark:text-gray-400">Posted by:
                    {{ $post->user->name }}</span>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6 shadow-sm">
        <h2 class="text-xl font-semibold mb-4">Reviews</h2>
        @forelse ($reviews as $rev)
            <!-- Review -->
            <div class="space-y-4">
                <div class="p-4 bg-white dark:bg-gray-700 rounded-lg shadow">
                    <p class="text-sm text-gray-800 dark:text-gray-200">
                        @for ($i = 1; $i <= 5; $i++)
                            <span
                                class="{{ $i <= $rev->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}">★</span>
                        @endfor - {{ $rev->review }}
                    </p>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-2">{{ $rev->user->name }}</div>
                </div>
            </div>
        @empty
            <p class="text-gray-500 dark:text-gray-400">No reviews available.</p>
        @endforelse
    </div>

    <!-- Dark Mode Toggle -->
    <div class="text-center mt-10">
        <button id="toggleDark" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700 transition">Toggle
            Dark Mode</button>
    </div>
</div>
<script>
    const html = document.documentElement;
    const btn = document.getElementById('toggleDark');
    btn.addEventListener('click', () => {
        html.classList.toggle('dark');
    });
</script>
