<?php

use App\Jobs\SmsPass;
use App\Models\BranchRoleUser;
use App\Models\Contact;
use App\Models\Profile;
use App\Models\User;
use App\Rules\NCode;
use Livewire\Attributes\Computed;
use Livewire\Volt\Component;
use App\Models\Role;
use App\Models\Branch;


new class extends Component {

    public string $f_name_fa = '';
    public string $l_name_fa = '';
    public string $n_code = '';
    public string $contact_value = '';
    public string $user_name = '';
    public bool $user_exists = false;

    public int|string $role_id = '';
    public int|string $branch_id = '';

    public ?Profile $editing_profile = null;


    public function rules(): array
    {
        return [
            'f_name_fa' => ['required', 'min:2', 'max:30'],
            'l_name_fa' => ['required', 'min:2', 'max:30'],
            'n_code' => ['required', 'digits:10', new NCode],
            'contact_value' => ['required', 'starts_with:09', 'digits:11'],
            'role_id' => ['required', 'exists:roles,id'],
        ];
    }

    public function check_user_exist(): void
    {
        $this->validateOnly('n_code');
        $profile = Profile::where('n_code', $this->n_code)
            ->first();
        if ($profile) {
            $this->user_exists = true;
            $this->editing_profile = $profile;

            if (is_null($profile->f_name_fa))
            {$this->f_name_fa = '';}
            else{$this->f_name_fa = $profile->f_name_fa;}

            if (is_null($profile->l_name_fa))
            {$this->l_name_fa = '';}
            else{$this->l_name_fa = $profile->l_name_fa;}

            $this->user_name = $profile->user->user_name;
        } else {
            $this->user_exists = false;
        }
        $this->modal('check-user-exist')->close();
        $this->modal('sync-user')->show();
    }

    #[Computed]
    public function roles()
    {
        return Role::where('name_en', '!=', 'SuperAdmin')->orderBy('name_fa')->get();
    }

    #[Computed]
    public function branches()
    {
        if (!$this->role_id) {
            return collect();
        } else {
            $role = Role::find($this->role_id);
            if ($role->is_global) {
                return collect();
            } else {
                return Branch::orderBy('short_name')->get();
            }
        }
    }

    public function updatedRoleId(): void
    {
        $this->reset('branch_id');
    }


    public function sync_user()
    {
        $this->validate();
        $role = Role::find($this->role_id);

        if ($this->branch_id == '' and !$role->is_global) {
            $this->addError('branch_id', 'اجباری است!');
            return;
        }
        // --------------------- User Exist --------------------------- //
        if ($this->user_exists) {
            $this->editing_profile->update([
                'f_name_fa' => $this->f_name_fa,
                'l_name_fa' => $this->l_name_fa,
            ]);
            // contact - search by mobile only
            $contact = Contact::firstOrCreate(
                ['contact_value' => $this->contact_value],
            );
            $this->editing_profile->user->contacts()->syncWithoutDetaching([$contact->id]);

            if ($role->is_global) {
                $b_id = null;
            } else {
                $b_id = $this->branch_id;
            }
            $bru = BranchRoleUser::where('branch_id', $b_id)->where('role_id', $this->role_id)->where('user_id', $this->editing_profile->user->id)->first();
            if (!$bru)
            {
                BranchRoleUser::create([
                    'branch_id' => $b_id,
                    'role_id' => $this->role_id,
                    'user_id' => $this->editing_profile->user->id,
                    'assigned_by' => auth()->id(),
                ]);
            }
            $this->dispatch('user-created', id: $this->editing_profile->user->id);

        }
        else {

            $tempPass = $this->n_code;
            // Create User
            $user = User::create([
                'user_name' => $this->n_code,
                'password' => $tempPass,
            ]);

            // Create Profile
            $user->profile()->create([
                'identifier_type' => 'national_id',
                'n_code' => $this->n_code,
                'f_name_fa' => $this->f_name_fa,
                'l_name_fa' => $this->l_name_fa,
            ]);

            // contact - search by mobile only
            $contact = Contact::firstOrCreate(
                ['contact_value' => $this->contact_value],
            );

            $user->contacts()->syncWithoutDetaching([$contact->id]);

            if ($role->is_global) {
                $b_id = null;
            } else {
                $b_id = $this->branch_id;
            }
            BranchRoleUser::create([
                'branch_id' => $b_id,
                'role_id' => $this->role_id,
                'user_id' => $user->id,
                'assigned_by' => auth()->id(),
            ]);
            // send the temporary password via SmsPass job
            SmsPass::dispatch($this->contact_value, $this->n_code, $tempPass);
            $this->dispatch('user-created', id: $user->id);
        }

        $this->modal('sync-user')->close();
    }

    public function reset_prop(): void
    {
        $this->reset();
        $this->resetErrorBag();
    }

}; ?>

