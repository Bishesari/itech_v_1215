<?php

use App\Models\Province;
use Livewire\Attributes\Computed;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination;

    public string $sortBy = 'name_fa';
    public string $sortDirection = 'asc';

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    #[Computed]
    public function provinces()
    {
        return Province::query()
            ->withCount('cities') // حتی اگر صفر باشد، استان حذف نمی‌شود
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(8);
    }

}; ?>
<div>
    <flux:table :paginate="$this->provinces" class="inline">
        <flux:table.columns>
            <flux:table.column>{{__('#')}}</flux:table.column>
            <flux:table.column>{{__('استان')}}</flux:table.column>
            <flux:table.column>{{__('تعداد شهرها')}}</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>

            @foreach ($this->provinces as $province)
                <flux:table.row>
                    <flux:table.cell>{{ $province->id }}</flux:table.cell>
                    <flux:table.cell>{{ $province->name_fa }}</flux:table.cell>
                    <flux:table.cell class="text-center">
                        <flux:badge color="green" size="sm" inset="top bottom">{{ $province->cities_count }}</flux:badge>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

</div>
