<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Pages\PagesController;
use App\Http\Controllers\Bcategory\BcategoryController;
use App\Http\Controllers\Blog\BlogController;
use App\Http\Controllers\Banner\BannerController;
use App\Http\Controllers\Patient\PatientController;
use App\Http\Controllers\Doctor\DoctorController;
use App\Http\Controllers\Testimonial\TestimonialController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Contact\ContactController;
use App\Http\Controllers\Sponsor\SponsorController;
use App\Http\Controllers\Service\ServiceController;
use App\Http\Controllers\Setting\SettingController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellerController;

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    return 'Application cache has been cleared';
});

Route::controller(AjaxController::class)->group(function (){
    Route::post('/validate-email', 'validate_email')->name('validate-email');
    Route::post('/validate-mobile', 'validate_mobile')->name('validate-mobile');
    Route::post('/validate-signup', 'validate_signup')->name('validate-signup');
    Route::post('/resend-otp', 'resend_otp')->name('resend-otp');
    Route::post('/verify-otp', 'verify_otp')->name('verify-otp');
    Route::post('/validate-forgot', 'validate_forgot')->name('validate-forgot');
    Route::post('/reset-password', 'reset_password')->name('reset-password');
    Route::post('/validate-login', 'validate_login')->name('validate-login');
    Route::post('/validate-logout', 'logout')->name('validate-logout');
    Route::post('/favourite-toggle', 'favourite_toggle')->name('favourite-toggle');
});

