<?php

use App\Models\Exam;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Volt\Component;

new class extends Component {
    public Exam $exam;


}; ?>

<div>
    <flux:heading size="lg" level="1">
        {{__('آزمونهای کتبی')}}
    </flux:heading>

    <div class="inline-flex mt-2 mb-4">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{route('exam.index')}}" wire:navigate x-data="{ loading: false }"
                                   @click="loading = true">
                <span x-show="!loading" x-cloak class="text-blue-500">{{__('آزمونها')}}</span>
                <flux:icon.loading x-show="loading" x-cloak class="size-5 animate-spin text-blue-500 mr-3"/>
            </flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{$exam->standard->name_fa}}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>
    <flux:separator variant="subtle"/>

    <flux:table class="inline">
        <flux:table.columns>

            <flux:table.column align="center">
                {{__('شناسه')}}
            </flux:table.column>

            <flux:table.column align="center">
                {{__('نام کاربری')}}
            </flux:table.column>

            <flux:table.column align="center">
                {{__('نام و نام خانوادگی')}}
            </flux:table.column>

            <flux:table.column align="center">
                {{__('امتیاز')}}
            </flux:table.column>

        </flux:table.columns>

        <flux:table.rows>
            @foreach ($exam->users as $user)
                <flux:table.row class="dark:hover:bg-stone-900/80 transition duration-300 hover:bg-zinc-100"
                                :key="$user->id">
                    <flux:table.cell align="center">{{ $user->id }}</flux:table.cell>
                    <flux:table.cell align="center">{{ $user->user_name }}</flux:table.cell>
                    <flux:table.cell align="center">
                        {{ $user->profile->f_name_fa . ' ' .$user->profile->l_name_fa }}
                    </flux:table.cell>
                    <flux:table.cell align="center">
                        @if($user->pivot->score < 50)
                            @php($color = 'red')
                        @elseif($user->pivot->score >= 50 and $user->pivot->score <= 90)
                            @php($color = 'green')
                        @elseif($user->pivot->score > 90)
                            @php($color = 'lime')
                        @endif
                        <flux:badge color="{{$color}}" size="sm">
                            {{ $user->pivot->score }}
                        </flux:badge>


                    </flux:table.cell>


                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

</div>
