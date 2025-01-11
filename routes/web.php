<?php

use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\Service\ServiceController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\Warehouse\GoodsController;
use App\Http\Controllers\Admin\Warehouse\SupplierController;
use App\Http\Controllers\Admin\Warehouse\WarehouseController;
use App\Http\Controllers\Finance\ClientsController as FClientsController;
use App\Http\Controllers\Finance\DebtsController;
use App\Http\Controllers\Service\OffersController;
use App\Http\Controllers\Warehouse\ExpenseController;
use App\Http\Controllers\Warehouse\IncomeController;
use App\Http\Controllers\Warehouse\MainController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'worker'])->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::group(['prefix' => 'sklad', 'as' => 'sklad.'], function () {
        Route::get('/', [MainController::class, 'index'])->name('index');
        Route::get('/balance-export', [MainController::class, 'export'])->name('export');
        Route::get('good/{good}', [MainController::class, 'good'])->name('good');

        Route::get('incomes', [IncomeController::class, 'index'])->name('incomes.index');
        Route::get('income/export/{good?}', [IncomeController::class, 'export'])->name('incomes.export');
        Route::post('income', [IncomeController::class, 'income'])->name('incomes.income');
        Route::get('income-edit/{balance}', [IncomeController::class, 'edit'])->name('incomes.edit');
        Route::post('income-update/{balance}', [IncomeController::class, 'update'])->name('incomes.update');
        Route::delete('income-delete/{balance}', [IncomeController::class, 'delete'])->name('incomes.delete');

        Route::get('expenses', [ExpenseController::class, 'index'])->name('expenses.index');
        Route::get('expense/export/{good?}', [ExpenseController::class, 'export'])->name('expenses.export');
        Route::post('expense', [ExpenseController::class, 'expense'])->name('expenses.expense');
        Route::get('expense-edit/{balance}', [ExpenseController::class, 'edit'])->name('expenses.edit');
        Route::post('expense-update/{balance}', [ExpenseController::class, 'update'])->name('expenses.update');
        Route::delete('expense-delete/{balance}', [ExpenseController::class, 'delete'])->name('expenses.delete');
    });

    Route::group(['controller' => OffersController::class, 'prefix' => 'offers', 'as' => 'offers.'], function () {
        Route::get('/', 'index')->name('index');
        Route::get('/form', 'form')->name('form');
    });

    Route::group(['prefix' => 'finance', 'as' => 'finance.'], function () {
        Route::get('/debts', [DebtsController::class, 'index'])->name('debts.index');
        Route::post('/debts/create', [DebtsController::class, 'create'])->name('debts.create');
        Route::get('/clients', [FClientsController::class, 'index'])->name('clients.index');
        Route::post('/clients/save/{client?}', [FClientsController::class, 'save'])->name('clients.save');
    });

    Route::middleware(['auth', 'admin'])->prefix('admin')->as('admin.')->group(function () {
        Route::get('users', [UsersController::class, 'index'])->name('users.index');
        Route::post('users/{user?}', [UsersController::class, 'save'])->name('users.save');

        Route::get('warehouses', [WarehouseController::class, 'index'])->name('warehouses.index');
        Route::post('warehouse/{warehouse?}', [WarehouseController::class, 'save'])->name('warehouses.save');

        Route::controller(GoodsController::class)->prefix('goods')->as('goods.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('form/{good?}', 'form')->name('form');
            Route::post('save/{good?}', 'save')->name('save');
        });
        Route::get('categories', [GoodsController::class, 'categories'])->name('categories.index');
        Route::post('categories/save/{category?}', [GoodsController::class, 'saveCategory'])->name('categories.save');

        Route::controller(SupplierController::class)->prefix('suppliers')->as('suppliers.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('save/{supplier?}', 'save')->name('save');
        });

        Route::controller(ServiceController::class)->prefix('services')->as('services.')->group(function () {
            Route::get('/categories', 'categories')->name('categories.index');
            Route::post('/categories/save/{serviceCategory?}', 'categorySave')->name('categories.save');

            Route::get('/', 'services')->name('index');
            Route::get('/form/{service?}', 'serviceForm')->name('form');
            Route::post('/save/{service?}', 'serviceSave')->name('save');
        });
    });
});

Auth::routes([
    //'login' => true,
    //'reset' => true,
    'register' => false,
    'verify' => false,
]);