Route::get('/admin', function () {
    if (\Auth::guest()) {
        return redirect(url('/admin/login'));
    } else {
        return redirect(url('/admin/dashboard'));
    }
});

Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [LoginController::class, 'login'])->name('login');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name("dashboard");
    Route::get('/admin/logout', [LoginController::class, 'logout'])->name('logout');

    //For Administrator
    Route::controller(UserController::class)->group(function (){
        Route::middleware('can:user-add')->group(function () {
            Route::get('/admin/user-add', 'create')->name('user-add');
            Route::post('/admin/user-insert', 'insert')->name('user-insert');
        });
        Route::middleware('can:user-edit')->group(function () {
            Route::get('/admin/user-edit/{id}', 'edit')->name('user-edit');
            Route::post('/admin/user-update', 'update')->name('user-update');
        });
        Route::middleware('can:user-list')->group(function () {
            Route::get("/admin/user-list", "view")->name("user-list");
            Route::get("/admin/user-load-table", "load_table")->name("user-load-table");
        });
        Route::middleware('can:user-delete')->group(function () {
            Route::post("/admin/user-delete", "delete")->name("user-delete");
            Route::get("/admin/user-changepassword/{id}", "changepassword")->name("user-changepassword");
            Route::post("/admin/user-changepassword-update", "changepassword_update")->name("user-changepassword-update");
        });
    });

    //For Role
    Route::controller(RoleController::class)->group(function (){
        Route::middleware('can:role-add')->group(function () {
            Route::get("/admin/role-add", "create")->name("role-add");
            Route::post("/admin/role-insert", "insert")->name("role-insert");
        });
        Route::middleware('can:role-edit')->group(function () {
            Route::get("/admin/role-edit/{id}", "edit")->name("role-edit");
            Route::post("/admin/role-update", "update")->name("role-update");
        });
        Route::middleware('can:role-list')->group(function () {
            Route::get("/admin/role-list", "view")->name("role-list");
            Route::get("/admin/role-load-table", "load_table")->name("role-load-table");
        });
        Route::middleware('can:role-delete')->group(function () {
            Route::post("/admin/role-delete", "delete")->name("role-delete");
        });
    });

    //For Pages
    Route::controller(PagesController::class)->group(function (){
        Route::middleware('can:pages-add')->group(function () {
            Route::get("/admin/pages-add", "create")->name("pages-add");
            Route::post("/admin/pages-insert", "insert")->name("pages-insert");
        });
        Route::middleware('can:pages-edit')->group(function () {
            Route::get("/admin/pages-edit/{id}", "edit")->name("pages-edit");
            Route::post("/admin/pages-update", "update")->name("pages-update");
        });
        Route::middleware('can:pages-list')->group(function () {
            Route::get("/admin/pages-list", "view")->name("pages-list");
        });
        Route::middleware('can:pages-delete')->group(function () {
            Route::post("/admin/pages-delete", "delete")->name("pages-delete");
        });
        Route::get("/admin/pages-create-slug", "createSlug")->name("pages-create-slug");
        Route::post("/admin/pages-change-status", "change_status")->name("pages-change-status");
        Route::post("/admin/pages-change-header-status", "change_header_status")->name("pages-change-header-status");
        Route::post("/admin/pages-change-footer-status", "change_footer_status")->name("pages-change-footer-status");
        Route::post("/admin/pages-update-order", "update_order")->name("pages-update-order");
        Route::post('/admin/pages-image-upload', 'uploadImage')->name('pages-image-upload');
    });

    //For Blog Category
    Route::controller(BcategoryController::class)->group(function (){
        Route::middleware('can:bcategory-add')->group(function () {
            Route::get("/admin/bcategory-add", "create")->name("bcategory-add");
            Route::post("/admin/bcategory-insert", "insert")->name("bcategory-insert");
        });
        Route::middleware('can:bcategory-edit')->group(function () {
            Route::get("/admin/bcategory-edit/{id}", "edit")->name("bcategory-edit");
            Route::post("/admin/bcategory-update", "update")->name("bcategory-update");
        });
        Route::middleware('can:bcategory-list')->group(function () {
            Route::get("/admin/bcategory-list", "view")->name("bcategory-list");
            Route::get("/admin/bcategory-load-table", "load_table")->name("bcategory-load-table");
        });
        Route::middleware('can:bcategory-delete')->group(function () {
            Route::post("/admin/bcategory-delete", "delete")->name("bcategory-delete");
        });
        Route::get("/admin/bcategory-create-slug", "createSlug")->name("bcategory-create-slug");
        Route::post("/admin/bcategory-change-status", "change_status")->name("bcategory-change-status");
        Route::post("/admin/bcategory-update-order", "update_order")->name("bcategory-update-order");
    });

    //For Blog
    Route::controller(BlogController::class)->group(function (){
        Route::middleware('can:blog-add')->group(function () {
            Route::get("/admin/blog-add", "create")->name("blog-add");
            Route::post("/admin/blog-insert", "insert")->name("blog-insert");
        });
        Route::middleware('can:blog-edit')->group(function () {
            Route::get("/admin/blog-edit/{id}", "edit")->name("blog-edit");
            Route::post("/admin/blog-update", "update")->name("blog-update");
        });
        Route::middleware('can:blog-list')->group(function () {
            Route::get("/admin/blog-list", "view")->name("blog-list");
            Route::get("/admin/blog-load-table", "load_table")->name("blog-load-table");
        });
        Route::middleware('can:blog-delete')->group(function () {
            Route::post("/admin/blog-delete", "delete")->name("blog-delete");
        });
        Route::get("/admin/blog-create-slug", "createSlug")->name("blog-create-slug");
        Route::post("/admin/blog-change-status", "change_status")->name("blog-change-status");
        Route::post("/admin/blog-update-order", "update_order")->name("blog-update-order");
        Route::post('/admin/blog-image-upload', 'uploadImage')->name('blog-image-upload');
    });

    //For Banner
    Route::controller(BannerController::class)->group(function (){
        Route::middleware('can:banner-add')->group(function () {
            Route::get("/admin/banner-add", "create")->name("banner-add");
            Route::post("/admin/banner-insert", "insert")->name("banner-insert");
        });
        Route::middleware('can:banner-edit')->group(function () {
            Route::get("/admin/banner-edit/{id}", "edit")->name("banner-edit");
            Route::post("/admin/banner-update", "update")->name("banner-update");
        });
        Route::middleware('can:banner-list')->group(function () {
            Route::get("/admin/banner-list", "view")->name("banner-list");
            Route::get("/admin/banner-load-table", "load_table")->name("banner-load-table");
        });
        Route::middleware('can:banner-delete')->group(function () {
            Route::post("/admin/banner-delete", "delete")->name("banner-delete");
        });
        Route::post("/admin/banner-change-status", "change_status")->name("banner-change-status");
        Route::post("/admin/banner-update-order", "update_order")->name("banner-update-order");
        Route::post('/admin/banner-image-upload', 'uploadImage')->name('banner-image-upload');
    });

    //For Patient
    Route::controller(PatientController::class)->group(function (){
        Route::middleware('can:patient-add')->group(function () {
            Route::get("/admin/patient-add", "create")->name("patient-add");
            Route::post("/admin/patient-insert", "insert")->name("patient-insert");
        });
        Route::middleware('can:patient-edit')->group(function () {
            Route::get("/admin/patient-edit/{id}", "edit")->name("patient-edit");
            Route::post("/admin/patient-update", "update")->name("patient-update");
        });
        Route::middleware('can:patient-list')->group(function () {
            Route::get("/admin/patient-list", "view")->name("patient-list");
            Route::get("/admin/patient-load-table", "load_table")->name("patient-load-table");
        });
        Route::middleware('can:patient-delete')->group(function () {
            Route::post("/admin/patient-delete", "delete")->name("patient-delete");
        });
        Route::get("/admin/patient-create-slug", "createSlug")->name("patient-create-slug");
        Route::post("/admin/patient-change-status", "change_status")->name("patient-change-status");
        Route::post("/admin/patient-update-order", "update_order")->name("patient-update-order");
        Route::post('/admin/patient-image-upload', 'uploadImage')->name('patient-image-upload');
    });

    //For Doctor
    Route::controller(DoctorController::class)->group(function (){
        Route::middleware('can:doctor-add')->group(function () {
            Route::get("/admin/doctor-add", "create")->name("doctor-add");
            Route::post("/admin/doctor-insert", "insert")->name("doctor-insert");
        });
        Route::middleware('can:doctor-edit')->group(function () {
            Route::get("/admin/doctor-edit/{id}", "edit")->name("doctor-edit");
            Route::post("/admin/doctor-update", "update")->name("doctor-update");
        });
        Route::middleware('can:doctor-list')->group(function () {
            Route::get("/admin/doctor-list", "view")->name("doctor-list");
            Route::get("/admin/doctor-load-table", "load_table")->name("doctor-load-table");
        });
        Route::middleware('can:doctor-delete')->group(function () {
            Route::post("/admin/doctor-delete", "delete")->name("doctor-delete");
        });
        Route::get("/admin/doctor-create-slug", "createSlug")->name("doctor-create-slug");
        Route::post("/admin/doctor-change-status", "change_status")->name("doctor-change-status");
        Route::post("/admin/doctor-update-order", "update_order")->name("doctor-update-order");
        Route::post('/admin/doctor-image-upload', 'uploadImage')->name('doctor-image-upload');
    });

    //For Testimonial
    Route::controller(TestimonialController::class)->group(function (){
        Route::middleware('can:testimonial-add')->group(function () {
            Route::get("/admin/testimonial-add", "create")->name("testimonial-add");
            Route::post("/admin/testimonial-insert", "insert")->name("testimonial-insert");
        });
        Route::middleware('can:testimonial-edit')->group(function () {
            Route::get("/admin/testimonial-edit/{id}", "edit")->name("testimonial-edit");
            Route::post("/admin/testimonial-update", "update")->name("testimonial-update");
        });
        Route::middleware('can:testimonial-list')->group(function () {
            Route::get("/admin/testimonial-list", "view")->name("testimonial-list");
            Route::get("/admin/testimonial-load-table", "load_table")->name("testimonial-load-table");
        });
        Route::middleware('can:testimonial-delete')->group(function () {
            Route::post("/admin/testimonial-delete", "delete")->name("testimonial-delete");
        });
        Route::post("/admin/testimonial-change-status", "change_status")->name("testimonial-change-status");
        Route::post("/admin/testimonial-update-order", "update_order")->name("testimonial-update-order");
        Route::post('/admin/testimonial-image-upload', 'uploadImage')->name('testimonial-image-upload');
    });

    //For Category
    Route::controller(CategoryController::class)->group(function (){
        Route::middleware('can:category-add')->group(function () {
            Route::get("/admin/category-add", "create")->name("category-add");
            Route::post("/admin/category-insert", "insert")->name("category-insert");
        });
        Route::middleware('can:category-edit')->group(function () {
            Route::get("/admin/category-edit/{id}", "edit")->name("category-edit");
            Route::post("/admin/category-update", "update")->name("category-update");
        });
        Route::middleware('can:category-list')->group(function () {
            Route::get("/admin/category-list", "view")->name("category-list");
            Route::get("/admin/category-load-table", "load_table")->name("category-load-table");
        });
        Route::middleware('can:category-delete')->group(function () {
            Route::post("/admin/category-delete", "delete")->name("category-delete");
        });
        Route::get("/admin/category-create-slug", "createSlug")->name("category-create-slug");
        Route::post("/admin/category-change-status", "change_status")->name("category-change-status");
        Route::post("/admin/category-update-order", "update_order")->name("category-update-order");
        Route::post('/admin/category-image-upload', 'uploadImage')->name('category-image-upload');
    });

    //For Contact
    Route::controller(ContactController::class)->group(function (){
        Route::middleware('can:contact-list')->group(function () {
            Route::get("/admin/contact-list", "view")->name("contact-list");
            Route::get("/admin/contact-load-table", "load_table")->name("contact-load-table");
        });
        Route::middleware('can:contact-delete')->group(function () {
            Route::post("/admin/contact-delete", "delete")->name("contact-delete");
        });
        Route::post("/admin/contact-update-order", "update_order")->name("contact-update-order");
    });

    //For Sponsor
    Route::controller(SponsorController::class)->group(function (){
        Route::middleware('can:sponsor-add')->group(function () {
            Route::get("/admin/sponsor-add", "create")->name("sponsor-add");
            Route::post("/admin/sponsor-insert", "insert")->name("sponsor-insert");
        });
        Route::middleware('can:sponsor-edit')->group(function () {
            Route::get("/admin/sponsor-edit/{id}", "edit")->name("sponsor-edit");
            Route::post("/admin/sponsor-update", "update")->name("sponsor-update");
        });
        Route::middleware('can:sponsor-list')->group(function () {
            Route::get("/admin/sponsor-list", "view")->name("sponsor-list");
            Route::get("/admin/sponsor-load-table", "load_table")->name("sponsor-load-table");
        });
        Route::middleware('can:sponsor-delete')->group(function () {
            Route::post("/admin/sponsor-delete", "delete")->name("sponsor-delete");
        });
        Route::post("/admin/sponsor-change-status", "change_status")->name("sponsor-change-status");
        Route::post("/admin/sponsor-update-order", "update_order")->name("sponsor-update-order");
        Route::post('/admin/sponsor-image-upload', 'uploadImage')->name('sponsor-image-upload');
    });

    //For Service
    Route::controller(ServiceController::class)->group(function (){
        Route::middleware('can:service-add')->group(function () {
            Route::get("/admin/service-add", "create")->name("service-add");
            Route::post("/admin/service-insert", "insert")->name("service-insert");
        });
        Route::middleware('can:service-edit')->group(function () {
            Route::get("/admin/service-edit/{id}", "edit")->name("service-edit");
            Route::post("/admin/service-update", "update")->name("service-update");
        });
        Route::middleware('can:service-list')->group(function () {
            Route::get("/admin/service-list", "view")->name("service-list");
            Route::get("/admin/service-load-table", "load_table")->name("service-load-table");
        });
        Route::middleware('can:service-delete')->group(function () {
            Route::post("/admin/service-delete", "delete")->name("service-delete");
        });
        Route::get("/admin/service-create-slug", "createSlug")->name("service-create-slug");
        Route::post("/admin/service-change-status", "change_status")->name("service-change-status");
        Route::post("/admin/service-update-order", "update_order")->name("service-update-order");
        Route::post('/admin/service-image-upload', 'uploadImage')->name('service-image-upload');
    });

    //For Customer
    Route::controller(CustomerController::class)->group(function (){
        Route::middleware('can:customer-list')->group(function () {
            Route::get("/admin/customer-list", "view")->name("customer-list");
            Route::get("/admin/customer-load-table", "load_table")->name("customer-load-table");
        });
        Route::middleware('can:customer-delete')->group(function () {
            Route::post("/admin/customer-delete", "delete")->name("customer-delete");
        });
        Route::post("/admin/customer-change-status", "change_status")->name("customer-change-status");
        Route::post("/admin/customer-update-order", "update_order")->name("customer-update-order");
    });

    //For Product
    Route::controller(ProductController::class)->group(function (){
        Route::middleware('can:product-add')->group(function () {
            Route::get("/admin/product-add", "create")->name("product-add");
            Route::post("/admin/product-insert", "insert")->name("product-insert");
        });
        Route::middleware('can:product-edit')->group(function () {
            Route::get("/admin/product-edit/{id}", "edit")->name("product-edit");
            Route::post("/admin/product-update", "update")->name("product-update");
        });
        Route::middleware('can:product-list')->group(function () {
            Route::get("/admin/product-list", "view")->name("product-list");
            Route::get("/admin/product-load-table", "load_table")->name("product-load-table");
        });
        Route::middleware('can:product-delete')->group(function () {
            Route::post("/admin/product-delete", "delete")->name("product-delete");
        });
        Route::get("/admin/product-create-slug", "createSlug")->name("product-create-slug");
        Route::post("/admin/product-change-status", "change_status")->name("product-change-status");
        Route::post("/admin/product-update-order", "update_order")->name("product-update-order");
        Route::get("/admin/product-get-subcategory", "getSubcategory")->name("product-get-subcategory");
        Route::get("/admin/product-get-city", "getCity")->name("product-get-city");
        Route::post('/admin/product-image-upload', 'uploadImage')->name('product-image-upload');
        Route::post('/admin/product-image-delete', 'deleteImage')->name('product-image-delete');
    });

    //For Setting
    Route::controller(SettingController::class)->group(function (){
        Route::middleware('can:setting-edit')->group(function () {
            Route::get("/admin/setting", "edit")->name("setting");
            Route::post("/admin/setting-update", "update")->name("setting-update");
        });
    });
});

