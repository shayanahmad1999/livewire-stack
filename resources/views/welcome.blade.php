<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Welcome to My Blog</title>

    <!-- Tailwind Config -->
    <script>
        window.tailwind = {
            config: {
                darkMode: 'class',
            }
        };
    </script>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Dark Mode Script -->
    <script>
        function toggleTheme() {
            const root = document.documentElement;
            root.classList.toggle('dark');
            localStorage.setItem('theme', root.classList.contains('dark') ? 'dark' : 'light');
        }

        // Apply saved theme
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>

<body
    class="min-h-screen flex flex-col bg-white text-gray-900 dark:bg-gray-900 dark:text-white transition-colors duration-300">

    <!-- Header Nav -->
    <header class="flex justify-between items-center px-6 py-4 bg-gray-100 dark:bg-gray-800">
        <!-- Auth Links (Left) -->
        <div>
            @if (Route::has('login'))
                <nav class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="px-4 py-1.5 text-sm border rounded text-[#1b1b18] dark:text-[#EDEDEC] border-gray-300 dark:border-gray-600 hover:border-gray-500 dark:hover:border-gray-400">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-4 py-1.5 text-sm border rounded text-[#1b1b18] dark:text-[#EDEDEC] border-transparent hover:border-gray-300 dark:hover:border-gray-600">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="px-4 py-1.5 text-sm border rounded text-[#1b1b18] dark:text-[#EDEDEC] border-gray-300 dark:border-gray-600 hover:border-gray-500 dark:hover:border-gray-400">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </div>

        <!-- Theme Toggle Button (Right) -->
        <button onclick="toggleTheme()"
            class="text-xl px-4 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition">
            🌓
        </button>
    </header>

    <!-- Page Content -->
    <div class="flex-grow">
        <section class="text-center py-10 px-4">
            <h1 class="text-4xl font-bold mb-2">Welcome to My Blog</h1>
            <p class="text-lg">Discover the latest posts and ideas.</p>
        </section>

        <main class="max-w-7xl mx-auto px-4 py-8 grid gap-6 lg:grid-cols-3">
            @foreach ($posts as $post)
                <article class="p-6 rounded shadow-md bg-gray-50 dark:bg-gray-800">
                    <div class="flex items-center mb-2">
                        <div
                            class="h-10 w-10 rounded-full bg-gray-300 dark:bg-neutral-700 flex items-center justify-center text-white font-bold">
                            @if ($post->image)
                                <img src="{{ asset($post->image) }}" alt="image" class="w-12 h-12 rounded-2xl">
                            @else
                                {{ strtoupper(substr($post->user->name, 0, 1)) }}
                            @endif
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $post->user->name }}</p>
                        </div>
                    </div>
                    <h2 class="text-2xl font-semibold mb-2">{{ $post->title }}</h2>
                    <p> {{ $post->content }}</p>
                    <div class="flex items-center space-x-1 mt-4">
                        @auth
                            @for ($i = 1; $i <= 5; $i++)
                                <svg onclick="submitRating({{ $post->id }}, {{ $i }})"
                                    class="cursor-pointer w-5 h-5 {{ $i <= $post->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                    fill="currentColor">
                                    viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.174 3.621a1 1 0 00.95.69h3.8c.969 0 1.371 1.24.588 1.81l-3.073 2.23a1 1 0 00-.364 1.118l1.174 3.621c.3.921-.755 1.688-1.54 1.118L10 13.347l-3.073 2.23c-.784.57-1.838-.197-1.54-1.118l1.174-3.621a1 1 0 00-.364-1.118L3.124 9.048c-.783-.57-.38-1.81.588-1.81h3.8a1 1 0 00.95-.69l1.174-3.621z" />
                                </svg>
                            @endfor
                        @else
                            <p class="text-gray-500 dark:text-gray-400">
                                Please <a href="{{ route('login') }}"
                                    class="underline text-blue-600 dark:text-blue-400">login</a> to write a
                                review.
                            </p>
                        @endauth
                    </div>
                </article>
            @endforeach
        </main>
        <div class="p-5">
            {{ $posts->links() }}
        </div>
    </div>

    <!-- Footer always sticks to bottom -->
    <footer class="p-4 text-center bg-gray-100 dark:bg-gray-800">
        <p>&copy; {{ date('Y') }} by Shayan. All rights reserved.</p>
    </footer>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        function submitRating(postId, rating) {
            $.ajax({
                url: '/user/review',
                method: 'POST',
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: JSON.stringify({
                    post_id: postId,
                    rating: rating
                }),
                success: function(response) {
                    showToast('success', response.message);
                },
                error: function(xhr) {
                    let message = 'An error occurred.';
                    try {
                        const response = JSON.parse(xhr.responseText);
                        message = response.errors ? response.errors.join('\n') : response.message;
                    } catch (e) {
                        message = 'Invalid response from server.';
                    }
                    showToast('error', message);
                }
            });
        }

        function showToast(type, message) {
            const bg = type === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
            const toast = document.createElement('div');
            toast.className = `fixed top-5 right-5 px-4 py-2 rounded shadow ${bg}`;
            toast.textContent = message;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 4000);
        }
    </script>

    {{-- <script>
        function submitRating(postId, rating) {
            fetch('/user/review', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        post_id: postId,
                        rating: rating
                    })
                })
                .then(res => res.json().then(data => ({
                    ok: res.ok,
                    data
                })))
                .then(({
                    ok,
                    data
                }) => {
                    if (ok) {
                        showToast('success', data.message);
                    } else {
                        const message = data.errors ? data.errors.join('\n') : data.message;
                        showToast('error', message);
                    }
                })
                .catch(() => showToast('error', 'Network error. Please try again.'));
        }

        function showToast(type, message) {
            const bg = type === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
            const toast = document.createElement('div');
            toast.className = `fixed top-5 right-5 px-4 py-2 rounded shadow ${bg}`;
            toast.textContent = message;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 4000);
        }
    </script> --}}

</body>

</html>
