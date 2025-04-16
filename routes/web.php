<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\Admin\ActivationController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CertificationController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CourierController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ManageCartController;
use App\Http\Controllers\Admin\ManageWishlistController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PagesController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\EuserForgotPasswordController;
use App\Http\Controllers\eusers\EuserController;
use App\Http\Controllers\eusers\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RazorpayController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\SiteMapController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\IndexController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;


// Group routes under 'admin' prefix and 'auth' middleware
Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    Route::get('/banners', [BannerController::class, 'index'])->name('admin.banners.index');
    Route::get('/banners/create', [BannerController::class, 'create'])->name('admin.banners.create');
    Route::post('/banners', [BannerController::class, 'store'])->name('admin.banners.store');
    Route::get('/banners/{banner}/edit', [BannerController::class, 'edit'])->name('admin.banners.edit');
    Route::put('/banners/{banner}', [BannerController::class, 'update'])->name('admin.banners.update');
    Route::delete('/banners/{banner}', [BannerController::class, 'destroy'])->name('admin.banners.destroy');
    Route::post('/banners/{banner}/update-status', [BannerController::class, 'updateStatus'])->name('admin.banners.updateStatus');
    Route::post('/banners/update-order', [BannerController::class, 'updateOrder'])->name('admin.banners.updateOrder');
    Route::resource('coupons', CouponController::class);
    Route::post('coupons-status-change', [CouponController::class, 'updateStatus'])->name('coupons.couponstatus');
    Route::resource('redirects', RedirectController::class);
    Route::post('redirects/status/update', [RedirectController::class, 'updateStatus'])->name('redirects.updateStatus');


    Route::get('/pages', [PagesController::class, 'index'])->name('admin.pages.index');
    Route::get('/pages/{pages}/edit', [PagesController::class, 'edit'])->name('admin.pages.edit');
    Route::put('/pages/{pages}', [PagesController::class, 'update'])->name('admin.pages.update');
    Route::post('admin/pageStatus', [PagesController::class, 'updateStatus'])->name('admin.pages.updateStatus');



    Route::resource('order', AdminOrderController::class);
    Route::get('/cash-on-delivery-list', [AdminOrderController::class, 'cashOnDeliveryList'])->name('admin.order.cashOnDeliveryList');
    Route::get('/pending-orders-list', [AdminOrderController::class, 'pendingOrdersList'])->name('admin.order.pendingOrdersList');
    Route::get('/pending-orders-data', [AdminOrderController::class, 'getPendingOrdersData'])->name('admin.order.getPendingOrdersData');
    Route::get('/orders/data', [AdminOrderController::class, 'getOrdersData'])->name('admin.order.data');
    Route::get('/orders/cod/data', [AdminOrderController::class, 'getCODOrdersData'])->name('admin.cod.order.data');
    Route::post('/orders/changeStatus', [AdminOrderController::class, 'changeStatus'])->name('admin.order.changeStatus');
    Route::post('/orders/add-payment-details', [AdminOrderController::class, 'addPaymentDetails'])->name('admin.order.addPaymentDetails');
    // Admin dashboard route
    Route::post('/orders/request-customer', [AdminOrderController::class, 'requestToCustomer'])->name('admin.order.requestToCustomer');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Profile routes
    // Route::middleware('auth')->group(function () {
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // });

    Route::get('/profile', [DashboardController::class, 'aprofile'])->name('admin.aprofile');
    Route::post('/update-profile', [DashboardController::class, 'update'])->name('admin.profile.update');

    Route::get('/change-password', [DashboardController::class, 'showChangePasswordForm'])->name('admin.showChangePasswordForm');
    Route::post('/update-password', [DashboardController::class, 'updatePassword'])->name('admin.updatePassword');


    //category
    Route::get('/add-category', [CategoryController::class, 'addcat'])->name('admin.addcat');
    Route::post('/store-category', [CategoryController::class, 'storecat'])->name('admin.storecat');
    Route::get('/category-list', [CategoryController::class, 'listcat'])->name('admin.listcat');
    Route::get('/category-edit/{id}', [CategoryController::class, 'editcat'])->name('admin.editcat');
    Route::post('/update-categor/{id}', [CategoryController::class, 'updatecat'])->name('admin.updatecat');
    Route::delete('/delete-categor/{id}', [CategoryController::class, 'deletecat'])->name('admin.deleteecat');
    Route::post('admin/toggleOnTop', [CategoryController::class, 'toggleOnTop'])->name('admin.toggleOnTop');
    Route::post('admin/toggleOnFooter', [CategoryController::class, 'toggleOnFooter'])->name('admin.toggleOnFooter');
    Route::post('admin/toggleOnStatus', [CategoryController::class, 'toggleOnStatus'])->name('admin.toggleOnStatus');

    //Testimonial
    Route::get('/add-testimonial', [TestimonialController::class, 'addtestimonial'])->name('admin.addtestimonial');
    Route::post('/store-testimonial', [TestimonialController::class, 'storetestimonial'])->name('admin.storetestimonial');
    Route::get('/testimonial-list', [TestimonialController::class, 'listtestimonial'])->name('admin.listtestimonial');
    Route::get('/testimonial-edit/{id}', [TestimonialController::class, 'edittestimonial'])->name('admin.edittestimonial');
    Route::post('/update-testimonial/{id}', [TestimonialController::class, 'updatetestimonial'])->name('admin.updatetestimonial');
    Route::delete('/delete-testimonial/{id}', [TestimonialController::class, 'deletetestimonial'])->name('admin.deletetestimonial');
    Route::post('testimonial/toggleOnStatus', [TestimonialController::class, 'toggleOnStatus'])->name('testimonial.toggleOnStatus');


    //subcategory
    Route::get('/list-subcat', [SubCategoryController::class, 'listsubcat'])->name('admin.listsubcat');
    Route::get('/add-subcat', [SubCategoryController::class, 'addsubcat'])->name('admin.addsubcat');
    Route::post('/store-subcat', [SubCategoryController::class, 'storesubcat'])->name('admin.storesubcat');
    Route::get('/edit-subcat/{id}', [SubCategoryController::class, 'editSubcat'])->name('admin.editSubcat');
    Route::post('/update-subcat/{id}', [SubCategoryController::class, 'updateSubcat'])->name('admin.updateSubcat');
    Route::delete('/delete-subcat/{id}', [SubCategoryController::class, 'deletesubcat'])->name('admin.deletesubcat');
    Route::post('admin/subcategoryOnTop', [SubCategoryController::class, 'subcategoryOnTop'])->name('admin.subcategoryOnTop');
    Route::post('admin/subcategoryOnFooter', [SubCategoryController::class, 'subcategoryOnFooter'])->name('admin.subcategoryOnFooter');
    Route::post('admin/subcategoryStatus', [SubCategoryController::class, 'subcategoryStatus'])->name('admin.subcategoryStatus');

    //Products
    Route::get('/products/data', [ProductController::class, 'getProductsData'])->name('admin.products.data');
    Route::get('/add-product', [ProductController::class, 'addproduct'])->name('admin.addproduct');
    Route::post('/store-product', [ProductController::class, 'storeproduct'])->name('admin.storeproduct');
    Route::get('/products-list', [ProductController::class, 'listproduct'])->name('admin.listproduct');
    Route::delete('/delete-product/{id}', [ProductController::class, 'deleteproduct'])->name('admin.deleteproduct');
    Route::post('admin/productOnTop', [ProductController::class, 'productOnTop'])->name('admin.productOnTop');
    Route::post('admin/productOnStatus', [ProductController::class, 'productOnStatus'])->name('admin.productOnStatus');
    Route::post('admin/sortOrderPopularStatus', [ProductController::class, 'sortOrderPopularStatus'])->name('admin.sortOrderPopularStatus');
    Route::post('admin/getSubCategory', [ProductController::class, 'getSubCategory'])->name('admin.getSubCategory');
    Route::get('/product-edit/{id}', [ProductController::class, 'editproduct'])->name('admin.editproduct');
    Route::post('/update-product/{id}', [ProductController::class, 'updateproduct'])->name('admin.editproductdata');
    Route::post('/update-product-partly/{id}', [ProductController::class, 'updateProductPartly'])->name('admin.updateProductPartly');
    Route::post('/update-product-images/{id}', [ProductController::class, 'updateProductImages'])->name('admin.updateProductImages');

    //Faqs
    Route::get('/faqs-list', [FaqController::class, 'listfaq'])->name('admin.listfaq');
    Route::get('/add-faq', [FaqController::class, 'addfaq'])->name('admin.addfaq');
    Route::post('/store-faq', [FaqController::class, 'storefaq'])->name('admin.storefaq');
    Route::delete('/delete-faq/{id}', [FaqController::class, 'deletefaq'])->name('admin.deletefaq');
    Route::post('admin/faqOnStatus', [FaqController::class, 'faqOnStatus'])->name('admin.faqOnStatus');
    Route::get('/faq-edit/{id}', [FaqController::class, 'editfaq'])->name('admin.editfaq');
    Route::post('/update-faq/{id}', [FaqController::class, 'updatefaq'])->name('admin.editfaqdata');

    // Follow wishlist routes
    Route::get('/wishlist', [ManageWishlistController::class, 'aWishlistData'])->name('admin.aWishlistData');
    Route::get('/admin/wishlist/data', [ManageWishlistController::class, 'getWishlistData'])->name('admin.wishlist.data');
    Route::get('/wishlist-list/{product_id}', [ManageWishlistController::class, 'WishlistData'])->name('admin.wishlist.details');
    Route::post('/wishlistOnStatus', [ManageWishlistController::class, 'wishlistOnStatus'])->name('admin.wishlistOnStatus');

    Route::post('/admin/wishlist/delete', [ManageWishlistController::class, 'deleteWishlist'])->name('admin.wishlist.delete');
    Route::post('/admin/wishlist/massDelete', [ManageWishlistController::class, 'massDeleteWishlist'])->name('admin.wishlist.massDelete');

    // Follow Cart routes
    Route::get('/cart', [ManageCartController::class, 'aCartData'])->name('admin.aCartData');
    Route::get('/admin/cart/data', [ManageCartController::class, 'getCartData'])->name('admin.cart.data');
    Route::get('/cart-list/{product_id}', [ManageCartController::class, 'CartData'])->name('admin.cart.details');
    Route::post('/cartOnStatus', [ManageCartController::class, 'cartOnStatus'])->name('admin.cartOnStatus');

    Route::post('/admin/cart/delete', [ManageCartController::class, 'deleteCart'])->name('admin.cart.delete');
    Route::post('/admin/cart/massDelete', [ManageCartController::class, 'massDeleteCart'])->name('admin.cart.massDelete');


    //activation
    Route::get('/add-activation', [ActivationController::class, 'addactivation'])->name('admin.addactivation');
    Route::post('/store-activation', [ActivationController::class, 'storeactivation'])->name('admin.storeactivation');
    Route::get('/activations-list', [ActivationController::class, 'listactivation'])->name('admin.listactivation');
    Route::post('activations/status', [ActivationController::class, 'activationstatus'])->name('admin.activationstatus');
    Route::get('/activations-edit/{id}', [ActivationController::class, 'editactivation'])->name('admin.editactivation');
    Route::get('/delete-activations/{id}', [ActivationController::class, 'deleteactivation'])->name('admin.deleteactivation');
    Route::post('/update-activations/{id}', [ActivationController::class, 'updateactivation'])->name('admin.updateactivation');

    //Courier types
    Route::get('/add-couriertype', [CourierController::class, 'addcouriertype'])->name('admin.addcouriertype');
    Route::post('/store-couriertype', [CourierController::class, 'storecouriertype'])->name('admin.storecouriertype');
    Route::get('/couriertypes-list', [CourierController::class, 'listcouriertype'])->name('admin.listcouriertype');
    Route::post('couriertypes/status', [CourierController::class, 'couriertypestatus'])->name('admin.couriertypestatus');
    Route::get('/couriertypes-edit/{id}', [CourierController::class, 'editcouriertype'])->name('admin.editcouriertype');
    Route::get('/delete-couriertypes/{id}', [CourierController::class, 'deletecouriertype'])->name('admin.deletecouriertype');
    Route::post('/update-couriertypes/{id}', [CourierController::class, 'updatecouriertype'])->name('admin.updatecouriertype');

    //certifications
    Route::get('/add-certification', [CertificationController::class, 'addcertification'])->name('admin.addcertification');
    Route::post('/store-certification', [CertificationController::class, 'storecertification'])->name('admin.storecertification');
    Route::get('/certifications-list', [CertificationController::class, 'listcertification'])->name('admin.listcertification');
    Route::post('certifications/status', [CertificationController::class, 'certificationstatus'])->name('admin.certificationstatus');
    Route::get('/certifications-edit/{id}', [CertificationController::class, 'editcertification'])->name('admin.editcertification');
    Route::get('/delete-certifications/{id}', [CertificationController::class, 'deletecertification'])->name('admin.deletecertification');
    Route::post('/update-certifications/{id}', [CertificationController::class, 'updatecertification'])->name('admin.updatecertification');

    //settings
    Route::get('/edit-setting', [AdminOrderController::class, 'addsetting'])->name('admin.settings');
    Route::post('/settings/toggle-status', [AdminOrderController::class, 'toggleStatus'])->name('settings.toggleStatus');
    Route::post('/store-setting', [AdminOrderController::class, 'storesetting'])->name('admin.storesettings');
    
});
Route::post('/apply-coupon', [CouponController::class, 'applyCoupon'])->name('coupon.applyCoupon');
Route::get('/', [IndexController::class, 'index'])->name('user.index');
Route::get('/category-products/{id}', [IndexController::class, 'categorywiseproduct'])->name('user.categorywiseproduct');
Route::get('/popular-products', [IndexController::class, 'popularproducts'])->name('user.popularproducts');
Route::get('/all-testimonials', [IndexController::class, 'testimonials'])->name('user.testimonials');
Route::get('/faqs', [IndexController::class, 'faqs'])->name('user.faqs');
Route::get('/category-product/{slug}', [IndexController::class, 'categorywiseproductSlug'])->name('user.categorywiseproductSlug');
Route::get('/sub-category/{id}', [IndexController::class, 'subCategory'])->name('user.subCategory');
Route::get('/sub-categories/{slug}', [IndexController::class, 'subCategorySlug'])->name('user.subCategorySlug');
Route::get('/sub-category-ajax/{id}', [IndexController::class, 'subCategoryAjax'])->name('user.subCategoryAjax');
Route::get('/products-details/{prodid}', [IndexController::class, 'productdetails'])->name('user.productdetails');
Route::get('/products-detail/{slug}', [IndexController::class, 'productdetailsSlug'])->name('user.productdetailsslug');
Route::get('/layout-category-products/{id}', [IndexController::class, 'getProductsForCategory']);
Route::get('/searchProduct', [IndexController::class, 'searchProducts'])->name('searchProducts');
Route::get('/api/get-suggestions', [IndexController::class, 'getSuggestions'])->name('api.suggestions');


