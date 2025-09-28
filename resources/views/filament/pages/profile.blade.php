<x-filament-panels::page>
    {{ $this->form }}

    <x-filament::section>
        <x-slot name="footer">
            <x-filament::button
                type="submit"
                form="data"
                class="fi-btn-fi-primary"
            >
                Simpan
            </x-filament::button>
        </x-slot>
    </x-filament::section>
</x-filament-panels::page>
