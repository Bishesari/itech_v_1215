<?php


use App\Http\Controllers\PaymentController;
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
    Volt::route('select_role', 'auth.select-role')->name('role.select');
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

Volt::route('exams', 'exam.index')->name('exam.index')->middleware(['auth']);

Volt::route('exam/quiz/active', 'exam.quiz.active')->name('active.quiz')->middleware(['auth']);
Volt::route('exam/quiz/create', 'exam.quiz.create')->name('quiz.create')->middleware(['auth']);
Volt::route('exam/{exam}/quiz/start', 'exam.quiz.start')->name('quiz.start')->middleware(['auth']);


Volt::route('exam/midterm/active', 'exam.midterm.active')->name('active.midterm')->middleware(['auth']);
Volt::route('exam/midterm/create', 'exam.midterm.create')->name('midterm.create')->middleware(['auth']);
Volt::route('exam/{exam}/midterm/start', 'exam.midterm.start')->name('midterm.start')->middleware(['auth']);



Volt::route('exam/final/create', 'exam.final.create')->name('final.create')->middleware(['auth']);
Volt::route('exam/{exam}/participants', 'exam.participant.index')->name('participant.index')->middleware(['auth']);


Volt::route('final/exams', 'exam.final.index')->name('final.exams');



Volt::route('exam_user/{examUser}/take', 'exam.take')->name('exam.take')->middleware(['auth']);
Volt::route('exam_user/{examUser}/result', 'exam.result')->name('exam.result')->middleware(['auth']);


Volt::route('product/written_questions', 'product.written-question.index')->name('written_question.index')->middleware(['auth']);
Volt::route('product/{product}/written_question', 'product.written-question.show')->name('written_question.show')->middleware(['auth']);
Volt::route('product/written_question/{writtenQuestion}/price_list', 'product.written-question.price-list')
    ->name('written_questions.price_list')->middleware(['auth']);



Volt::route('product/services/', 'product.written-question.index')->name('service.index')->middleware(['auth']);



// My Product buy pages
Volt::route('product/categories', 'product.category.index')->name('category.index')->middleware(['auth']);
Volt::route('products', 'product.index')->name('product.index');
Route::get('payment/start/{order}', [PaymentController::class, 'start'])->name('payment.start')->middleware(['auth']);
Route::post('payment/callback', [PaymentController::class, 'callback'])->withoutMiddleware(['web'])->name('payment.callback');

Volt::route('products/category/{category:slug}', 'product.category.show')->name('products.by-category');
Volt::route('product/{product}', 'product.show')->name('product.show');
