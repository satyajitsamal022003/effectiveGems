<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Couriertype;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Artisan;

class IndexController extends Controller
{
    public function optimize()
    {
        // Run optimization commands
        Artisan::call('optimize:clear'); // Clears all caches
        Artisan::call('config:cache');   // Cache the config files
        Artisan::call('route:cache');    // Cache the routes
        Artisan::call('view:cache');     // Cache the views
        Artisan::call('event:cache');    // Cache the events

        // Optionally return a response or redirect
        return response()->json(['message' => 'Application optimized successfully!']);
    }
    public function index()
    {
        $categories = Category::where('status', 1)->get();
        $popularproducts = Product::where('status', 1)->where('sortOrderPopular', 1)->paginate(8);
        return view('user.index', compact('categories', 'popularproducts'));
    }

    public function categorywiseproduct($id)
    {
        // Pagination setup
        $pageNo = request()->get('page', 1);
        $itemsPerPage = 8; // Number of items per page
        $toSkip = ($pageNo - 1) * $itemsPerPage;


        $subcategoriesCount = SubCategory::where('categoryId', $id)->where('status', 1)->count();


        // Fetch subcategory IDs
        $subcat = SubCategory::where('categoryId', $id)->where('status', 1)->get();
        $subcategoryIds = $subcat->pluck('id');

        // Find the max number of products to set the longest pagination length

        // Adjust subcategories pagination
        $subcategories = SubCategory::where('categoryId', $id)->where('status', 1)->get();
        // $subcategoriesMaxPage = ceil($subcategoriesCount / $itemsPerPage);

        // if ($pageNo <= $subcategoriesMaxPage) {
        //     // Normal pagination for subcategories
        //     $subcategories = $subcategories->skip($toSkip)->take($itemsPerPage)->get();
        // } else {
        //     // Repeat the last page of subcategories once the max page is reached
        //     $lastPageToSkip = ($subcategoriesMaxPage - 1) * $itemsPerPage;
        //     $subcategories = $subcategories->skip($lastPageToSkip)->take($itemsPerPage)->get();
        // }

        // Adjust main category products pagination
        // $maincategoryproducts = Product::where('categoryId', $id)->where('status', 1);
        // $maincategoryMaxPage = ceil($maincategoryproductsCount / $itemsPerPage);

        // if ($pageNo <= $maincategoryMaxPage) {
        //     // Normal pagination for main category products
        //     $maincategoryproducts = $maincategoryproducts->skip($toSkip)->take($itemsPerPage)->get();
        // } else {
        //     // Repeat the last page of main category products
        //     $lastPageToSkip = ($maincategoryMaxPage - 1) * $itemsPerPage;
        //     $maincategoryproducts = $maincategoryproducts->skip($lastPageToSkip)->take($itemsPerPage)->get();
        // }

        // Adjust subcategory products pagination
        // $subcategoryproducts = Product::whereIn('categoryId', $subcategoryIds)->orWhere('categoryId', $id)->where('status', 1)->paginate(8);
        
       $subcategoryproducts = Product::where('categoryId', $id)
        ->where('status', 1) // Filter by status
        ->orderByRaw("CASE WHEN subCategoryId IS NULL THEN 0 ELSE 1 END") // Main category products first
        ->paginate(8); // Paginate the result






        // Fetch the category details
        $category = Category::find($id);

        return view('user.category.product', compact(
            'subcategories',
            'category',
            'subcategoryproducts',
            'pageNo',
        ));
    }
    public function subCategory($id)
    {
        // Pagination setup
        $pageNo = request()->get('page', 1);
        $itemsPerPage = 8; // Number of items per page
        $toSkip = ($pageNo - 1) * $itemsPerPage;
        // $subcategoryproducts = Product::where('categoryId', $id)->orWhere('subCategoryId', $id)->where('status', 1)->paginate(8);
        $subcategoryproducts = Product::where('subCategoryId', $id)->where('status', 1)->paginate(8);
        $category = SubCategory::find($id);

        return view('user.category.subCategory', compact(
            'category',
            'subcategoryproducts',
        ));
    }



    public function productdetails($prodid)
    {

        $productdetails = Product::with('category')->where('id', $prodid)->first();

        $relatedProducts = Product::where('id', '!=', $prodid)->where('categoryId',$productdetails->categoryId)->where('status', 1)->paginate(8);
        $popularproducts = Product::where('status', 1)->where('sortOrderPopular', 1)->paginate(8);
        $variants = [];
        $couriertype = Couriertype::where('id', $productdetails->courierTypeId)->first();
        if ($productdetails->variant)
            $variants = Product::whereIn("id", json_decode($productdetails->variant))->pluck("productName");
        // dd(count($variants));
        return view('user.details.product', compact('productdetails', 'relatedProducts', 'popularproducts', 'variants', "couriertype"));
    }
    
    public function getProductsForCategory($categoryId)
    {
        // Fetch the category by ID
        $category = Category::find($categoryId);
    
        if ($category) {
            // Fetch products with the specified conditions
            $products = $category->products()
                ->where('status', 1)
                ->where('on_top', 1)
                ->get(); // Assuming the relationship is defined as a method in the Category model
    
            // Return a view snippet containing the products
            return view('user.partials.product_list', compact('products'))->render();
        }
    
        return response()->json(['error' => 'Category not found'], 404);
    }
    
     public function pages($id)
        {
            $current_page = Page::where('seoUrl', $id)->first();
    
            return view('user.pages', compact(['current_page']));
        }
        
        public function paymentsuccess(){
            return view('user.payment_success');
        }
        
        public function paymentfailed(){
            return view('user.payment_failed');
        }
}
