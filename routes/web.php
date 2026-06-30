<?php

use App\Http\Controllers\AntenatalController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TreatmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rules\Password;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/password/required', function () {
        return view('auth.password-required');
    })->name('password.required');

    Route::post('/password/required', function (Request $request) {
        $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) use ($request) {
                if (! Hash::check($value, $request->user()->password)) {
                    $fail('The current password is incorrect.');
                }
            }],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);
        $request->user()->markPasswordAsChanged();

        return redirect()->intended(route('dashboard'));
    })->name('password.required.post');

    Route::middleware('password.reset')->group(function () {
        Route::get('/dashboard', App\Http\Controllers\DashboardController::class)
            ->middleware('verified')->name('dashboard');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::resource('patients', PatientController::class)->middleware([
            'index' => 'permission:patients.view',
            'show' => 'permission:patients.view',
            'create' => 'permission:patients.create',
            'store' => 'permission:patients.create',
            'edit' => 'permission:patients.edit',
            'update' => 'permission:patients.edit',
            'destroy' => 'permission:patients.delete',
        ]);

        Route::get('family-files', function () {
            return view('family-files.index');
        })->middleware('permission:patients.view')->name('family-files.index');

        Route::resource('treatments', TreatmentController::class)->middleware([
            'index' => 'permission:treatments.view',
            'show' => 'permission:treatments.view',
            'create' => 'permission:treatments.create',
            'store' => 'permission:treatments.create',
            'edit' => 'permission:treatments.edit',
            'update' => 'permission:treatments.edit',
            'destroy' => 'permission:treatments.delete',
        ]);
        Route::get('treatments/{treatment}/compliance', [TreatmentController::class, 'compliance'])
            ->middleware('permission:treatments.compliance')->name('treatments.compliance');

        Route::resource('antenatal', AntenatalController::class)->middleware([
            'index' => 'permission:antenatal.view',
            'show' => 'permission:antenatal.view',
            'create' => 'permission:antenatal.create',
            'store' => 'permission:antenatal.create',
            'edit' => 'permission:antenatal.edit',
            'update' => 'permission:antenatal.edit',
            'destroy' => 'permission:antenatal.delete',
        ]);
        Route::get('antenatal/{antenatal}/partograph', [AntenatalController::class, 'partograph'])
            ->middleware('permission:antenatal.partograph')->name('antenatal.partograph');
        Route::post('antenatal/{antenatal}/partograph', [AntenatalController::class, 'storePartograph'])
            ->middleware('permission:antenatal.partograph')->name('antenatal.partograph.store');

        Route::get('finance/invoices', [FinanceController::class, 'invoices'])
            ->middleware('permission:finance.invoices.view')->name('finance.invoices');
        Route::get('finance/invoices/create', [FinanceController::class, 'createInvoice'])
            ->middleware('permission:finance.invoices.create')->name('finance.create-invoice');
        Route::post('finance/invoices', [FinanceController::class, 'storeInvoice'])
            ->middleware('permission:finance.invoices.create')->name('finance.store-invoice');
        Route::get('finance/invoices/{invoice}', [FinanceController::class, 'showInvoice'])
            ->middleware('permission:finance.invoices.view')->name('finance.show-invoice');
        Route::get('finance/payments', [FinanceController::class, 'payments'])
            ->middleware('permission:finance.payments.view')->name('finance.payments');
        Route::post('finance/invoices/{invoice}/payments', [FinanceController::class, 'storePayment'])
            ->middleware('permission:finance.payments.create')->name('finance.store-payment');

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('daily', [App\Http\Controllers\ReportController::class, 'daily'])
                ->middleware('permission:reports.daily')->name('daily');
            Route::get('treatment', [App\Http\Controllers\ReportController::class, 'treatment'])
                ->middleware('permission:reports.treatment')->name('treatment');
            Route::get('compliance', [App\Http\Controllers\ReportController::class, 'compliance'])
                ->middleware('permission:reports.compliance')->name('compliance');
            Route::get('financial', [App\Http\Controllers\ReportController::class, 'financial'])
                ->middleware('permission:reports.financial')->name('financial');
        });

        Route::resource('users', App\Http\Controllers\UserController::class)
            ->middleware('role:super_admin');
    });
});

require __DIR__.'/auth.php';
