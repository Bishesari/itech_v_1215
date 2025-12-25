<?php

use App\Models\Role;
use Flux\Flux;
use Livewire\Volt\Component;

new class extends Component {

    public Role $role;

    public string $name_fa = '';
    public string $name_en = '';


    public function mount(): void
    {
        $this->name_fa = $this->role->name_fa;
        $this->name_en = $this->role->name_en;
    }

    public function delete_role(): void
    {
        $this->role->delete();
        $this->modal('delete-role-' . $this->role->id)->close();
        $this->dispatch('role-deleted');

        Flux::toast(
            heading: 'حذف شد.',
            text: 'نقش کاربری با موفقیت حذف شد.',
            variant: 'danger'
        );
    }

}; ?>

<div class="text-center">
    <flux:tooltip content="حذف نقش" position="bottom">
        <flux:icon.trash variant="micro" class="cursor-pointer size-5 text-red-500 dark:text-red-400"
                         x-on:click="$flux.modal('delete-role-{{ $role->id }}').show()"/>
    </flux:tooltip>

    <flux:modal name="delete-role-{{ $role->id }}" :show="$errors->isNotEmpty()" focusable class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{__('حذف نقش کاربری ')}} <span
                        class="font-bold text-red-500 dark:text-red-400">{{ $role->name_fa }}</span></flux:heading>
                <flux:text class="mt-2">{{__('با تایید حذف اطلاعات مربوطه حذف خواهند شد.')}}</flux:text>
            </div>

            <form wire:submit.prevent="delete_role" class="space-y-4 flex flex-col gap-3" autocomplete="off">
                <div class="flex">
                    <flux:spacer/>
                    <flux:button type="submit" variant="primary" color="red" size="sm"
                                 class="cursor-pointer">{{__('تایید حذف')}}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