Route::controller(ProfileController::class)
    ->middleware('customer.login')
    ->group(function () {
        Route::get('/my-account', 'myAccount');
        Route::post('/my-account-update', 'myAccountUpdate')->name('my-account-update');
        Route::get('/my-listing', 'myListing')->name('my-listing');
        Route::post('/mark-as-sold', 'markAsSold')->name('mark-as-sold');
        Route::get('/seller-inquiry', [SellerController::class, 'index'])->name('seller-inquiry');
        Route::post('/seller-inquiry-insert', [SellerController::class, 'insert'])->name('seller-inquiry-insert');
        Route::get('/seller-inquiry-get-subcategory', [SellerController::class, 'getSubcategory'])->name('seller-inquiry-get-subcategory');
        Route::get('/seller-inquiry-get-city', [SellerController::class, 'getCity'])->name('seller-inquiry-get-city');
        Route::post('/seller-inquiry-image-upload', [SellerController::class, 'uploadImage'])->name('seller-inquiry-image-upload');
        Route::post('/seller-inquiry-image-delete', [SellerController::class, 'deleteImage'])->name('seller-inquiry-image-delete');
        Route::get('/change-password', 'changePassword');
    });

Route::controller(\App\Http\Controllers\ChatController::class)
    ->middleware('customer.login')
    ->group(function () {
        Route::get('/chat', 'index')->name('chat.index');
        Route::get('/chat/{otherId}', 'show')->name('chat.show');
        Route::post('/chat/send', 'store')->name('chat.store');
    });

Route::controller(HomeController::class)->group(function (){
    Route::get('/', 'index')->name('/');
    Route::get('/blogs','blog')->name('blog');
    Route::get('/blogs/{slug}', 'blogDetail');
    Route::get('/contact-us', 'contact')->name('contacts');
    Route::post('/contact-insert', 'contact_insert')->name('contact-insert');
    Route::get('/machines', 'product')->name('machines');
    Route::get('/machines/{slug}', 'productDetail');
    Route::get('/404', 'error404');
    Route::get('/{slug}', 'page');
});

require __DIR__.'/auth.php';
