<div>
    <div class="flex justify-end mr-6 pb-4">
        <a href="{{ route('posts.create') }}" wire:navigate
            class="bg-indigo-500 hover:bg-indigo-700 p-3 rounded-2xl">Create</a>
    </div>
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        S.No
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Title
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Image
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Content
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($posts as $key => $post)
                    <tr
                        class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                        <th scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $key + 1 }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $post->title }}
                        </td>
                        <td class="px-6 py-4">
                            <img src="{{ asset($post->image) }}" alt="image" class="w-12 h-12 rounded-2xl">
                        </td>
                        <td class="px-6 py-4">
                            {{ $post->content }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('posts.edit', $post->id) }}" wire:navigate
                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                            <a wire:click="delete({{ $post->id }})" wire:navigate wire:confirm="Are you sure want to delete!"
                                class="font-medium text-red-600 dark:text-red-500 hover:underline">Delete</a>
                        </td>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-6 py-4">
                            <span>No Posts Available</span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-2 mt-6">{{ $posts->links() }}</div>
    </div>

</div>
