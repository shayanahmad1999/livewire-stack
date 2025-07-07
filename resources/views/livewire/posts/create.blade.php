<div>
    <div class="flex justify-end mr-6 pb-4">
        <a href="{{ route('posts.index') }}" wire:navigate
            class="bg-indigo-500 hover:bg-indigo-700 p-3 rounded-2xl">View</a>
    </div>
    <form wire:submit="store" class="mx-auto max-w-md flex flex-col gap-6 bg-slate-900 shadow-2xl rounded-2xl p-4">
        <!-- Title -->
        <flux:input wire:model="form.title" :label="__('Title')" type="text" required autofocus autocomplete="title"
            placeholder="Title..." />
        <!-- Image -->
        <flux:input wire:model="form.image" :label="__('Image')" type="file" required autofocus />
        <div>
            @if ($form->image)
                <img src="{{ $form->image->temporaryUrl() }}" alt="image" class="w-12 h-12 rounded-2xl">
            @endif
        </div>
        <!-- Content -->
        <flux:textarea wire:model="form.content" :label="__('Content')" placeholder="Content..." />



        <div class="flex items-center justify-end">
            <flux:button variant="primary" type="submit" class="w-full">{{ __('Create') }}</flux:button>
        </div>
    </form>
</div>
