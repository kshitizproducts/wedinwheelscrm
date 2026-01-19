<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AddressContactsController;
use App\Http\Controllers\BasicInformationController;
use App\Http\Controllers\FinancialBankingController;
use App\Http\Controllers\LegalComplianceController;
use App\Http\Controllers\EmployeeDocumentationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CarServicingController;
use App\Http\Controllers\DriverTaskController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ForgotPasswordController;



use Illuminate\Support\Facades\Mail;
use App\Mail\TestEmail;

Route::get('/car-share/{token}', [LeadController::class, 'publicShowcase']);
Route::post('/car-share/{token}/confirm', [LeadController::class, 'confirmCar']);
// Public Showcase (Bina login wala)
Route::get('/showcase/{token}', [LeadController::class, 'publicShowcase'])->name('public.showcase');
Route::post('/finalize-car', [LeadController::class, 'finalizeCar'])->name('finalize.car');

//   Route::post('/car-share/{token}/confirm', [LeadController::class, 'confirmPublicCar']);

Route::get('/send-test-mail', function () {
    Mail::to('kshitiz.ranchi@gmail.com')->send(new TestEmail());
    return "✅ Test mail sent successfully to kshitiz.ranchi@gmail.com";
});    

require_once base_path('routes/role_permission.php');
require_once base_path('routes/user.php');
require_once base_path('routes/cars.php');
require_once base_path('routes/notification.php');
require_once base_path('routes/garage.php');
require_once base_path('routes/service.php');
require_once base_path('routes/accounts.php');
require_once base_path('routes/my_task.php');
require_once base_path('routes/bookings.php');
require_once base_path('routes/leads.php');
Route::get('/alert-test', [AlertController::class, 'index'])->name('alert.index');
Route::post('/alert-add', [AlertController::class, 'store'])->name('alert.store');
Route::get('/reset_password', [AuthController::class, 'reset_password'])->name('reset_password');


Route::get('/reset-password', [ForgotPasswordController::class, 'reset_password'])->name('password.request');
Route::post('/reset-password', [ForgotPasswordController::class, 'send_reset_link'])->name('password.email');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'show_reset_form'])->name('password.reset');
Route::post('/reset-password/update', [ForgotPasswordController::class, 'update_password'])->name('password.update');



Route::get('/test-mail', function () {
    $to = 'yourmail@example.com'; // replace with your email to receive the test
    try {
        Mail::raw('This is a test email from Laravel using Hostinger SMTP.', function ($message) use ($to) {
            $message->to($to)
                    ->subject('Test Email from WedinWheels');
        });
        return '✅ Test mail sent successfully!';
    } catch (\Exception $e) {
        return '❌ Mail failed: ' . $e->getMessage();
    }
});

Route::get('/', function () {
    return view('welcome');
});






// Public routes
Route::get('/login', function () {
    return view('auth.login');
});
Route::post('/login_now', [AuthController::class, 'login'])->name('login_now');