<div>
    <flux:tooltip content="کاربر جدید" position="left">
        <flux:icon.plus-circle variant="micro" class="cursor-pointer size-5 text-blue-500 mr-4"
                               x-on:click="$flux.modal('check-user-exist').show()"/>
    </flux:tooltip>

    <flux:modal name="check-user-exist" :show="$errors->isNotEmpty()" focusable class="md:w-96"
                :dismissible="false">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{__('درج کاربر جدید')}}</flux:heading>
                <flux:text class="mt-2">{{__('کد ملی را وارد کنید.')}}</flux:text>
            </div>

            <form wire:submit.prevent="check_user_exist" class="space-y-4 flex flex-col gap-3" autocomplete="off">
                <x-my.flt_lbl name="n_code" label="{{__('کدملی:')}}" maxlength="10" dir="ltr"
                              class="tracking-wider font-semibold" autofocus required/>
                <div class="flex">
                    <flux:spacer/>
                    <flux:button type="submit" variant="primary" color="teal" size="sm"
                                 class="cursor-pointer">{{__('ثبت')}}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>


    {{------------------------- Sync User Role Contact --------------------------------}}
    <flux:modal name="sync-user" :show="$errors->isNotEmpty()" focusable class="md:w-96" @close="reset_prop"
                :dismissible="false">
        <div class="space-y-6">
            <div>
                @if($user_name)
                    <flux:heading size="lg">{{__('ویرایش - نام کاربری:')}} {{$user_name}}</flux:heading>
                @else
                    <flux:heading size="lg">{{__('درج کاربر جدید - کد ملی:')}} {{$n_code}}</flux:heading>
                @endif

                <flux:text class="mt-2">{{__('اطلاعات خواسته شده را وارد کنید.')}}</flux:text>
            </div>

            <form wire:submit.prevent="sync_user" class="space-y-4 flex flex-col gap-3" autocomplete="off">
                <x-my.flt_lbl name="f_name_fa" label="{{__('نام:')}}" maxlength="30"
                              class="tracking-wider font-semibold" autofocus required/>

                <x-my.flt_lbl name="l_name_fa" label="{{__('نام خانوادگی:')}}" maxlength="40"
                              class="tracking-wider font-semibold" required/>

                <x-my.flt_lbl name="contact_value" label="{{__('شماره موبایل:')}}" dir="ltr" maxlength="11"
                              class="tracking-wider font-semibold" required/>

                <flux:select variant="listbox" searchable placeholder="انتخاب نقش" wire:model.live="role_id" required>
                    @foreach ($this->roles as $role)
                        <flux:select.option value="{{ $role->id }}">
                            {{ $role->name_fa }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <div class="relative">
                    <flux:select variant="listbox" searchable placeholder="انتخاب شعبه" wire:model.live="branch_id"
                                 :disabled="!$role_id" required>
                        @forelse ($this->branches as $branch)
                            <flux:select.option value="{{ $branch->id }}">
                                {{ $branch->short_name }}
                            </flux:select.option>
                        @empty
                            <flux:select.option disabled>{{__('بدون نیاز به شعبه')}}</flux:select.option>
                        @endforelse

                    </flux:select>

                    <div wire:loading wire:target="role_id" class="absolute left-8 top-3">
                        <flux:icon.loading variant="micro" class="text-blue-500 dark:text-blue-300"/>
                    </div>
                </div>


                <div class="flex">
                    <flux:spacer/>
                    <flux:button type="submit" variant="primary" color="teal" size="sm"
                                 class="cursor-pointer">{{__('ثبت')}}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>


</div>
