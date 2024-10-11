<x-filament-panels::page>
    <!-- {{ $this->getRecord()->order_id }} -->

    {{ $this->orderInfolist }}

    {{ $this->appraiserInfolist }}

    <div>
        {{ $this->table }}
    </div>
</x-filament-panels::page>
