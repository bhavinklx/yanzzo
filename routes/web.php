<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Banner\BannerController;
use App\Http\Controllers\Pages\PagesController;
use App\Http\Controllers\Bcategory\BcategoryController;
use App\Http\Controllers\Blog\BlogController;
use App\Http\Controllers\Testimonial\TestimonialController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\City\CityController;
use App\Http\Controllers\Service\ServiceController;
use App\Http\Controllers\Usp\UspController;
use App\Http\Controllers\Faq\FaqController;
use App\Http\Controllers\Contact\ContactController;
use App\Http\Controllers\Inquiry\InquiryController;
use App\Http\Controllers\Franchise\FranchiseController;
use App\Http\Controllers\Lounge\LoungeController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\MembershipOrder\MembershipOrderController;
use App\Http\Controllers\Discount\DiscountController;
use App\Http\Controllers\Membership\MembershipController;
use App\Http\Controllers\Setting\SettingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RazorpayWebhookController;
use Illuminate\Support\Facades\Mail;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
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
});

Auth::routes();
Route::get('/admin', function () {
    if (\Auth::guest()) {
        return redirect(url('/admin/login'));
    } else {
        return redirect(url('/admin/dashboard'));
    }
});

Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [LoginController::class, 'login'])->name('login');
//Route::get("/admin/access-denied", "RoleController@access_denied")->name("access-denied");
Route::group(['middleware' => ['auth'/*, 'www.admin'*/]], function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name("dashboard");
    Route::get('/admin/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/admin/role-add', [RoleController::class, 'create'])->name("role-add");
    Route::get('/admin/role-edit/{id}', [RoleController::class, 'edit'])->name("role-edit");
    Route::post('/admin/role-insert', [RoleController::class, 'insert'])->name("role-insert");
    Route::post('/admin/role-update', [RoleController::class, 'update'])->name("role-update");
    Route::get('/admin/role-list', [RoleController::class, 'view'])->name("role-list");
    Route::get('/admin/role-load-table', [RoleController::class, 'load_table'])->name("role-load-table");
    Route::post('/admin/role-delete', [RoleController::class, 'delete'])->name("role-delete");

    //For User Route
    Route::get('/admin/user-add', [UserController::class, 'create'])->name("user-add");
    Route::get('/admin/user-edit/{id}', [UserController::class, 'edit'])->name("user-edit");
    Route::get('/admin/user-list', [UserController::class, 'view'])->name("user-list");
    Route::post('/admin/user-insert', [UserController::class, 'insert'])->name("user-insert");
    Route::post('/admin/user-update', [UserController::class, 'update'])->name("user-update");
    Route::get('/admin/user-load-table', [UserController::class, 'load_table'])->name("user-load-table");
    Route::post('/admin/user-delete', [UserController::class, 'delete'])->name("user-delete");
    Route::get('/admin/user-changepassword/{id}', [UserController::class, 'changepassword'])->name("user-changepassword");
    Route::post('/admin/user-changepassword-update', [UserController::class, 'changepassword_update'])->name("user-changepassword-update");

    //For banner
    Route::get("/admin/banner-add", [BannerController::class, "create"])->name("banner-add");
    Route::get("/admin/banner-edit/{id}", [BannerController::class, "edit"])->name("banner-edit");
    Route::get("/admin/banner-list", [BannerController::class, "view"])->name("banner-list");
    Route::get("/admin/banner-load-table", [BannerController::class, "load_table"])->name("banner-load-table");
    Route::post("/admin/banner-insert", [BannerController::class, "insert"])->name("banner-insert");
    Route::post("/admin/banner-update", [BannerController::class, "update"])->name("banner-update");
    Route::post("/admin/banner-change-status", [BannerController::class, "change_status"])->name("banner-change-status");
    Route::post("/admin/banner-update-order", [BannerController::class, "update_order"])->name("banner-update-order");
    Route::post("/admin/banner-delete", [BannerController::class, "delete"])->name("banner-delete");

    //For pages
    Route::get("/admin/pages-add", [PagesController::class, "create"])->name("pages-add");
    Route::get("/admin/pages-edit/{id}", [PagesController::class, "edit"])->name("pages-edit");
    Route::get("/admin/pages-create-slug", [PagesController::class, "createSlug"])->name("pages-create-slug");
    Route::get("/admin/pages-list", [PagesController::class, "view"])->name("pages-list");
    Route::post("/admin/pages-insert", [PagesController::class, "insert"])->name("pages-insert");
    Route::post("/admin/pages-update", [PagesController::class, "update"])->name("pages-update");
    Route::post("/admin/pages-change-status", [PagesController::class, "change_status"])->name("pages-change-status");
    Route::post("/admin/pages-change-header-status", [PagesController::class, "change_header_status"])->name("pages-change-header-status");
    Route::post("/admin/pages-change-footer-status", [PagesController::class, "change_footer_status"])->name("pages-change-footer-status");
    Route::post("/admin/pages-delete", [PagesController::class, "delete"])->name("pages-delete");
    Route::post("/admin/pages-update-order", [PagesController::class, "update_order"])->name("pages-update-order");

    //For Blog Category Route
    Route::get("/admin/bcategory-add", [BcategoryController::class, "create"])->name("bcategory-add");
    Route::get("/admin/bcategory-edit/{id}", [BcategoryController::class, "edit"])->name("bcategory-edit");
    Route::get("/admin/bcategory-create-slug", [BcategoryController::class, "createSlug"])->name("bcategory-create-slug");
    Route::get("/admin/bcategory-list", [BcategoryController::class, "view"])->name("bcategory-list");
    Route::get("/admin/bcategory-load-table", [BcategoryController::class, "load_table"])->name("bcategory-load-table");
    Route::post("/admin/bcategory-insert", [BcategoryController::class, "insert"])->name("bcategory-insert");
    Route::post("/admin/bcategory-update", [BcategoryController::class, "update"])->name("bcategory-update");
    Route::post("/admin/bcategory-change-status", [BcategoryController::class, "change_status"])->name("bcategory-change-status");
    Route::post("/admin/bcategory-update-order", [BcategoryController::class, "update_order"])->name("bcategory-update-order");
    Route::post("/admin/bcategory-delete", [BcategoryController::class, "delete"])->name("bcategory-delete");
    Route::get('/admin/bcategory-export', [BcategoryController::class, 'export'])->name("bcategory-export");

    //For Blog
    Route::get("/admin/blog-add", [BlogController::class, "create"])->name("blog-add");
    Route::get("/admin/blog-edit/{id}", [BlogController::class, "edit"])->name("blog-edit");
    Route::get("/admin/blog-create-slug", [BlogController::class, "createSlug"])->name("blog-create-slug");
    Route::get("/admin/blog-list", [BlogController::class, "view"])->name("blog-list");
    Route::get("/admin/blog-load-table", [BlogController::class, "load_table"])->name("blog-load-table");
    Route::post("/admin/blog-insert", [BlogController::class, "insert"])->name("blog-insert");
    Route::post("/admin/blog-update", [BlogController::class, "update"])->name("blog-update");
    Route::post("/admin/blog-change-status", [BlogController::class, "change_status"])->name("blog-change-status");
    Route::post("/admin/blog-change-popular-status", [BlogController::class, "change_popula_status"])->name("blog-change-popular-status");
    Route::post("/admin/blog-update-order", [BlogController::class, "update_order"])->name("blog-update-order");
    Route::post("/admin/blog-delete", [BlogController::class, "delete"])->name("blog-delete");

    //For Testimonial
    Route::get("/admin/testimonial-add", [TestimonialController::class, "create"])->name("testimonial-add");
    Route::get("/admin/testimonial-edit/{id}", [TestimonialController::class, "edit"])->name("testimonial-edit");
    Route::get("/admin/testimonial-list", [TestimonialController::class, "view"])->name("testimonial-list");
    Route::get("/admin/testimonial-load-table", [TestimonialController::class, "load_table"])->name("testimonial-load-table");
    Route::post("/admin/testimonial-insert", [TestimonialController::class, "insert"])->name("testimonial-insert");
    Route::post("/admin/testimonial-update", [TestimonialController::class, "update"])->name("testimonial-update");
    Route::post("/admin/testimonial-change-status", [TestimonialController::class, "change_status"])->name("testimonial-change-status");
    Route::post("/admin/testimonial-delete", [TestimonialController::class, "delete"])->name("testimonial-delete");
    Route::post("/admin/testimonial-update-order", [TestimonialController::class, "update_order"])->name("testimonial-update-order");

    //For Product Category
    Route::get("/admin/category-add", [CategoryController::class, "create"])->name("category-add");
    Route::get("/admin/category-edit/{id}", [CategoryController::class, "edit"])->name("category-edit");
    Route::get("/admin/category-create-slug", [CategoryController::class, "createSlug"])->name("category-create-slug");
    Route::get("/admin/category-list", [CategoryController::class, "view"])->name("category-list");
    Route::get("/admin/category-load-table", [CategoryController::class, "load_table"])->name("category-load-table");
    Route::post("/admin/category-insert", [CategoryController::class, "insert"])->name("category-insert");
    Route::post("/admin/category-update", [CategoryController::class, "update"])->name("category-update");
    Route::post("/admin/category-change-status", [CategoryController::class, "change_status"])->name("category-change-status");
    Route::post("/admin/category-change-home-status", [CategoryController::class, "change_home_status"])->name("category-change-home-status");
    Route::post("/admin/category-delete", [CategoryController::class, "delete"])->name("category-delete");
    Route::post("/admin/category-update-order", [CategoryController::class, "update_order"])->name("category-update-order");

    //For City
    Route::controller(CityController::class)->group(function (){
        Route::get("/admin/city-add", "create")->name("city-add");
        Route::get("/admin/city-edit/{id}", "edit")->name("city-edit");
        Route::get("/admin/city-create-slug", "createSlug")->name("city-create-slug");
        Route::get("/admin/city-list", "view")->name("city-list");
        Route::post("/admin/city-load-type", "load_type")->name("city-load-type");
        Route::get("/admin/city-load-table", "load_table")->name("city-load-table");
        Route::post("/admin/city-insert", "insert")->name("city-insert");
        Route::post("/admin/city-update", "update")->name("city-update");
        Route::post("/admin/city-change-status", "change_status")->name("city-change-status");
        Route::post("/admin/city-update-order", "update_order")->name("city-update-order");
        Route::post("/admin/city-delete", "delete")->name("city-delete");
        Route::post("/admin/city-delete-type", "delete_type")->name("city-delete-type");
    });

    //For Service
    Route::controller(ServiceController::class)->group(function (){
        Route::get("/admin/service-add", "create")->name("service-add");
        Route::get("/admin/service-edit/{id}", "edit")->name("service-edit");
        Route::get("/admin/service-create-slug", "createSlug")->name("service-create-slug");
        Route::get("/admin/service-list", "view")->name("service-list");
        Route::get("/admin/service-load-table", "load_table")->name("service-load-table");
        Route::post("/admin/service-insert", "insert")->name("service-insert");
        Route::post("/admin/service-update", "update")->name("service-update");
        Route::post("/admin/service-change-status", "change_status")->name("service-change-status");
        Route::post("/admin/service-update-order", "update_order")->name("service-update-order");
        Route::post("/admin/service-delete", "delete")->name("service-delete");
    });

    //For Usp
    Route::controller(UspController::class)->group(function (){
        Route::get("/admin/usp-add", "create")->name("usp-add");
        Route::get("/admin/usp-edit/{id}", "edit")->name("usp-edit");
        Route::get("/admin/usp-create-slug", "createSlug")->name("usp-create-slug");
        Route::get("/admin/usp-list", "view")->name("usp-list");
        Route::get("/admin/usp-load-table", "load_table")->name("usp-load-table");
        Route::post("/admin/usp-insert", "insert")->name("usp-insert");
        Route::post("/admin/usp-update", "update")->name("usp-update");
        Route::post("/admin/usp-change-status", "change_status")->name("usp-change-status");
        Route::post("/admin/usp-update-order", "update_order")->name("usp-update-order");
        Route::post("/admin/usp-delete", "delete")->name("usp-delete");
    });

    //For FAQ
    Route::controller(FaqController::class)->group(function (){
        Route::get("/admin/faq-add", "create")->name("faq-add");
        Route::get("/admin/faq-edit/{id}", "edit")->name("faq-edit");
        Route::get("/admin/faq-list", "view")->name("faq-list");
        Route::get("/admin/faq-load-table", "load_table")->name("faq-load-table");
        Route::post("/admin/faq-insert", "insert")->name("faq-insert");
        Route::post("/admin/faq-update", "update")->name("faq-update");
        Route::post("/admin/faq-change-status", "change_status")->name("faq-change-status");
        Route::post("/admin/faq-change-home-status", "change_home_status")->name("faq-change-home-status");
        Route::post("/admin/faq-update-order", "update_order")->name("faq-update-order");
        Route::post("/admin/faq-delete", "delete")->name("faq-delete");
    });

    //For Franchise
    Route::controller(FranchiseController::class)->group(function (){
        Route::get("/admin/franchise-add", "create")->name("franchise-add");
        Route::get("/admin/franchise-edit/{id}", "edit")->name("franchise-edit");
        Route::get("/admin/franchise-list", "view")->name("franchise-list");
        Route::get("/admin/franchise-load-table", "load_table")->name("franchise-load-table");
        Route::post("/admin/franchise-insert", "insert")->name("franchise-insert");
        Route::post("/admin/franchise-update", "update")->name("franchise-update");
        Route::post("/admin/franchise-change-status", "change_status")->name("franchise-change-status");
        Route::post("/admin/franchise-update-order", "update_order")->name("franchise-update-order");
        Route::post("/admin/franchise-delete", "delete")->name("franchise-delete");
        Route::post("/admin/franchise-lounge-delete", "delete_lounge")->name("franchise-lounge-delete");
    });

    //For Venue
    Route::controller(LoungeController::class)->group(function (){
        Route::get("/admin/lounge-add", "create")->name("lounge-add");
        Route::get("/admin/lounge-edit/{id}", "edit")->name("lounge-edit");
        Route::get("/admin/lounge-create-slug", "createSlug")->name("lounge-create-slug");
        Route::post("/admin/lounge-pupload", "pupload")->name("lounge-pupload");
        Route::get("/admin/lounge-list", "view")->name("lounge-list");
        Route::get("/admin/lounge-load-table", "load_table")->name("lounge-load-table");
        Route::post("/admin/lounge-insert", "insert")->name("lounge-insert");
        Route::post("/admin/lounge-update", "update")->name("lounge-update");
        Route::post("/admin/lounge-change-status", "change_status")->name("lounge-change-status");
        Route::post("/admin/lounge-change-book-status", "change_book_status")->name("lounge-change-book-status");
        Route::post("/admin/lounge-change-maintenance-status", "change_maintenance_status")->name("lounge-change-maintenance-status");
        Route::post("/admin/lounge-update-order", "update_order")->name("lounge-update-order");
        Route::post("/admin/lounge-delete", "delete")->name("lounge-delete");
        Route::post("/admin/lounge-remove-image", "remove_image")->name("lounge-remove-image");
        Route::post("/admin/lounge-time-delete", "deleteLTime")->name("lounge-time-delete");
        Route::post("/admin/lounge-maintenance-time-delete", "deleteLMTime")->name("lounge-maintenance-time-delete");
    });

    //For Customer
    Route::controller(CustomerController::class)->group(function (){
        Route::get("/admin/customer-add", "create")->name("customer-add");
        Route::get("/admin/customer-list", "view")->name("customer-list");
        Route::get("/admin/customer-load-table", "load_table")->name("customer-load-table");
        Route::post("/admin/customer-change-status", "change_status")->name("customer-change-status");
        Route::post("/admin/customer-update-order", "update_order")->name("customer-update-order");
        Route::post("/admin/customer-delete", "delete")->name("customer-delete");
        Route::post("/admin/search-customer", "search_customer")->name("search-customer");
        Route::post('/admin/customer-load-time-slot', 'loadTimeSlot')->name("customer-load-time-slot");
        Route::post("/admin/customer-lounge-insert", "customerLounge_insert")->name("customer-lounge-insert");
    });

    //For Order
    Route::controller(OrderController::class)->group(function (){
        Route::get("/admin/order-list/vieworder/{id}", "view_order");
        Route::get("/admin/order-list/{status}", "view")->name("order-list");
        Route::get("/admin/order-load-table", "load_table")->name("order-load-table");
        Route::post("/admin/order-change-status", "change_status")->name("order-change-status");
        Route::post("/admin/order-change-cancelled-status", "change_cancelled_status")->name("order-change-cancelled-status");
        Route::post("/admin/order-change-refunded-status", "change_refunded_status")->name("order-change-refunded-status");
        Route::post("/admin/order-delete", "delete")->name("order-delete");
    });

    //For Discount
    Route::controller(DiscountController::class)->group(function (){
        Route::get("/admin/discount-add", "create")->name("discount-add");
        Route::get("/admin/discount-edit/{id}", "edit")->name("discount-edit");
        Route::get("/admin/discount-list", "view")->name("discount-list");
        Route::get("/admin/discount-load-table", "load_table")->name("discount-load-table");
        Route::post("/admin/discount-insert", "insert")->name("discount-insert");
        Route::post("/admin/discount-update", "update")->name("discount-update");
        Route::post("/admin/discount-change-status", "change_status")->name("discount-change-status");
        Route::post("/admin/discount-update-order", "update_order")->name("discount-update-order");
        Route::post("/admin/discount-delete", "delete")->name("discount-delete");
    });

    //For Membership
    Route::controller(MembershipController::class)->group(function (){
        Route::get("/admin/membership-add", "create")->name("membership-add");
        Route::get("/admin/membership-edit/{id}", "edit")->name("membership-edit");
        Route::get("/admin/membership-create-slug", "createSlug")->name("membership-create-slug");
        Route::get("/admin/membership-list", "view")->name("membership-list");
        Route::get("/admin/membership-load-table", "load_table")->name("membership-load-table");
        Route::post("/admin/membership-insert", "insert")->name("membership-insert");
        Route::post("/admin/membership-update", "update")->name("membership-update");
        Route::post("/admin/membership-change-status", "change_status")->name("membership-change-status");
        Route::post("/admin/membership-change-recommended-status", "change_recommended_status")->name("membership-change-recommended-status");
        Route::post("/admin/membership-update-order", "update_order")->name("membership-update-order");
        Route::post("/admin/membership-delete", "delete")->name("membership-delete");
    });

    //For Membership Order
    Route::controller(MembershipOrderController::class)->group(function (){
        Route::get("/admin/membership-order-list/{status}", "view")->name("membership-order-list");
        Route::get("/admin/membership-order-load-table", "load_table")->name("membership-order-load-table");
        Route::post("/admin/membership-order-change-status", "change_status")->name("membership-order-change-status");
        Route::post("/admin/membership-order-change-cancelled-status", "change_cancelled_status")->name("membership-order-change-cancelled-status");
        Route::post("/admin/membership-order-change-refunded-status", "change_refunded_status")->name("membership-order-change-refunded-status");
        Route::post("/admin/membership-order-delete", "delete")->name("membership-order-delete");
    });

    //For Contact
    Route::get("/admin/contact-list", [ContactController::class, "view"])->name("contact-list");
    Route::get("/admin/contact-load-table", [ContactController::class, "load_table"])->name("contact-load-table");
    Route::post("/admin/contact-delete", [ContactController::class, "delete"])->name("contact-delete");
    Route::get("/admin/contact-export", [ContactController::class, "export"])->name("contact-export");

    //For Contact
    Route::get("/admin/inquiry-list", [InquiryController::class, "view"])->name("inquiry-list");
    Route::get("/admin/inquiry-load-table", [InquiryController::class, "load_table"])->name("inquiry-load-table");
    Route::post("/admin/inquiry-delete", [InquiryController::class, "delete"])->name("inquiry-delete");
    Route::get("/admin/inquiry-export", [InquiryController::class, "export"])->name("inquiry-export");

    //For Setting
    Route::get("/admin/setting", [SettingController::class, "edit"])->name("setting");
    Route::post("/admin/setting-update", [SettingController::class, "update"])->name("setting-update");
});

