<?php

use App\Models\Standard;
use Livewire\Attributes\Computed;
use Livewire\Volt\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination, WithoutUrlPagination;

    public string $sortBy = 'name_fa';
    public string $sortDirection = 'asc';
    public int $perPage = 10;

    public ?int $highlightedStandardId = null;


    public function mount($highlight_id = null): void
    {
        if ($highlight_id){
            $this->afterComeBack($highlight_id);
        }
    }
    public function sort($column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    #[Computed]
    public function standards()
    {
        return Standard::query()
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function afterComeBack($id = null): void
    {
        $this->reset(['sortBy', 'sortDirection']);

        $standard = Standard::find($id);
        if (!$standard) {
            return;
        }
        $beforeCount = Standard::where('name_fa', '<', $standard->name_fa)->count();
        $page = intdiv($beforeCount, $this->perPage) + 1;
        $this->gotoPage($page);

        $this->highlightedStandardId = $standard->id;
    }

}; ?>

<div>
    <flux:heading size="lg" level="1">
        {{__('اطلاعات پایه')}}
    </flux:heading>
    <div class="inline-flex mt-2 mb-4">
        <flux:text>{{__('استانداردهای آموزشی')}}</flux:text>
        <flux:tooltip content="استاندارد جدید" position="left">
            <flux:link href="{{ route('standard.create') }}" wire:navigate x-data="{ loading: false }"
                       @click="loading = true">
                {{-- آیکن پلاس --}}
                <flux:icon.plus-circle x-show="!loading" variant="micro" class="size-5 text-blue-500 mr-3"/>
                {{-- لودر --}}
                <flux:icon.loading x-show="loading" class="size-5 animate-spin text-blue-500 mr-3"/>
            </flux:link>
        </flux:tooltip>
    </div>


    <flux:separator variant="subtle"/>

    <flux:table :paginate="$this->standards">

        <flux:table.columns>

            <flux:table.column sortable :sorted="$sortBy === 'id'" :direction="$sortDirection"
                               wire:click="sort('id')">
                {{__('#')}}
            </flux:table.column>

            <flux:table.column>{{__('رشته')}}</flux:table.column>

            <flux:table.column sortable :sorted="$sortBy === 'name_fa'" :direction="$sortDirection"
                               wire:click="sort('name_fa')">
                {{__('نام فارسی')}}
            </flux:table.column>

            <flux:table.column sortable :sorted="$sortBy === 'code'" :direction="$sortDirection"
                               wire:click="sort('code')">{{__('کد استاندارد')}}
            </flux:table.column>

            <flux:table.column sortable :sorted="$sortBy === 'sum_h'" :direction="$sortDirection"
                               wire:click="sort('sum_h')">{{__('مجموع')}}
            </flux:table.column>

            <flux:table.column sortable :sorted="$sortBy === 'required_h'" :direction="$sortDirection"
                               wire:click="sort('required_h')">{{__('مورد نیاز')}}
            </flux:table.column>

            <flux:table.column>{{__('فعال')}}</flux:table.column>


            <flux:table.column sortable :sorted="$sortBy === 'created_at'" :direction="$sortDirection"
                               wire:click="sort('created_at')">
                {{__('زمان ثبت')}}
            </flux:table.column>

            <flux:table.column sortable :sorted="$sortBy === 'updated_at'" :direction="$sortDirection"
                               wire:click="sort('updated_at')">
                {{__('زمان ویرایش')}}
            </flux:table.column>

            <flux:table.column>{{ __('عملیات') }}</flux:table.column>
        </flux:table.columns>


        <flux:table.rows>

            @if($highlightedStandardId)
                <div x-data x-init="setTimeout(() => $wire.set('highlightedStandardId', null), 1000)"></div>
            @endif

            @foreach ($this->standards as $standard)

                @php($class='')
                @if($highlightedStandardId === $standard->id)
                    @php($class='bg-green-100 dark:bg-green-900/40')
                @endif
                <flux:table.row class="{{$class}} dark:hover:bg-stone-900/80 transition duration-300 hover:bg-zinc-100"
                                :key="$standard->id">
                    <flux:table.cell>
                        <flux:heading class="flex items-center gap-1">
                            {{$standard->id}}
                            <flux:tooltip toggleable position="left">
                                <flux:button icon="information-circle" size="sm" variant="ghost"
                                             class="cursor-pointer"/>
                                <flux:tooltip.content class="max-w-[20rem] space-y-2">
                                    <p class="text-justify">{{__('نام لاتین: ')}}{{ $standard->name_en }}</p>
                                    <p class="text-justify">
                                        {{__('نظری: ')}}{{ $standard->nazari_h }}{{__('، ')}}
                                        {{__('عملی: ')}}{{ $standard->amali_h }}{{__('، ')}}
                                        {{__('کارورزی: ')}}{{ $standard->karvarzi_h }}{{__('، ')}}
                                        {{__('ساعت پروژه: ')}}{{ $standard->project_h }}
                                    </p>
                                </flux:tooltip.content>
                            </flux:tooltip>
                        </flux:heading>

                    </flux:table.cell>
                    <flux:table.cell>{{ $standard->field->title }}</flux:table.cell>
                    <flux:table.cell>{{ $standard->name_fa }}</flux:table.cell>
                    <flux:table.cell>{{ $standard->code }}</flux:table.cell>
                    <flux:table.cell>{{ $standard->sum_h }}</flux:table.cell>
                    <flux:table.cell>{{ $standard->required_h }}</flux:table.cell>

                    <flux:table.cell>
                        <div class="inline-flex items-center gap-2">
                            <flux:badge size="sm" color="{{ $standard->is_active ? 'green' : 'red' }}">
                                {{ $standard->is_active ? 'فعال' : 'غیرفعال' }}
                            </flux:badge>

                            @php($checked = $standard->is_active ? 'checked' : null)
                            <flux:switch :$checked
                                         wire:key="standard-switch-{{ $standard->id }}-{{ $standard->is_active }}"
                                         wire:click="toggleStatus({{ $standard->id }})"
                                         wire:loading.attr="disabled"
                            />

                        </div>
                    </flux:table.cell>


                    <flux:table.cell class="whitespace-nowrap">
                        <div class="leading-tight">
                            <div>{{ explode(' ', $standard->jalali_created_at)[0] }}</div>
                            <div class="text-xs">{{ substr($standard->jalali_created_at, 11, 5) }}</div>
                        </div>
                    </flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">
                        <div>{{ explode(' ', $standard->jalali_updated_at)[0] }}</div>
                        <div class="text-xs">{{ substr($standard->jalali_updated_at, 11, 5) }}</div>
                    </flux:table.cell>

                    <flux:table.cell>
                        <div class="inline-flex items-center gap-2">
                            <flux:link href="{{ route('standard.edit', $standard) }}" variant="subtle" wire:navigate
                                       x-data="{ loading: false }" @click="loading = true">
                                <flux:icon.pencil-square variant="micro" x-show="!loading"
                                                         class="size-5 text-yellow-500"/>
                                <flux:icon.loading x-show="loading" class="size-5 text-yellow-500"/>
                            </flux:link>
                        </div>
                    </flux:table.cell>

                </flux:table.row>
            @endforeach

        </flux:table.rows>
    </flux:table>
</div>
