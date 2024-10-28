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
    public function searchProducts(Request $request)
    {
        $catId = $request->catId;
        $subCatId = $request->subCatId;
        $search = $request->search;
        if ($catId)
            return $this->categorywiseproduct($catId, $search);
        elseif ($subCatId)
            return $this->subCategory($subCatId, $search);
        else
            return $this->subCategory2($search);
    }
    public function index()
    {
        $categories = Category::where('status', 1)->orderBy('sortOrder', 'asc')->orderBy('created_at', 'asc')->get();
        $popularproducts = Product::where('status', 1)->where('sortOrderPopular', 1)->orderBy('sortOrderPopular', 'asc')->orderBy('created_at', 'asc')->paginate(16);
        return view('user.index', compact('categories', 'popularproducts'));
    }

    public function categorywiseproduct($id, $search = null)
    {
        // Pagination setup
        $pageNo = request()->get('page', 1);
        $itemsPerPage = 16; // Number of items per page
        $toSkip = ($pageNo - 1) * $itemsPerPage;

        // Fetch subcategory IDs
        $subcat = SubCategory::where('categoryId', $id)->where('status', 1)->get();
        $subcategoryIds = $subcat->pluck('id');

        // Fetch subcategories 
        $subcategories = SubCategory::where('categoryId', $id)->where('status', 1)->orderBy('sortOrder', 'asc')->orderBy('created_at', 'asc')->get();

        // Fetch the products, order by main category products first, then by product id to ensure consistency
        $subcategoryproducts = Product::where('categoryId', $id)
            ->where('status', 1) // Filter by status
            ->orderByRaw("CASE WHEN subCategoryId IS NULL THEN 0 ELSE 1 END") // Main category products first
            ->orderBy('sortOrder', 'asc')
            ->orderBy('created_at', 'asc')
            ->paginate(16); // Paginate the result

        // If there's a search query, modify the product search and apply the same ordering
        if ($search) {
            $subcategoryproducts = Product::where('categoryId', $id)
                ->where('status', 1) // Filter by status
                ->where('productName', 'like', '%' . $search . '%') // Search by product name
                ->orderByRaw("CASE WHEN subCategoryId IS NULL THEN 0 ELSE 1 END") // Main category products first
                ->orderBy('sortOrder', 'asc')
                ->orderBy('created_at', 'asc')
                ->paginate(16); // Paginate the result

            // Return the search view with products
            return view('user.category.searchProducts', compact(
                'subcategoryproducts',
                'search'
            ));
        }

        // Fetch the category details
        $category = Category::find($id);

        // Return the category product view with subcategories and products
        return view('user.category.product', compact(
            'subcategories',
            'category',
            'subcategoryproducts',
            'pageNo',
        ));
    }

    public function subCategory($id, $search = null)
    {
        // Pagination setup
        $pageNo = request()->get('page', 1);
        $itemsPerPage = 16; // Number of items per page
        $toSkip = ($pageNo - 1) * $itemsPerPage;
        // $subcategoryproducts = Product::where('categoryId', $id)->orWhere('subCategoryId', $id)->where('status', 1)->paginate(8);
        $subcategoryproducts = Product::where('subCategoryId', $id)->where('status', 1)->orderByRaw("CASE WHEN sortOrderSubCategory = 0 OR sortOrderSubCategory IS NULL THEN 1 ELSE 0 END")
            ->orderBy('created_at', 'asc')->paginate(16);
        if ($search) {
            $subcategoryproducts = Product::where('subCategoryId', $id)
                ->where('status', 1)
                ->where('productName', 'like', '%' . $search . '%')
                ->orderByRaw("CASE WHEN sortOrderSubCategory = 0 OR sortOrderSubCategory IS NULL THEN 1 ELSE 0 END")
                ->orderBy('created_at', 'asc')
                ->paginate(16);
            return view('user.category.searchProducts', compact(

                'subcategoryproducts',
                'search'
            ));
        }

        $category = SubCategory::find($id);

        return view('user.category.subCategory', compact(
            'category',
            'subcategoryproducts',
        ));
    }
    public function subCategory2($search = null)
    {
        // Pagination setup
        $pageNo = request()->get('page', 1);
        $itemsPerPage = 8; // Number of items per page
        $toSkip = ($pageNo - 1) * $itemsPerPage;
        // $subcategoryproducts = Product::where('categoryId', $id)->orWhere('subCategoryId', $id)->where('status', 1)->paginate(8);
        $subcategoryproducts = Product::where('status', 1)
            ->where('productName', 'like', '%' . $search . '%')
            ->paginate(16);

        $category = SubCategory::find(1);

        return view('user.category.searchProducts', compact(
            'category',
            'subcategoryproducts',
            'search'
        ));
    }



    public function productdetails($prodid)
    {

        $productdetails = Product::with('category')->where('id', $prodid)->first();

        $relatedProducts = Product::where('id', '!=', $prodid)->where('categoryId', $productdetails->categoryId)->where('status', 1)->orderBy('sortOrder', 'asc')->orderBy('created_at', 'asc')->paginate(16);
        $popularproducts = Product::where('status', 1)->where('sortOrderPopular', 1)->paginate(16);
        $variants = [];
        $couriertype = Couriertype::where('id', $productdetails->courierTypeId)->first();
        if ($productdetails->variant)
            $variants = Product::whereIn("id", json_decode($productdetails->variant))->select("variantName", "priceB2C")->get();
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
                ->orderBy('sortOrder', 'asc')
                ->orderBy('created_at', 'asc')
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

    public function paymentsuccess()
    {
        return view('user.payment_success');
    }

    public function paymentfailed()
    {
        return view('user.payment_failed');
    }
}
