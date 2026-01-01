<?php

use App\Models\Field;
use Flux\Flux;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {

    public ?Field $deleting_field = null;
    public string $title = '';

    #[On('show-delete-modal')]
    public function show_delete_modal(Field $field): void
    {
        $this->deleting_field = $field;
        $this->title = $field->title;
        $this->modal('delete')->show();
    }

    public function delete_field(): void
    {
        $this->deleting_field->delete();
        $this->modal('delete')->close();
        $this->dispatch("field-deleted");
        Flux::toast(
            heading: 'حذف شد.',
            text: 'رشته '. $this->deleting_field->title . 'با موفقیت حذف شد.',
            variant: 'danger',
            position: 'top right'
        );
    }


};
?>
{{--    Confirm Delete Modal   --}}
<flux:modal name="delete" class="md:w-96">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">{{__('حذف رشته ')}} <span
                    class="font-bold text-red-500 dark:text-red-400">{{$this->deleting_field?->title }}</span></flux:heading>
            <flux:text class="mt-2">{{__('با تایید اطلاعات مربوطه حذف خواهند شد.')}}</flux:text>
        </div>

        <div class="flex gap-2">
            {{-- دکمه انصراف --}}
            <flux:button x-on:click="$flux.modal('delete').close()" variant="ghost" size="sm" class="flex-1 cursor-pointer">
                {{__('انصراف')}}
            </flux:button>

            {{-- دکمه تایید با لودینگ --}}
            <flux:button wire:click="delete_field" variant="primary" color="red" size="sm" class="flex-1 cursor-pointer">
                <span wire:target="delete_field">{{__('تایید حذف')}}</span>
            </flux:button>
        </div>
    </div>
</flux:modal>