Route::controller(ProfileController::class)->group(function (){
    Route::get('/my-account', 'myAccount');
    Route::post('/my-account-update', 'myAccountUpdate')->name('my-account-update');
    Route::get('/my-booking', 'myBooking');
    Route::get('/change-password', 'changePassword');
});

Route::controller(RazorpayWebhookController::class)->group(function (){
    Route::any('/razorpaywebhook', 'handle')->name('/razorpaywebhook');
});

Route::controller(CheckoutController::class)->group(function (){
    Route::get('/booking-lounge/{id}', 'bookingLounge');
    Route::post('/booking-lounge-insert', 'bookingLounge_insert')->name("booking-lounge-insert");
    Route::get('/confirm-lounge/{id}', 'confirmLounge');
    Route::get('/payment-lounge/{id}', 'paymentLounge');
    Route::post('/payment-lounge-insert', 'paymentLounge_insert')->name("payment-lounge-insert");
    Route::post('/payment-lounge-verify', 'paymentLounge_verify')->name("payment-lounge-verify");
    Route::post('/load-time-slot', 'loadTimeSlot')->name("load-time-slot");
    Route::get('/payment-membership/{id}', 'paymentMembership');
    Route::post('/payment-membership-insert', 'paymentMembership_insert')->name("payment-membership-insert");
    Route::post('/payment-membership-verify', 'paymentMembership_verify')->name("payment-membership-verify");

    Route::get('/payment-success/{id}', 'paymentSuccess');
    Route::get('/payment-failed/{id}', 'paymentFailed');
    Route::get('/payment-expired', 'paymentExpired')->name('payment-expired');
    Route::post('/apply-discount', 'applyDiscount')->name('apply-discount');
    Route::post('/remove-discount', 'removeDiscount')->name('remove-discount');
});

Route::controller(HomeController::class)->group(function (){
    Route::get('/', 'index')->name('/');
    Route::get('/blogs','blog')->name('blog');
    Route::get('/blogs/{slug}', 'blogDetail');
    Route::get('/contact-us', 'contact')->name('contacts');
    Route::post('/contact-insert', 'contact_insert')->name('contact-insert');
    Route::get('/faqs', 'faqs')->name('faqs');
    Route::get('/membership', 'membership')->name('membership');
    Route::get('/book-lounge', 'bookLounge')->name('book-lounge');
    Route::get('/book-lounge/{slug}', 'bookLoungeDetail');
    Route::get('/become-partner', 'becomePartner')->name('become-partner');
    Route::post('/become-partner-insert', 'becomePartner_insert')->name('become-partner-insert');
    Route::get('/404', 'error404');
    Route::get('/{slug}', 'page');
});