// Cart

Route::get('/cart', [CartController::class, 'index']);
Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('addToCart');
Route::post('/remove-from-cart', [CartController::class, 'removeFromCart'])->name('removeFromCart');
Route::post('/change-quantity', [CartController::class, 'changeQuantity'])->name('changeQuantity');
Route::post('/change-settings', [CartController::class, 'changeAddSettings'])->name('changeAddSettings');
Route::get('/view-cart', [CartController::class, 'viewCart'])->name('viewCart');
Route::post('/destroy-coupon', [CartController::class, 'destroyCoupon'])->name('destroyCoupon');
Route::post('/set-cod-session', function (\Illuminate\Http\Request $request) {
    if ($request->isCOD == 'true' || $request->isCOD === true) {
        session(['isCOD' => true]);
    } else {
        session()->forget('isCOD');
        session()->forget('cod_applied'); // Remove cod charge if it was applied
    }
    return response()->json(['status' => 'updated']);
});


// RazorPay
Route::get('razorpay', [RazorpayController::class, 'createOrder'])->name('razorpay.order');
Route::post('razorpay-payment', [RazorpayController::class, 'storePayment'])->name('razorpay.payment.store');
Route::post('razorpay-callback', [RazorpayController::class, 'paymentCallback'])->name('razorpay.callback');
Route::get('razorpay-test', [RazorpayController::class, 'testRazorpayCredentials'])->name('razorpay.testRazorpayCredentials');

