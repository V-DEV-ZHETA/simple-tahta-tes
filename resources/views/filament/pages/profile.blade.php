<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="flex justify-end gap-x-3 mt-6">
            <x-filament::button
                type="submit"
                size="lg"
                color="success"
                icon="heroicon-o-check-circle"
            >
                Save Changes
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>