<?php

use App\Models\Chapter;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {

    public ?Chapter $editing_chapter = null;

    public string $number = '';
    public string $title = '';

    #[On('show-edit-modal')]
    public function show_edit_modal(Chapter $chapter): void
    {
        $this->editing_chapter = $chapter;

        $this->number = $chapter->number;
        $this->title = $chapter->title;
        $this->modal('edit')->show();
    }

    protected function rules(): array
    {
        return [
            'number' => ['required', 'numeric'],
            'title' => ['required', 'min:2']
        ];
    }

    public function update_chapter(): void
    {
        $this->editing_chapter->update($this->validate());
        $this->modal('edit')->close();
        $this->dispatch("chapter-updated");
        Flux::toast(
            heading: 'ویرایش شد.',
            text: 'رشته '. $this->editing_chapter->title .' با موفقیت ویرایش شد.',
            variant: 'warning',
            position: 'top right'
        );
    }


    public function reset_prop(): void
    {
        $this->reset();
        $this->resetErrorBag();
    }

}; ?>

<div>

    <flux:modal name="edit" :show="$errors->isNotEmpty()" flyout focusable class="md:w-[400px]" @close="reset_prop"
                :dismissible="false">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{__('درج سرفصل جدید')}}</flux:heading>
                <flux:text class="mt-2">{{__('اطلاعات سرفصل جدید را وارد کنید.')}}</flux:text>
            </div>

            <form wire:submit.prevent="update_chapter" class="space-y-4 flex flex-col gap-3" autocomplete="off">

                <x-my.flt_lbl name="number" label="{{__('شماره فصل:')}}" maxlength="2" dir="ltr"
                              class="tracking-wider font-semibold" autofocus required/>

                <x-my.flt_lbl name="title" label="{{__('عنوان سرفصل:')}}" maxlength="100"
                              class="tracking-wider font-semibold" autofocus required/>
                <div class="flex">
                    <flux:spacer/>
                    <flux:button type="submit" variant="primary" color="teal" size="sm"
                                 class="cursor-pointer">{{__('ثبت')}}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