Route::get('optimize', [IndexController::class, 'optimize'])->name('user.optmize');
Route::get('/pages/{url}', [IndexController::class, 'pages'])->name('pages');

//success and failed
Route::get('/payment-success', [IndexController::class, 'paymentsuccess'])->name('payment.success');
Route::get('/order-placed', [IndexController::class, 'orderPlaced'])->name('order.placed');
Route::get('/payment-failed', [IndexController::class, 'paymentfailed'])->name('payment.failed');

Route::resource('checkout', OrderController::class);

//order request and cancel
Route::post('/request-to-customer', [AdminOrderController::class, 'requestToCustomer'])->name('order.request');
Route::post('/store-courier-details', [AdminOrderController::class, 'courierdetails'])->name('order.courierdetails');
Route::post('/store-delivery-details', [AdminOrderController::class, 'deliverydetails'])->name('order.deliverydetails');
Route::post('/invoice-upload', [AdminOrderController::class, 'invoiceupload'])->name('order.invoiceupload');
Route::get('/order/accept/{id}', [AdminOrderController::class, 'acceptOrder'])->name('order.accept');
Route::get('/order/cancel/{id}', [AdminOrderController::class, 'cancelOrder'])->name('order.cancel');
Route::get('/get-courier-name', [AdminOrderController::class, 'getCourierName'])->name('order.getCourierName');

