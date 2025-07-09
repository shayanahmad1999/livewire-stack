<div>
    <div class="flex justify-end mr-6 pb-4">
        <a href="{{ route('users.index') }}" wire:navigate
            class="bg-indigo-500 hover:bg-indigo-700 p-3 rounded-2xl">View</a>
    </div>

    <form wire:submit="update" class="mx-auto max-w-md flex flex-col gap-6 bg-slate-900 shadow-2xl rounded-2xl p-4">
        <!-- Name -->
        <flux:input wire:model="form.name" :label="__('Name')" type="text" required autofocus autocomplete="name"
            :placeholder="__('Full name')" />

        <!-- Email Address -->
        <flux:input wire:model="form.email" :label="__('Email address')" type="email" required autocomplete="email"
            placeholder="email@example.com" />

        <!-- Password -->
        <flux:input wire:model="form.password" :label="__('Password')" type="password"
            autocomplete="new-password" :placeholder="__('Password')" viewable />

        <!-- Confirm Password -->
        <flux:input wire:model="form.password_confirmation" :label="__('Confirm password')" type="password"
            autocomplete="new-password" :placeholder="__('Confirm password')" viewable />

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Update User') }}
            </flux:button>
        </div>
    </form>
</div>
