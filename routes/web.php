<?php

use App\Http\Controllers\Accounting\IncomeController;
use App\Http\Controllers\Accounting\InvoiceController;
use App\Http\Controllers\Accounting\LoanController;
use App\Http\Controllers\Accounting\PurchasingController;
use App\Http\Controllers\Accounting\ReceiptController;
use App\Http\Controllers\Accounting\ReportController;
use App\Http\Controllers\Accounting\TaxReportController;
use App\Http\Controllers\App\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CRM\AdsPlanController;
use App\Http\Controllers\CRM\DealController;
use App\Http\Controllers\CRM\PipelineStageController;
use App\Http\Controllers\CRM\QuotationController;
use App\Http\Controllers\CRM\UploadController;
use App\Http\Controllers\HRM\DivisionController;
use App\Http\Controllers\HRM\EmployeeController;
use App\Http\Controllers\HRM\PayrollController;
use App\Http\Controllers\Marketing\MarketingController;
use App\Http\Controllers\ProjectManagement\ProjectController;
use App\Http\Controllers\RBAC\PermissionController;
use App\Http\Controllers\RBAC\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);

    Route::get('register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth'])
    ->prefix('crm')
    ->group(function () {
        Route::get('/contacts', [\App\Http\Controllers\CRM\ContactController::class, 'index'])->name('contacts.index');
        Route::get('/contacts/create', [\App\Http\Controllers\CRM\ContactController::class, 'create'])->name('contacts.create');
        Route::post('/contacts/store', [\App\Http\Controllers\CRM\ContactController::class, 'store'])->name('contacts.store');
        Route::get('/contacts/{contact}/edit', [\App\Http\Controllers\CRM\ContactController::class, 'edit'])->name('contacts.edit');
        Route::put('/contacts/{contact}', [\App\Http\Controllers\CRM\ContactController::class, 'update'])->name('contacts.update');
        Route::delete('/contacts/{contact}', [\App\Http\Controllers\CRM\ContactController::class, 'destroy'])->name('contacts.destroy');

        Route::get('/pipeline', [PipelineStageController::class, 'index'])->name('pipeline.index');
        Route::get('/pipeline/create', [PipelineStageController::class, 'create'])->name('pipeline.create');
        Route::post('/pipeline/store', [PipelineStageController::class, 'store'])->name('pipeline.store');
        Route::get('/pipeline/{stages}/edit', [PipelineStageController::class, 'edit'])->name('pipeline.edit');
        Route::put('/pipeline/{stages}', [PipelineStageController::class, 'update'])->name('pipeline.update');
        Route::delete('/pipeline/{stages}', [PipelineStageController::class, 'destroy'])->name('pipeline.destroy');

        Route::get('/deal', [DealController::class, 'index'])->name('deal.index');
        Route::get('/deal/create', [DealController::class, 'create'])->name('deal.create');
        Route::post('/deal/store', [DealController::class, 'store'])->name('deal.store');
        Route::get('/deal/{deal}/edit', [DealController::class, 'edit'])->name('deal.edit');
        Route::put('/deal/{deal}', [DealController::class, 'update'])->name('deal.update');
        Route::delete('/deal/{deal}', [DealController::class, 'destroy'])->name('deal.destroy');
        Route::get('/deal/kanban', [DealController::class, 'kanban'])->name('deal.kanban');
        Route::post('/deal/{deal}/move', [DealController::class, 'move'])->name('deal.move');

        Route::get('upload', [UploadController::class, 'index'])->name('crm.upload.index');
        Route::post('upload', [UploadController::class, 'store'])->name('crm.upload.store');

        Route::get('quotations', [QuotationController::class, 'index'])->name('crm.quotations.index');
        Route::get('quotations/create', [QuotationController::class, 'create'])->name('crm.quotations.create');
        Route::post('quotations', [QuotationController::class, 'store'])->name('crm.quotations.store');
        Route::get('quotations/{quotation}/edit', [QuotationController::class, 'edit'])->name('crm.quotations.edit');
        Route::put('quotations/{quotation}', [QuotationController::class, 'update'])->name('crm.quotations.update');
        Route::delete('quotations/{quotation}', [QuotationController::class, 'destroy'])->name('crm.quotations.destroy');
        Route::get('quotations/{quotation}/pdf', [QuotationController::class, 'exportPdf'])->name('crm.quotations.exportPdf');

        Route::get('ads-plans', [AdsPlanController::class, 'index'])->name('crm.ads_plans.index');
        Route::get('ads-plans/create', [AdsPlanController::class, 'create'])->name('crm.ads_plans.create');
        Route::post('ads-plans', [AdsPlanController::class, 'store'])->name('crm.ads_plans.store');
        Route::get('ads-plans/{adsPlan}/edit', [AdsPlanController::class, 'edit'])->name('crm.ads_plans.edit');
        Route::put('ads-plans/{adsPlan}', [AdsPlanController::class, 'update'])->name('crm.ads_plans.update');
        Route::delete('ads-plans/{adsPlan}', [AdsPlanController::class, 'destroy'])->name('crm.ads_plans.destroy');

        Route::prefix('project-management')->group(function () {
            Route::get('projects', [ProjectController::class, 'index'])->name('projects.index');
            Route::get('projects/create', [ProjectController::class, 'create'])->name('projects.create');
            Route::post('projects/store', [ProjectController::class, 'store'])->name('projects.store');
            Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
            Route::get('projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
            Route::put('projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
            Route::delete('projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

            // Route::get('projects/{project}/progress', [ProjectProgressController::class, 'index'])->name('projects.progress.index');
            // Route::post('projects/{project}/progress', [ProjectProgressController::class, 'store'])->name('projects.progress.store');
            Route::post('/task/{task}/status', [ProjectController::class, 'updateTask'])->name('tasks.updateStatus');
            Route::get('/projects/{project}/calendar', [ProjectController::class, 'calendar'])->name('projects.calendar');
        });

        Route::prefix('hrm')->group(function () {
            // === Division Routes ===
            Route::get('/divisions', [DivisionController::class, 'index'])->name('divisions.index');
            Route::get('/divisions/create', [DivisionController::class, 'create'])->name('divisions.create');
            Route::post('/divisions', [DivisionController::class, 'store'])->name('divisions.store');
            Route::get('/divisions/{division}/edit', [DivisionController::class, 'edit'])->name('divisions.edit');
            Route::put('/divisions/{division}', [DivisionController::class, 'update'])->name('divisions.update');
            Route::delete('/divisions/{division}', [DivisionController::class, 'destroy'])->name('divisions.destroy');

            // === Payroll Routes ===
            Route::get('/payrolls', [PayrollController::class, 'index'])->name('payrolls.index');
            Route::get('/payrolls/create', [PayrollController::class, 'create'])->name('payrolls.create');
            Route::post('/payrolls', [PayrollController::class, 'store'])->name('payrolls.store');
            Route::get('/payrolls/{payroll}/edit', [PayrollController::class, 'edit'])->name('payrolls.edit');
            Route::put('/payrolls/{payroll}', [PayrollController::class, 'update'])->name('payrolls.update');
            Route::delete('/payrolls/{payroll}', [PayrollController::class, 'destroy'])->name('payrolls.destroy');

            // === Employee Routes (opsional, jika sudah ada model dan controller-nya) ===
            Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
            Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
            Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
            Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
            Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
            Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
        });

        // Modul Accounting
        Route::prefix('accounting')
            ->name('accounting.')
            ->group(function () {
                Route::get('incomes', [IncomeController::class, 'index'])->name('incomes.index');
                Route::get('incomes/create', [IncomeController::class, 'create'])->name('incomes.create');
                Route::get('incomes/{income}/edit', [IncomeController::class, 'edit'])->name('incomes.edit');
                Route::put('incomes/{income}', [IncomeController::class, 'update'])->name('incomes.update');
                Route::delete('incomes/{income}', [IncomeController::class, 'destroy'])->name('incomes.destroy');
                Route::post('incomes', [IncomeController::class, 'store'])->name('incomes.store');

                Route::get('purchasings', [PurchasingController::class, 'index'])->name('purchasings.index');
                Route::get('purchasings/create', [PurchasingController::class, 'create'])->name('purchasings.create');
                Route::post('purchasings', [PurchasingController::class, 'store'])->name('purchasings.store');
                Route::get('purchasings/{purchasing}/edit', [PurchasingController::class, 'edit'])->name('purchasings.edit');
                Route::put('purchasings/{purchasing}', [PurchasingController::class, 'update'])->name('purchasings.update');
                Route::delete('purchasings/{purchasing}', [PurchasingController::class, 'destroy'])->name('purchasings.destroy');

                Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
                Route::get('reports/annual', [\App\Http\Controllers\Accounting\ReportController::class, 'annualReport'])->name('reports.annual');

                Route::get('loans/', [LoanController::class, 'index'])->name('loans.index');
                Route::get('loans/create', [LoanController::class, 'create'])->name('loans.create');
                Route::post('loans/store', [LoanController::class, 'store'])->name('loans.store');
                Route::get('loans/{loan}/edit', [LoanController::class, 'edit'])->name('loans.edit');
                Route::put('loans/{loan}', [LoanController::class, 'update'])->name('loans.update');
                Route::delete('loans/{loan}', [LoanController::class, 'destroy'])->name('loans.destroy');

                Route::get('/tax', [TaxReportController::class, 'index'])->name('tax.index');
                Route::get('/tax/export', [TaxReportController::class, 'exportPdf'])->name('tax.export');

                Route::get('invoices/', [InvoiceController::class, 'index'])->name('invoices.index');
                Route::get('invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
                Route::post('invoices/', [InvoiceController::class, 'store'])->name('invoices.store');
                Route::get('invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
                Route::get('invoices/{id}/pdf', [InvoiceController::class, 'exportPdf'])->name('invoices.pdf');
                Route::patch('/invoices/{invoice}/update-status', [\App\Http\Controllers\Accounting\InvoiceController::class, 'updateStatus'])->name('invoices.update-status');

                Route::get('receipts', [ReceiptController::class, 'index'])->name('receipts.index');
                Route::get('receipts/create', [ReceiptController::class, 'create'])->name('receipts.create');
                Route::post('receipts', [ReceiptController::class, 'store'])->name('receipts.store');

                Route::get('receipts/{receipt}', [ReceiptController::class, 'show'])->name('receipts.show');
                Route::get('receipts/{receipt}/pdf', [ReceiptController::class, 'pdf'])->name('receipts.pdf');
            });

        Route::prefix('marketing')->group(function () {
            Route::get('/kanban', [MarketingController::class, 'kanban'])->name('marketing.kanban');
            Route::post('/kanban', [MarketingController::class, 'storeKanbanBoard']);
            Route::post('/kanban/task', [MarketingController::class, 'storeKanbanTask'])->name('marketing.kanban.task.store');
            Route::get('/kanban/task/{task}/edit', [MarketingController::class, 'editKanbanTask'])->name('marketing.kanban.task.edit');
            Route::put('/kanban/task/{task}', [MarketingController::class, 'updateKanbanTask'])->name('marketing.kanban.task.update');
            Route::delete('/kanban/task/{task}', [MarketingController::class, 'destroyKanbanTask'])->name('marketing.kanban.task.destroy');
            Route::post('/kanban/update-task', [MarketingController::class, 'updateKanbanTask'])->name('marketing.kanban.update-task');

            Route::get('/mindmap', [MarketingController::class, 'mindmap'])->name('marketing.mindmap');
            Route::get('/mindmap/{id}/nodes', [MarketingController::class, 'getMindMapNodes'])->name('marketing.mindmap.nodes');
            Route::post('/mindmap', [MarketingController::class, 'storeMindMap'])->name('marketing.mindmap.store');
            Route::post('/mindmap/node', [MarketingController::class, 'storeMindNode'])->name('marketing.mindmap.node.store');
            Route::post('/mindmap/node/ajax', [MarketingController::class, 'storeMindNodeAjax'])->name('marketing.mindmap.node.ajax');
            Route::get('/mindmap/node/{node}/edit', [MarketingController::class, 'editMindNode'])->name('marketing.mindmap.node.edit');
            Route::put('/mindmap/node/{node}', [MarketingController::class, 'updateMindNode'])->name('marketing.mindmap.node.update');
            Route::delete('/mindmap/node/{node}', [MarketingController::class, 'destroyMindNode'])->name('marketing.mindmap.node.destroy');

            Route::get('/strategy', [MarketingController::class, 'strategy'])->name('marketing.strategy');
            Route::post('/strategy', [MarketingController::class, 'storeStrategy']);

            Route::get('/social', [MarketingController::class, 'social'])->name('marketing.social');
            Route::post('/social', [MarketingController::class, 'storeSocial']);
        });

        Route::prefix('rbac')->group(function () {
            Route::get('roles', [RoleController::class, 'index'])
                ->name('roles.index')
                ->middleware('role:Master Admin');
            Route::post('roles', [RoleController::class, 'store'])
                ->name('roles.store')
                ->middleware('role:Master Admin');
            Route::post('roles/{role}/update-permissions', [RoleController::class, 'updatePermissions'])
                ->name('roles.update.permissions')
                ->middleware('role:Master Admin');
            Route::post('roles/{role}/assign-users', [RoleController::class, 'assignUsers'])
                ->name('roles.assign.users')
                ->middleware('role:Master Admin');
            Route::delete('roles/{role}', [RoleController::class, 'destroy'])
                ->name('roles.destroy')
                ->middleware('role:Master Admin');
            // Permission Routes
            Route::get('permissions', [PermissionController::class, 'index'])
                ->name('permissions.index')
                ->middleware('role:Master Admin');
            Route::post('permissions', [PermissionController::class, 'store'])
                ->name('permissions.store')
                ->middleware('role:Master Admin');
            Route::post('permissions/{permission}/update', [PermissionController::class, 'update'])
                ->name('permissions.update')
                ->middleware('role:Master Admin');
            Route::delete('permissions/{permission}', [PermissionController::class, 'destroy'])
                ->name('permissions.destroy')
                ->middleware('role:Master Admin');

            Route::get('users', [UserController::class, 'index'])->name('users.index');
            Route::post('users', [UserController::class, 'store'])->name('users.store');
            Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        });
    });

// Default redirect
Route::get('/', fn () => redirect()->route('login'));
