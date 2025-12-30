<?php

use App\Models\City;
use Flux\Flux;
use Livewire\Volt\Component;

new class extends Component {

    public City $city;

    public string $name_fa = '';


    public function mount(): void
    {
        $this->name_fa = $this->city->name_fa;
    }

    public function delete_city(): void
    {
        $this->city->delete();
        $this->modal('delete-city-' . $this->city->id)->close();
        $this->dispatch('city-deleted');

        Flux::toast(
            heading: 'حذف شد.',
            text: 'شهر با موفقیت حذف شد.',
            variant: 'danger',
            position: 'top right'
        );
    }

}; ?>

<div class="text-center">
    <flux:tooltip content="حذف شهر" position="bottom">
        <flux:icon.trash variant="micro" class="cursor-pointer size-5 text-red-500 dark:text-red-400"
                         x-on:click="$flux.modal('delete-city-{{ $city->id }}').show()"/>
    </flux:tooltip>

    <flux:modal name="delete-city-{{ $city->id }}" :show="$errors->isNotEmpty()" focusable class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{__('حذف شهر ')}} <span
                        class="font-bold text-red-500 dark:text-red-400">{{ $city->name_fa }}</span></flux:heading>
                <flux:text class="mt-2">{{__('با تایید حذف اطلاعات مربوطه حذف خواهند شد.')}}</flux:text>
            </div>

            <form wire:submit.prevent="delete_city" class="space-y-4 flex flex-col gap-3" autocomplete="off">
                <div class="flex">
                    <flux:spacer/>
                    <flux:button type="submit" variant="primary" color="red" size="sm"
                                 class="cursor-pointer">{{__('تایید حذف')}}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
