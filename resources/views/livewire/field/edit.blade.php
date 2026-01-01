<?php

use App\Models\Field;
use Flux\Flux;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {

    public ?Field $editing_field = null;
    public string $title = '';

    #[On('show-edit-modal')]
    public function show_edit_modal(Field $field): void
    {
        $this->editing_field = $field;
        $this->title = $field->title;
        $this->modal('edit')->show();
    }

    protected function rules(): array
    {
        return [
            'title' => ['required', 'min:2', Rule::unique('fields', 'title')->ignore($this->editing_field)],
        ];
    }


    public function update_field(): void
    {
        $this->editing_field->update($this->validate());
        $this->modal('edit')->close();
        $this->dispatch("field-updated", id: $this->editing_field->id);
        Flux::toast(
            heading: 'ویرایش شد.',
            text: 'رشته '. $this->editing_field->title .' با موفقیت ویرایش شد.',
            variant: 'warning',
            position: 'top right'
        );
    }


};
?>
<flux:modal name="edit" :show="$errors->isNotEmpty()" focusable class="md:w-96" flyout :dismissible="false">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">{{__('ویرایش رشته ')}}
                <span class="font-bold text-yellow-500">
                     @if($editing_field)
                        {{ $title }}
                    @endif
                </span></flux:heading>
            <flux:text class="mt-2">{{__('اطلاعات رشته را ویرایش کنید.')}}</flux:text>
        </div>

        <form wire:submit.prevent="update_field" class="space-y-4 flex flex-col gap-3" autocomplete="off">
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
