<div>
    <x-buyer-layout>
        <div class="max-w-5xl mx-auto  mt-8">

    <form wire:submit="save">
        {{ $this->form }}
        <x-filament::button type="submit" class="mt-4 ">
            UPDATE
        </x-filament::button>
    </form>
        </div>
    </x-buyer-layout>
    <x-filament-actions::modals />
</div>