// Protected routes (requires login + admin role)
Route::middleware(['custom_auth', 'role:Admin'])->group(function () {

   

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');



  Route::get('/check-role', function () {
    dd(auth()->user()->getRoleNames());
});


 

    Route::get('/customer_enquiries', function () {
        return view('backend.pages.master.customer_enquiries');
    });

    Route::get('/transactions', function () {
        return view('backend.pages.master.transactions');
    });

    Route::get('/invoices', function () {
        return view('backend.pages.master.invoice.invoices');
    });

    Route::get('/dummy-invoice', function () {
        return view('backend.pages.master.invoice.dummy-invoice');
    });

    Route::get('/reports', function () {
        return view('backend.pages.master.reports');
    });

    



    
 

    Route::get('/edit_profile', function () {
        return view('backend.profile.edit_profile');
    });




 Route::get('/dashboard', function () {
        return view('backend.dashboard');
    }); 

Route::get('/dashboard', [AdminController::class, 'dashboard']);

Route::get('/bookings', [BookingController::class, 'index']);
Route::post('/add_booking', [BookingController::class, 'store']);
Route::post('/update_booking', [BookingController::class, 'update_booking']); // Naam controller se match hona chahiye
Route::post('/delete_booking', [BookingController::class, 'delete_booking']);






// Basic Information Routes

Route::get('/basic-information', [BasicInformationController::class, 'index'])->name('basic_information_index');
Route::get('/basic-information/get', [BasicInformationController::class, 'get'])->name('basic_information_get');
Route::post('/basic_information_save', [BasicInformationController::class, 'basic_information_save'])->name('basic_information_save');
Route::get('/basic-information/edit/{id}', [BasicInformationController::class, 'edit'])->name('basic_information_edit');
Route::delete('/basic-information/delete/{id}', [BasicInformationController::class, 'delete'])->name('basic_information_delete');





// legal 


Route::get('/legal-compliance', [LegalComplianceController::class, 'index'])->name('legal_index');

Route::get('/legal-compliance/get', [LegalComplianceController::class, 'get'])->name('legal_get');

Route::post('/legal-compliance/save', [LegalComplianceController::class, 'save'])->name('legal_save');

Route::get('/legal-compliance/edit/{id}', [LegalComplianceController::class, 'edit'])->name('legal_edit');

Route::delete('/legal-compliance/delete/{id}', [LegalComplianceController::class, 'delete'])->name('legal_delete');





// address

Route::get('/address_contacts', [AddressContactsController::class, 'index'])->name('address_contacts_index');

Route::get('/address_contacts/get', [AddressContactsController::class, 'get'])->name('address_contacts_get');

Route::post('/address_contacts/save', [AddressContactsController::class, 'save'])->name('address_contacts_save');

Route::get('/address_contacts/edit/{id}', [AddressContactsController::class, 'edit'])->name('address_contacts_edit');

Route::delete('/address_contacts/delete/{id}', [AddressContactsController::class, 'delete'])->name('address_contacts_delete');


 



// financial

// Route::get('/financial_banking', [FinancialBankingController::class, 'index'])->name('financial_banking_index');
// Route::get('/financial_banking/get', [FinancialBankingController::class, 'get'])->name('financial_banking_get');
// Route::post('/financial_banking/save', [FinancialBankingController::class, 'save'])->name('financial_banking_save');
// Route::get('/financial_banking/edit/{id}', [FinancialBankingController::class, 'edit'])->name('financial_banking_edit');
// Route::delete('/financial_banking/delete/{id}', [FinancialBankingController::class, 'delete'])->name('financial_banking_delete');

Route::get('/financial_banking', [FinancialBankingController::class, 'index']);
Route::get('/financial_banking/get', [FinancialBankingController::class, 'get'])->name('financial_banking_get');
Route::post('/financial_banking/save', [FinancialBankingController::class, 'save'])->name('financial_banking_save');

Route::get('/financial_banking/edit/{id}', [FinancialBankingController::class, 'edit']);
Route::delete('/financial_banking/delete/{id}', [FinancialBankingController::class, 'delete']);

 

 

// driver tasks routes

Route::get('/driver/tasks', [DriverTaskController::class, 'index'])->name('driver.tasks');
Route::get('/driver/task/list', [DriverTaskController::class, 'list'])->name('driver.task.list');
Route::post('/driver/task/store', [DriverTaskController::class, 'store'])->name('driver.task.store');
Route::get('/driver/task/edit/{id}', [DriverTaskController::class, 'edit']);
Route::post('/driver/task/update', [DriverTaskController::class, 'update'])->name('driver.task.update');
Route::delete('/driver/task/delete/{id}', [DriverTaskController::class, 'delete']);
Route::post('update_driver_schedule/{id}', [DriverTaskController::class, 'update_driver_schedule']);
Route::get('delete_driver_schedule/{id}', [DriverTaskController::class, 'delete_driver_schedule']);




Route::get('/driver_tasks', [DriverTaskController::class, 'driver_tasks'])->name('driver_tasks');
Route::post('driver_task_start/{id}', [DriverTaskController::class, 'driver_task_start']);
Route::post('driver_task_end/{id}', [DriverTaskController::class, 'driver_task_end']);
Route::post('driver_task_remark/{id}', [DriverTaskController::class, 'driver_task_remark']);
Route::get('delete_driver_task/{id}', [DriverTaskController::class, 'delete_driver_task']);
Route::get(
    'driver-task-remarks/{id}',
    [DriverTaskController::class,'driver_task_remarks']
);


 


    // Route::get('/profile', function () {
    //     return view('backend.profile.profile');
    // });
Route::get('/profile', [ProfileController::class, 'profile'])->name(name: 'profile');
Route::post('profile/photo/update', [ProfileController::class, 'updatePhoto']);
Route::post('user-documents/update', [ProfileController::class, 'update']);


Route::get('/employee_docs', [EmployeeDocumentationController::class, 'employee_docs'])->name('employee_docs');
    Route::get('/employee_documentation', [EmployeeDocumentationController::class, 'index'])
        ->name('employee_docs_page');
 
    // fetch employees list (json paginated)
    Route::get('/employee_docs_get', [EmployeeDocumentationController::class, 'getEmployees'])
        ->name('employee_docs_get');

    // upload/update employee documents (post)
    Route::post('/employee_docs_update', [EmployeeDocumentationController::class, 'updateDocs'])
        ->name('employee_docs_update');



 


// Car servicing 
Route::get('/car_servicing', [CarServicingController::class, 'car_servicing'])->name('car_servicing');

Route::get('/update_car_servicing/{id}', [CarServicingController::class, 'update_car_servicing'])->name('update_car_servicing');


 



    Route::get('/change_password', function () {
        return view('backend.profile.change_password');
    });


Route::post('change-password/update', [ProfileController::class, 'updatePassword']);




});
