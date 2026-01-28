<?php


use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination, WithoutUrlPagination;

    public string $sortBy = 'id';
    public string $sortDirection = 'desc';
    public int $perPage = 10;

    public ?int $highlightedUserId = null;


    public function sort($column): void
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
    public function users()
    {
        return User::query()
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    #[On('user-created')]
    public function afterCreated($id = null): void
    {
        $this->reset(['sortBy', 'sortDirection']);

        $user = User::find($id);
        if (!$user) {
            return;
        }
        $beforeCount = User::where('id', '>', $user->id)->count();
        $page = intdiv($beforeCount, $this->perPage) + 1;
        $this->gotoPage($page);

        $this->highlightedUserId = $user->id;
    }

}; ?>

<div>
    <flux:heading size="lg" level="1">
        {{__('اطلاعات پایه')}}
    </flux:heading>
    <div class="inline-flex mt-2 mb-4">
        <flux:text>{{__('کاربران شعب آموزشگاهها')}}</flux:text>
        <livewire:user.create/>
    </div>

    <flux:separator variant="subtle"/>

    <flux:table :paginate="$this->users" class="inline">
        <flux:table.columns>

            <flux:table.column align="center" sortable :sorted="$sortBy === 'id'" :direction="$sortDirection"
                               wire:click="sort('id')">
                {{__('شناسه')}}
            </flux:table.column>

            <flux:table.column align="center" sortable :sorted="$sortBy === 'user_name'" :direction="$sortDirection"
                               wire:click="sort('user_name')">
                {{__('نام کاربری')}}
            </flux:table.column>

            <flux:table.column align="center">
                {{__('نام و نام خانوادگی')}}
            </flux:table.column>

            <flux:table.column align="center">
                {{__('نقشها')}}
            </flux:table.column>

            <flux:table.column sortable :sorted="$sortBy === 'created_at'" :direction="$sortDirection"
                               wire:click="sort('created_at')">
                {{__('زمان ثبت')}}
            </flux:table.column>

            <flux:table.column sortable :sorted="$sortBy === 'updated_at'" :direction="$sortDirection"
                               wire:click="sort('updated_at')">
                {{__('زمان ویرایش')}}
            </flux:table.column>

        </flux:table.columns>

        <flux:table.rows>

            @if($highlightedUserId)
                <div x-data x-init="setTimeout(() => $wire.set('highlightedUserId', null), 2000)"></div>
            @endif
            @foreach ($this->users as $user)
                @php($class='')

                @if($highlightedUserId === $user->id)
                    @php($class='bg-green-100 dark:bg-green-900/40')
                @endif

                <flux:table.row class="{{$class}} dark:hover:bg-stone-900/80 transition duration-300 hover:bg-zinc-100"
                                :key="$user->id">
                    <flux:table.cell>{{ $user->id }}</flux:table.cell>
                    <flux:table.cell>{{ $user->user_name }}</flux:table.cell>
                    <flux:table.cell>{{ $user->profile->f_name_fa . ' ' . $user->profile->l_name_fa }}</flux:table.cell>
                    <flux:table.cell>
                        @foreach($user->getAllRolesWithBranches() as $role)
                            <flux:badge color="{{$role->role_color}}" size="sm">
                                {{ $role->role_name  }}
                                {{ $role->branch_name  }}
                            </flux:badge>
                        @endforeach
                    </flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">
                        <div class="leading-tight">
                            <div>{{ explode(' ', $user->jalali_created_at)[0] }}</div>
                            <div class="text-xs">
                                {{ substr($user->jalali_created_at, 11, 5) }}
                                <span class="text-stone-500">{{ substr($user->jalali_created_at, 17) }}</span>
                            </div>
                        </div>
                    </flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">
                        <div>{{ explode(' ', $user->jalali_updated_at)[0] }}</div>
                        <div class="text-xs">
                            {{ substr($user->jalali_updated_at, 11, 5) }}
                            <span class="text-stone-500">{{ substr($user->jalali_updated_at, 17) }}</span>
                        </div>
                    </flux:table.cell>

                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>


</div>
