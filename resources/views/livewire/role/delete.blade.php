<?php

use App\Models\Role;
use Flux\Flux;
use Livewire\Volt\Component;

new class extends Component {

    public Role $role;

    public function delete_role(): void
    {
        $this->role->delete();
        $this->modal('delete-role-' . $this->role->id)->close();
        $this->dispatch('role-deleted');

        Flux::toast(
            heading: 'حذف شد.',
            text: 'نقش کاربری با موفقیت حذف شد.',
            variant: 'danger',
            position: 'top right'
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
                <flux:text class="mt-2">{{__('با تایید اطلاعات مربوطه حذف خواهند شد.')}}</flux:text>
            </div>

            <flux:button wire:click="delete_role" variant="primary" color="red" size="sm"
                         class="cursor-pointer">
                {{__('تایید حذف')}}
            </flux:button>
        </div>
    </flux:modal>
</div>
