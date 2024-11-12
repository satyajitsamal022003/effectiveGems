<?php

use App\Http\Controllers\Admin\ActivationController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CertificationController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CourierController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RazorpayController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\SiteMapController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\IndexController;
use App\Http\Controllers\User\OrderController;
use Illuminate\Support\Facades\Route;




// Group routes under 'admin' prefix and 'auth' middleware
Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    Route::resource('coupons', CouponController::class);
    Route::post('coupons-status-change', [CouponController::class, 'updateStatus'])->name('coupons.couponstatus');
    Route::resource('redirects', RedirectController::class);
    Route::post('redirects/status/update', [RedirectController::class, 'updateStatus'])->name('redirects.updateStatus');



    Route::resource('order', AdminOrderController::class);
    Route::get('/pending-orders-list', [AdminOrderController::class, 'pendingOrdersList'])->name('admin.order.pendingOrdersList');
    Route::get('/pending-orders-data', [AdminOrderController::class, 'getPendingOrdersData'])->name('admin.order.getPendingOrdersData');
    Route::get('/orders/data', [AdminOrderController::class, 'getOrdersData'])->name('admin.order.data');
    Route::post('/orders/changeStatus', [AdminOrderController::class, 'changeStatus'])->name('admin.order.changeStatus');
    // Admin dashboard route
    Route::post('/orders/request-customer', [AdminOrderController::class, 'requestToCustomer'])->name('admin.order.requestToCustomer');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Profile routes
    // Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // });

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
    Route::post('admin/getSubCategory', [ProductController::class, 'getSubCategory'])->name('admin.getSubCategory');
    Route::get('/product-edit/{id}', [ProductController::class, 'editproduct'])->name('admin.editproduct');
    Route::post('/update-product/{id}', [ProductController::class, 'updateproduct'])->name('admin.editproductdata');
    Route::post('/update-product-partly/{id}', [ProductController::class, 'updateProductPartly'])->name('admin.updateProductPartly');

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
    Route::get('/add-setting', [AdminOrderController::class, 'addsetting'])->name('admin.settings');
    Route::post('/store-setting', [AdminOrderController::class, 'storesetting'])->name('admin.storesettings');
});
Route::post('/apply-coupon', [CouponController::class, 'applyCoupon'])->name('coupon.applyCoupon');
Route::get('/', [IndexController::class, 'index'])->name('user.index');
Route::get('/category-products/{id}', [IndexController::class, 'categorywiseproduct'])->name('user.categorywiseproduct');
Route::get('/sub-category/{id}', [IndexController::class, 'subCategory'])->name('user.subCategory');
Route::get('/sub-category-ajax/{id}', [IndexController::class, 'subCategoryAjax'])->name('user.subCategoryAjax');
Route::get('/products-details/{prodid}', [IndexController::class, 'productdetails'])->name('user.productdetails');
Route::get('/layout-category-products/{id}', [IndexController::class, 'getProductsForCategory']);
Route::get('/searchProduct', [IndexController::class, 'searchProducts'])->name('searchProducts');


// Cart 

Route::get('/cart', [CartController::class, 'index']);
Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('addToCart');
Route::post('/remove-from-cart', [CartController::class, 'removeFromCart'])->name('removeFromCart');
Route::post('/change-quantity', [CartController::class, 'changeQuantity'])->name('changeQuantity');
Route::post('/change-settings', [CartController::class, 'changeAddSettings'])->name('changeAddSettings');
Route::get('/view-cart', [CartController::class, 'viewCart'])->name('viewCart');

// RazorPay 
Route::get('razorpay', [RazorpayController::class, 'createOrder'])->name('razorpay.order');
Route::post('razorpay-payment', [RazorpayController::class, 'storePayment'])->name('razorpay.payment.store');
Route::post('razorpay-callback', [RazorpayController::class, 'paymentCallback'])->name('razorpay.callback');
Route::get('razorpay-test', [RazorpayController::class, 'testRazorpayCredentials'])->name('razorpay.testRazorpayCredentials');

Route::get('optimize', [IndexController::class, 'optimize'])->name('user.optmize');
Route::get('/pages/{url}', [IndexController::class, 'pages'])->name('pages');

//success and failed
Route::get('/payment-success', [IndexController::class, 'paymentsuccess'])->name('payment.success');
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
Route::get('sitemap/subcategory-images.xml', [SiteMapController::class, 'subCategoryImages'])->name('sitemap.subCategoryImages');
Route::get('sitemap/products-images/page/{page}.xml', [SiteMapController::class, 'productImagesPage'])->name('sitemap.product-images.page');
Route::get('sitemap/category-images/page/{page}.xml', [SiteMapController::class, 'categoryImagesPage'])->name('sitemap.category-images.page');
Route::get('sitemap/subcategory-images/page/{page}.xml', [SiteMapController::class, 'subCategoryImagesPage'])->name('sitemap.subcategory-images.page');




// Include authentication routes
require __DIR__ . '/auth.php';
