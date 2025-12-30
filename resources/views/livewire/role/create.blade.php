<?php

use App\Models\Role;
use Flux\Flux;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {

    public string $name_fa = '';
    public string $name_en = '';

    protected function rules(): array
    {
        return [
            'name_fa' => ['required', 'min:2', Rule::unique('roles', 'name_fa')],
            'name_en' => ['required', 'min:2', Rule::unique('roles', 'name_en')],
        ];
    }


    public function save(): void
    {
        $role = Role::create($this->validate());
        $this->modal('new-role')->close();
        $this->dispatch('role-created', id: $role->id);

        Flux::toast(
            heading: 'ثبت شد.',
            text: 'نقش جدید با موفقیت ثبت شد.',
            variant: 'success',
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
    <flux:tooltip content="نقش جدید" position="left">
        <flux:icon.plus-circle variant="micro" class="cursor-pointer size-5 text-blue-500 mr-4"
                               x-on:click="$flux.modal('new-role').show()"/>
    </flux:tooltip>

    <flux:modal name="new-role" :show="$errors->isNotEmpty()" focusable class="md:w-96" @close="reset_prop"
                :dismissible="false">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{__('درج نقش جدید')}}</flux:heading>
                <flux:text class="mt-2">{{__('اطلاعات نقش کاربری جدید را وارد کنید.')}}</flux:text>
            </div>

            <form wire:submit.prevent="save" class="space-y-4 flex flex-col gap-3" autocomplete="off">
                <x-my.flt_lbl name="name_fa" label="{{__('نام نقش فارسی:')}}" maxlength="50"
                              class="tracking-wider font-semibold" autofocus required/>
                <x-my.flt_lbl name="name_en" label="{{__('نام نقش لاتین:')}}" dir="ltr" maxlength="50"
                              class="tracking-wider font-semibold" required/>

                <div class="flex">
                    <flux:spacer/>
                    <flux:button type="submit" variant="primary" color="teal" size="sm"
                                 class="cursor-pointer">{{__('ثبت')}}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
