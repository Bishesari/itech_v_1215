<?php

use App\Models\Chapter;
use App\Models\Field;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {

    public ?Chapter $deleting_chapter = null;
    public string $title = '';

    #[On('show-delete-modal')]
    public function show_delete_modal(Chapter $chapter): void
    {
        $this->deleting_chapter = $chapter;
        $this->title = $chapter->title;
        $this->modal('delete')->show();
    }

    public function delete_chapter(): void
    {
        $this->deleting_chapter->delete();
        $this->modal('delete')->close();
        $this->dispatch("chapter-deleted");
        Flux::toast(
            heading: 'حذف شد.',
            text: 'رشته ' . $this->deleting_chapter->title . 'با موفقیت حذف شد.',
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
            <flux:heading size="lg">{{__('حذف سرفصل ')}} <span
                    class="font-bold text-red-500 dark:text-red-400">{{$this->deleting_chapter?->title }}</span>
            </flux:heading>
            <flux:text class="mt-2">{{__('با تایید اطلاعات مربوطه حذف خواهند شد.')}}</flux:text>
        </div>

        <div class="flex gap-2">
            {{-- دکمه انصراف --}}
            <flux:button x-on:click="$flux.modal('delete').close()" variant="ghost" size="sm"
                         class="flex-1 cursor-pointer">
                {{__('انصراف')}}
            </flux:button>

            {{-- دکمه تایید با لودینگ --}}
            <flux:button wire:click="delete_chapter" variant="primary" color="red" size="sm"
                         class="flex-1 cursor-pointer">
                <span wire:target="delete_field">{{__('تایید حذف')}}</span>
            </flux:button>
        </div>
    </div>
</flux:modal>

