<?php

use App\Models\Role;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination;

    public string $sortBy = 'id';
    public string $sortDirection = 'desc';

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
    public function roles()
    {
        return Role::query()
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(12);
    }

    #[On('role-created')]
    public function refreshList(): void
    {
        $this->resetPage();
    }

    #[On('role-updated')]
    #[On('role-deleted')]
    public function roleChanged(): void
    {
        $this->dispatch('$refresh');
    }

}; ?>


<div>
    <flux:heading size="lg" level="1">
        {{__('اطلاعات پایه')}}
    </flux:heading>

    <div class="inline-flex mt-2 mb-4">
        <flux:text>{{__('نقشهای کاربری')}}</flux:text>
        <livewire:role.create/>
    </div>

    <flux:separator variant="subtle"/>

    <flux:table :paginate="$this->roles" class="inline">
        <flux:table.columns>
            <flux:table.column sortable :sorted="$sortBy === 'id'" :direction="$sortDirection"
                               wire:click="sort('id')">
                {{__('#')}}
            </flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'name_fa'" :direction="$sortDirection"
                               wire:click="sort('name_fa')">
                {{__('نقش کاربری')}}
            </flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'name_en'" :direction="$sortDirection"
                               wire:click="sort('name_en')">{{__('Role')}}</flux:table.column>

            <flux:table.column sortable :sorted="$sortBy === 'created_at'" :direction="$sortDirection"
                               wire:click="sort('created_at')">
                {{__('زمان ثبت')}}
            </flux:table.column>

            <flux:table.column align="center" sortable :sorted="$sortBy === 'updated_at'" :direction="$sortDirection"
                               wire:click="sort('updated_at')">
                {{__('زمان ویرایش')}}
            </flux:table.column>

            <flux:table.column>{{ __('عملیات') }}</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>

            @foreach ($this->roles as $role)
                <flux:table.row>
                    <flux:table.cell>{{ $role->id }}</flux:table.cell>
                    <flux:table.cell>{{ $role->name_fa }}</flux:table.cell>
                    <flux:table.cell>{{ $role->name_en }}</flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">
                        <div class="leading-tight">
                            <div>{{ explode(' ', $role->jalali_created_at)[0] }}</div>
                            <div class="text-xs">{{ substr($role->jalali_created_at, 11, 5) }}</div>
                        </div>
                    </flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">
                        <div>{{ explode(' ', $role->jalali_updated_at)[0] }}</div>
                        <div class="text-xs">{{ substr($role->jalali_updated_at, 11, 5) }}</div>
                    </flux:table.cell>


                    <flux:table.cell>
                        <div class="inline-flex items-center gap-2">
                            <livewire:role.edit :$role :key="'role-edit-'.$role->id"/>
                            <livewire:role.delete :$role :key="'role-delete-'.$role->id"/>
                        </div>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

</div>
