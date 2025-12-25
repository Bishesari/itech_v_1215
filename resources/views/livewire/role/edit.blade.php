<?php

use App\Models\Role;
use Flux\Flux;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {

    public Role $role;

    public string $name_fa = '';
    public string $name_en = '';

    protected function rules(): array
    {
        return [
            'name_fa' => ['required', 'min:2', Rule::unique('roles', 'name_fa')->ignore($this->role)],
            'name_en' => ['required', 'min:2', Rule::unique('roles', 'name_en')->ignore($this->role)],
        ];
    }

    public function mount(): void
    {
        $this->name_fa = $this->role->name_fa;
        $this->name_en = $this->role->name_en;
    }


    public function update_role(): void
    {
        $this->role->update($this->validate());
        $this->modal('edit-role-' . $this->role->id)->close();
        $this->dispatch('role-updated');

        Flux::toast(
            heading: 'تغییرات اعمال شد.',
            text: 'نقش ' . $this->name_fa . ' با موفقیت ویرایش شد.',
            variant: 'warning'
        );
    }

}; ?>

<div>
    <flux:tooltip content="ویرایش نقش" position="bottom">
        <flux:icon.pencil-square variant="micro" class="cursor-pointer size-5 text-yellow-500"
                                 x-on:click="$flux.modal('edit-role-{{ $role->id }}').show()"/>
    </flux:tooltip>

    <flux:modal name="edit-role-{{ $role->id }}" :show="$errors->isNotEmpty()" focusable class="md:w-96" flyout
                :dismissible="false">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{__('ویرایش نقش کاربری ')}} <span
                        class="font-bold text-yellow-500">{{ $role->name_fa }}</span></flux:heading>
                <flux:text class="mt-2">{{__('اطلاعات نقش را جهت ویرایش وارد کنید.')}}</flux:text>
            </div>

            <form wire:submit.prevent="update_role" class="space-y-4 flex flex-col gap-3" autocomplete="off">
                <x-my.flt_lbl name="name_fa" label="{{__('نام نقش فارسی:')}}" maxlength="40"
                              class="tracking-wider font-semibold" autofocus required/>
                <x-my.flt_lbl name="name_en" label="{{__('نام نقش لاتین:')}}" dir="ltr" maxlength="40"
                              class="tracking-wider font-semibold" required/>

                <div class="flex">
                    <flux:spacer/>
                    <flux:button type="submit" variant="primary" color="yellow" size="sm"
                                 class="cursor-pointer">{{__('ویرایش')}}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
