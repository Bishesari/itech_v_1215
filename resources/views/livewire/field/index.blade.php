<?php

use App\Models\Field;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination;

    public string $sortBy = 'id';
    public string $sortDirection = 'desc';
    public int $perPage = 10;

    public ?int $lastCreatedFieldId = null;
    public ?int $lastUpdatedFieldId = null;


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
    public function fields()
    {
        return Field::query()
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    //   Edit Update

    public ?Field $editing_field;
    public string $title = '';
    protected function rules(): array
    {
        return [
            'title' => ['required', 'min:2', Rule::unique('fields', 'title')->ignore($this->editing_field)],
        ];
    }


    public function edit(Field $field): void
    {
        $this->editing_field = $field;
        $this->title = $field->title;
        $this->modal('edit')->show();
    }

    public function update()
    {
        $this->editing_field->update($this->validate());
        $this->modal('edit')->close();
        $this->dispatch("f-updated");

    }



    #[On('field-created')]
    public function afterCreated(): void
    {
        $this->lastCreatedFieldId = 10;
        $this->lastUpdatedFieldId = null;
    }

    #[On('f-updated')]
    public function afterUpdated(): void
    {
        $this->lastCreatedFieldId = null;
        $this->lastUpdatedFieldId = 20;
    }



}; ?>


<div>
    <flux:heading size="lg" level="1">
        {{__('اطلاعات پایه')}}
    </flux:heading>

    <div class="inline-flex mt-2 mb-4">
        <flux:text>{{__('رشته های آموزشی')}}</flux:text>

        {{-- Create Component --}}
        <livewire:field.create/>
    </div>
    <flux:separator variant="subtle"/>

    @if($lastCreatedFieldId)
        <div class="text-sm text-green-600 mb-2">
            Last created field id: {{ $lastCreatedFieldId }}
        </div>
    @endif

    @if($lastUpdatedFieldId)
        <div class="text-sm text-yellow-600">
            Updated ID: {{ $lastUpdatedFieldId }}
        </div>
    @endif


    <flux:table :paginate="$this->fields" class="inline">

        <flux:table.columns>
            <flux:table.column sortable :sorted="$sortBy === 'id'" :direction="$sortDirection"
                               wire:click="sort('id')">
                {{__('#')}}
            </flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'title'" :direction="$sortDirection"
                               wire:click="sort('title')">
                {{__('عنوان رشته')}}
            </flux:table.column>

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
            @foreach ($this->fields as $field)

                <flux:table.row>
                    <flux:table.cell>{{ $field->id }}</flux:table.cell>
                    <flux:table.cell>{{ $field->title }}</flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">
                        <div class="leading-tight">
                            <div>{{ explode(' ', $field->jalali_created_at)[0] }}</div>
                            <div class="text-xs">{{ substr($field->jalali_created_at, 11, 5) }}</div>
                        </div>
                    </flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">
                        <div>{{ explode(' ', $field->jalali_updated_at)[0] }}</div>
                        <div class="text-xs">{{ substr($field->jalali_updated_at, 11, 5) }}</div>
                    </flux:table.cell>


                    <flux:table.cell>

                        <div class="inline-flex items-center gap-2">
                            <flux:tooltip content="ویرایش عنوان رشته" position="bottom">
                                <flux:icon.pencil-square variant="micro" class="cursor-pointer size-5 text-yellow-500"
                                                         wire:click="edit({{ $field }})"
                                />
                            </flux:tooltip>

                        </div>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach

        </flux:table.rows>
    </flux:table>

    {{--    Edit Modal   --}}
    <flux:modal name="edit" :show="$errors->isNotEmpty()" focusable class="md:w-96" flyout :dismissible="false">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{__('ویرایش رشته ')}} <span
                        class="font-bold text-yellow-500">{{ $title }}</span></flux:heading>
                <flux:text class="mt-2">{{__('اطلاعات رشته را جهت ویرایش وارد کنید.')}}</flux:text>
            </div>

            <form wire:submit.prevent="update" class="space-y-4 flex flex-col gap-3" autocomplete="off">
                <x-my.flt_lbl name="title" label="{{__('عنوان رشته:')}}" maxlength="40"
                              class="tracking-wider font-semibold" autofocus required/>

                <div class="flex">
                    <flux:spacer/>
                    <flux:button type="submit" variant="primary" color="yellow" size="sm"
                                 class="cursor-pointer">{{__('ویرایش')}}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

</div>