// SITEMAPS
Route::get('sitemap.xml', [SiteMapController::class, 'index'])->name('sitemap.index');
Route::get('sitemap/categories.xml', [SiteMapController::class, 'categories'])->name('sitemap.categories');
Route::get('sitemap/subcategories.xml', [SiteMapController::class, 'subcategories'])->name('sitemap.subcategories');
Route::get('sitemap/footer-pages.xml', [SiteMapController::class, 'footerPages'])->name('sitemap.footer-pages');
Route::get('sitemap/products.xml', [SiteMapController::class, 'products'])->name('sitemap.products');
Route::get('sitemap/product-images.xml', [SiteMapController::class, 'productImages'])->name('sitemap.productImages');
Route::get('sitemap/category-images.xml', [SiteMapController::class, 'categoryImages'])->name('sitemap.categoryImages');
Route::get('sitemap/products/page/{page}.xml', [SiteMapController::class, 'productsPage'])->name('sitemap.products.page');
Route::get('sitemap/subcategory-images.xml', [SiteMapController::class, 'subCategoryImages'])->name('sitemap.subCategoryImages');
Route::get('sitemap/products-images/page/{page}.xml', [SiteMapController::class, 'productImagesPage'])->name('sitemap.product-images.page');
Route::get('sitemap/category-images/page/{page}.xml', [SiteMapController::class, 'categoryImagesPage'])->name('sitemap.category-images.page');
Route::get('sitemap/subcategory-images/page/{page}.xml', [SiteMapController::class, 'subCategoryImagesPage'])->name('sitemap.subcategory-images.page');




