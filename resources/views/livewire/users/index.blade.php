<div>
    <div class="flex justify-end mr-6 pb-4">
        <a href="{{ route('users.create') }}" wire:navigate
            class="bg-indigo-500 hover:bg-indigo-700 p-3 rounded-2xl">Create</a>
    </div>
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif
    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search users..."
        class="w-full px-4 py-2 mb-4 border rounded-md focus:outline-none focus:ring
               bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-700" />
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        S.No
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Email
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Role
                    </th>
                    <th>
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $key => $user)
                    <tr
                        class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                        <th scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $key + 1 }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $user->name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-4">
                            <select wire:change="changeRole({{ $user->id }}, $event.target.value)"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700
                        bg-white dark:bg-gray-900 text-gray-900 dark:text-white
                        rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="creator" {{ $user->role == 'creator' ? 'selected' : '' }}>Creator
                                </option>
                                <option value="guest" {{ $user->role == 'guest' ? 'selected' : '' }}>Guest</option>
                            </select>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('users.edit', $user->id) }}" wire:navigate
                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a> |
                            <a wire:click="delete({{ $user->id }})" wire:navigate
                                wire:confirm="Are you sure want to delete!"
                                class="font-medium text-red-600 dark:text-red-500 hover:underline">Delete</a> 
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-6 py-4">
                            <span>No Users Available</span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-2 mt-6">{{ $users->links() }}</div>
    </div>
</div>
