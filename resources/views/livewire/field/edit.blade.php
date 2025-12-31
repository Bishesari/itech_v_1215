<?php

use App\Models\Field;
use Flux\Flux;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {

    public Field $field;

    public string $title = '';

    protected function rules(): array
    {
        return [
            'title' => ['required', 'min:2', Rule::unique('fields', 'title')->ignore($this->field)],
        ];
    }

    public function mount(): void
    {
        $this->title = $this->field->title;
    }


    public function update_field(): void
    {
        $this->field->update($this->validate());
        $this->modal('edit-field-' . $this->field->id)->close();
        $this->dispatch("f-updated");
    }

}; ?>

<div>
    <flux:tooltip content="ویرایش عنوان رشته" position="bottom">
        <flux:icon.pencil-square variant="micro" class="cursor-pointer size-5 text-yellow-500"
                                 x-on:click="$flux.modal('edit-field-{{ $field->id }}').show()"/>
    </flux:tooltip>

    <flux:modal name="edit-field-{{ $field->id }}" :show="$errors->isNotEmpty()" focusable class="md:w-96" flyout
                :dismissible="false">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{__('ویرایش رشته ')}} <span
                        class="font-bold text-yellow-500">{{ $field->title }}</span></flux:heading>
                <flux:text class="mt-2">{{__('اطلاعات رشته را جهت ویرایش وارد کنید.')}}</flux:text>
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
</div>