//User panel

Route::group(['prefix' => 'user'], function () {
    Route::get('/sign-up', function () {
        return view('eusers.register');
    })->name('eusers.signup');

    Route::post('/post-sign-up', [RegisterController::class, 'postsignup'])->name('eusers.postsignup');
    Route::post('/send-otp', [RegisterController::class, 'sendOtp']);
    Route::post('/verify-otp', [RegisterController::class, 'verifyOtp']);

    Route::post('/checkout-send-otp', [OrderController::class, 'sendOtp']);
    Route::post('/checkout-verify-otp', [OrderController::class, 'verifyOtp']);

    Route::get('/sign-in', function () {
        return view('eusers.login');
    })->name('eusers.login');


    Route::post('/post-sign-in', [EuserController::class, 'postsignin'])->name('eusers.postsignin');
    Route::post('/logout', [EuserController::class, 'logout'])->name('euser.logout');

    Route::middleware('euser.auth')->group(function () {
        Route::get('/dashboard', [EuserController::class, 'dashboard'])->name('euser.dashboard');
        Route::get('/my-orders', [EuserController::class, 'myorderlist'])->name('euser.myorderlist');
        Route::get('/orders-view/{id}', [EuserController::class, 'ordersview'])->name('euser.ordersview');
        Route::get('/my-wishlist', [WishlistController::class, 'index'])->name('euser.wishlist');
        Route::post('/add-to-wishlist', [WishlistController::class, 'store'])->name('euser.wishlist-add');
        Route::post('/move-to-wishlist', [WishlistController::class, 'moveto'])->name('euser.wishlist-move');
        Route::post('/remove-from-wishlist', [WishlistController::class, 'destroy'])->name('euser.wishlist-destroy');
        Route::get('/my-profile', [ProfileController::class, 'myProfile'])->name('euser.myProfile');
        Route::get('/setting', [ProfileController::class, 'Setting'])->name('euser.setting');
        Route::post('/update-profile', [ProfileController::class, 'updateProfile'])->name('euser.updateProfile');
        Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('euser.changePassword');
        Route::get('/manage-address', [AddressController::class, 'manageAddress'])->name('euser.manageaddress');
        Route::post('/store-address', [AddressController::class, 'storeAddress'])->name('euser.address.store');
        Route::get('/edit-address/{id}', [AddressController::class, 'editAddress'])->name('euser.address.edit');
        Route::put('/update-address/{id}', [AddressController::class, 'updateAddress'])->name('euser.address.update');
        Route::get('/api/address/{id}', [AddressController::class, 'getAddress'])->name('euser.address.getaddress');
        Route::delete('/delete-address/{id}', [AddressController::class, 'destroy'])->name('euser.address.destroy');
    });
});

Route::get('user/forgot-password', [EuserForgotPasswordController::class, 'showForgotPasswordForm'])->name('user.password.request');
Route::post('user/forgot-password', [EuserForgotPasswordController::class, 'sendResetLink'])->name('user.password.email');
Route::get('user/reset-password/{token}', [EuserForgotPasswordController::class, 'showResetPasswordForm'])->name('user.password.reset');
Route::post('user/reset-password', [EuserForgotPasswordController::class, 'resetPassword'])->name('user.password.update');



// Include authentication routes
require __DIR__ . '/auth.php';
