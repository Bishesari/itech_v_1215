<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
Volt::route('/', 'welcome')->name('home');
//Route::get('/', function () {    return view('welcome');})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
    Volt::route('select_role', 'auth.select-role')->name('select_role');
});

Volt::route('forgotten-password', 'auth.my-forgot-password')->name('forgotten.password');


Volt::route('provinces', 'province.index')->name('province.index')->middleware(['auth']);
Volt::route('province/{province}/cities', 'province.city.index')->name('city.index')->middleware(['auth']);

Volt::route('roles', 'role.index')->name('role.index')->middleware(['auth']);

Volt::route('branches/{highlight_id}', 'branch.index')->name('branch.index')->middleware(['auth']);
Volt::route('branch/create', 'branch.create')->name('branch.create')->middleware(['auth']);
Volt::route('branch/{branch}/edit', 'branch.edit')->name('branch.edit')->middleware(['auth']);

Volt::route('fields', 'field.index')->name('field.index')->middleware(['auth']);
Volt::route('standards/{highlight_id}', 'standard.index')->name('standard.index')->middleware(['auth']);
Volt::route('standard/create', 'standard.create')->name('standard.create')->middleware(['auth']);
Volt::route('standard/{standard}/edit', 'standard.edit')->name('standard.edit')->middleware(['auth']);
Volt::route('standard/{standard}/chapters', 'standard.chapter.index')->name('chapter.index')->middleware(['auth']);

Volt::route('questions/{sid}/{cid}/index', 'question.index')->name('question.index')->middleware(['auth']);
Volt::route('question/{sid}/{cid}/create', 'question.create')->name('question.create')->middleware(['auth']);
Volt::route('question/{question}/edit', 'question.edit')->name('question.edit')->middleware(['auth']);

Volt::route('users', 'user.index')->name('user.index')->middleware(['auth']);
