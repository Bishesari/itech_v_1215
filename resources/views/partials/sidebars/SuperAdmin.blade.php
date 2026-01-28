<flux:navlist.item icon="user-group" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate x-data="{ loading: false }"
                   @click="loading = true">
    <span>{{ __('داشبرد') }}</span>
    <flux:badge color="{{session('color')}}" size="sm" class="mr-2">{{__('سوپر ادمین')}}</flux:badge>
    <flux:icon.loading x-show="loading" class="inline absolute left-2 top-2 size-3.5 text-stone-500"/>
</flux:navlist.item>

<flux:navlist.group :heading="__('اطلاعات پایه')" class="grid" expandable :expanded="request()->routeIs(['role.index', 'province.index', 'branch.index', 'field.index', 'standard.index', 'user.index'])" >

    <flux:navlist.item icon="user-group" :href="route('role.index')" :current="request()->routeIs('role.index')" wire:navigate x-data="{ loading: false }"
                       @click="loading = true">
        <span>{{ __('نقشهای کاربری') }}</span>
        <flux:icon.loading x-show="loading" class="inline absolute left-2 size-3.5 text-stone-500"/>
    </flux:navlist.item>

    <flux:navlist.item icon="user-group" :href="route('province.index')" :current="request()->routeIs('province.index')" wire:navigate x-data="{ loading: false }"
                       @click="loading = true">
        <span>{{ __('استانها') }}</span>
        <flux:icon.loading x-show="loading" class="inline absolute left-2 size-3.5 text-stone-500"/>
    </flux:navlist.item>


    <flux:navlist.item icon="user-group" :href="route('branch.index', ['highlight_id'=>0])" :current="request()->routeIs('branch.index')" wire:navigate x-data="{ loading: false }"
                       @click="loading = true">
        <span>{{ __('لیست شعب') }}</span>
        <flux:icon.loading x-show="loading" class="inline absolute left-2 size-3.5 text-stone-500"/>
    </flux:navlist.item>

    <flux:navlist.item icon="user-group" :href="route('field.index')" :current="request()->routeIs('field.index')" wire:navigate x-data="{ loading: false }"
                       @click="loading = true">
        <span>{{ __('رشته های آموزشی') }}</span>
        <flux:icon.loading x-show="loading" class="inline absolute left-2 size-3.5 text-stone-500"/>
    </flux:navlist.item>

    <flux:navlist.item icon="user-group" :href="route('standard.index', ['highlight_id'=>0])" :current="request()->routeIs('standard.index')" wire:navigate x-data="{ loading: false }"
                       @click="loading = true">
        <span>{{ __('استانداردها') }}</span>
        <flux:icon.loading x-show="loading" class="inline absolute left-2 size-3.5 text-stone-500"/>
    </flux:navlist.item>

    <flux:navlist.item icon="user-group" :href="route('user.index')" :current="request()->routeIs('user.index')" wire:navigate x-data="{ loading: false }"
                       @click="loading = true">
        <span>{{ __('کاربران آموزشگاه') }}</span>
        <flux:icon.loading x-show="loading" class="inline absolute left-2 size-3.5 text-stone-500"/>
    </flux:navlist.item>

</flux:navlist.group>

<flux:navlist.group :heading="__('بانک سوال')" class="grid" expandable :expanded="request()->routeIs(['question.index', 'question.create'])" >
    <flux:navlist.item icon="user-group" href="{{route('question.index', ['sid'=>0, 'cid'=>0] )}}" :current="request()->routeIs('question.index')" wire:navigate x-data="{ loading: false }"
                       @click="loading = true">
        <span>{{ __('کل سوالات') }}</span>
        <flux:icon.loading x-show="loading" class="inline absolute left-2 size-3.5 text-stone-500"/>
    </flux:navlist.item>
    <flux:navlist.item icon="user-group" href="{{route('question.create', ['sid'=>0, 'cid'=>0] )}}" :current="request()->routeIs('question.create')" wire:navigate x-data="{ loading: false }"
                       @click="loading = true">
        <span>{{ __('درج سوال جدید') }}</span>
        <flux:icon.loading x-show="loading" class="inline absolute left-2 size-3.5 text-stone-500"/>
    </flux:navlist.item>
</flux:navlist.group>

<flux:navlist.group :heading="__('آزمونهای کتبی')" class="grid" expandable :expanded="request()->routeIs(['exam.index', 'active.quiz', 'active.midterm'])" >
    <flux:navlist.item icon="user-group" href="{{route('exam.index')}}" :current="request()->routeIs('exam.index')" wire:navigate x-data="{ loading: false }"
                       @click="loading = true">
        <span>{{ __('لیست آزمونها') }}</span>
        <flux:icon.loading x-show="loading" class="inline absolute left-2 size-3.5 text-stone-500"/>
    </flux:navlist.item>

    <flux:navlist.item icon="user-group" href="{{route('active.quiz')}}" :current="request()->routeIs('active.quiz')" wire:navigate x-data="{ loading: false }"
                       @click="loading = true">
        <span>{{ __('کوئیزهای کلاسی') }}</span>
        <flux:icon.loading x-show="loading" class="inline absolute left-2 size-3.5 text-stone-500"/>
    </flux:navlist.item>
    <flux:navlist.item icon="user-group" href="{{route('active.midterm')}}" :current="request()->routeIs('active.midterm')" wire:navigate x-data="{ loading: false }"
                       @click="loading = true">
        <span>{{ __('میانترم') }}</span>
        <flux:icon.loading x-show="loading" class="inline absolute left-2 size-3.5 text-stone-500"/>
    </flux:navlist.item>

</flux:navlist.group>


<flux:navlist.group :heading="__('فروشگاه')" class="grid" expandable :expanded="request()->routeIs(['category.index', 'product.index'])" >

    <flux:navlist.item icon="user-group" href="{{route('category.index')}}" :current="request()->routeIs('category.index')" wire:navigate x-data="{ loading: false }"
                       @click="loading = true">
        <span>{{ __('گروهبندی محصولات') }}</span>
        <flux:icon.loading x-show="loading" class="inline absolute left-2 size-3.5 text-stone-500"/>
    </flux:navlist.item>

    <flux:navlist.item icon="user-group" href="{{route('product.index')}}" :current="request()->routeIs('product.index')" wire:navigate x-data="{ loading: false }"
                       @click="loading = true">
        <span>{{ __('لیست محصولات') }}</span>
        <flux:icon.loading x-show="loading" class="inline absolute left-2 size-3.5 text-stone-500"/>
    </flux:navlist.item>


    <flux:navlist.item icon="user-group" href="{{route('written_question.index')}}" :current="request()->routeIs('written_question.index')" wire:navigate x-data="{ loading: false }"
                       @click="loading = true">
        <span>{{ __('نمونه سوالات پرتکرار') }}</span>
        <flux:icon.loading x-show="loading" class="inline absolute left-2 size-3.5 text-stone-500"/>
    </flux:navlist.item>

</flux:navlist.group>
