<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Couriertype;
use App\Models\Faq;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\Page;
use App\Models\Testimonial;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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



    public function getSuggestions(Request $request)
    {
        $query = $request->input('query');

        if ($query) {
            // Split the search term into individual words
            $words = explode(' ', $query);

            // Generate substrings of 3 consecutive characters
            $substrings = [];
            for ($i = 0; $i <= strlen($query) - 3; $i++) {
                $substrings[] = substr($query, $i, 3);
            }

            // Create the REGEXP pattern for substrings
            $pattern = implode('|', array_map(function ($substr) {
                return preg_quote($substr, '/');
            }, $substrings));

            // Query to search products
            $suggestions = Product::where(function ($q) use ($query, $words, $pattern) {
                // Priority 1: Exact full-term match
                $q->orWhere('productName', '=', $query);

                // Priority 2: Individual word match
                foreach ($words as $word) {
                    $q->orWhere('productName', 'LIKE', "%{$word}%");
                }

                // Priority 3: Substring match
                $q->orWhere('productName', 'REGEXP', $pattern);
            })
            ->orderByRaw("
                CASE
                    WHEN productName = ? THEN 1                       -- Exact full name match
                    WHEN productName LIKE ? THEN 2                    -- Contains full name
                    " . implode("\n", array_map(
                        fn($word, $index) => "WHEN productName LIKE '%{$word}%' THEN " . ($index + 3),
                        $words,
                        array_keys($words)
                    )) . "
                    ELSE 99                                           -- Substring matches
                END", [$query, "%{$query}%"])
            ->limit(10)
            ->get(['productName', 'id']);  // Adjust the fields as needed

            return response()->json($suggestions);
        }

        return response()->json([]);
    }

    public function searchProducts(Request $request)
    {
        // Retrieve the query parameters directly from the request
        $catId = $request->query('catId');
        $subCatId = $request->query('subCatId');
        $search = $request->query('search');

        // Check if catId or subCatId is provided, otherwise fall back to subCategory2
        if ($catId) {
            return $this->categorywiseproduct($catId, $search);
        } elseif ($subCatId) {
            return $this->subCategory($subCatId, $search);
        } else {
            return $this->subCategory2($search);
        }
    }

    public function index()
    {
        $testimonials = Testimonial::where('status', 1)->get();
        $categories = Category::where('status', 1)->orderByRaw("CASE WHEN sortOrder = 0 OR sortOrder IS NULL THEN 1 ELSE 0 END")
            ->orderBy('sortOrder', 'asc')->orderBy('created_at', 'asc')->get();
        $popularproducts = Product::where('status', 1)->where('sortOrderPopular', 1)->orderBy('sortOrderPopular', 'asc')->orderBy('created_at', 'asc')->paginate(16);
        $banners = Banner::where('status', 1)->orderBy('sort_order')->get();

        $faqs = Faq::where('is_active', 1)->get();

        return view('user.index', compact('categories', 'popularproducts', 'testimonials', 'banners', 'faqs'));
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
        $subcategories = SubCategory::where('categoryId', $id)
            ->where('status', 1)
            ->orderByRaw("CASE WHEN sortOrder = 0 OR sortOrder IS NULL THEN 1 ELSE 0 END")
            ->orderBy('sortOrder', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();


        // Fetch the products, order by main category products first, then by product id to ensure consistency
        $subcategoryproducts = Product::where('categoryId', $id)
            ->where('status', 1) // Filter by status
            ->orderByRaw("CASE WHEN subCategoryId IS NULL THEN 0 ELSE 1 END") // Main category products first
            ->orderByRaw("CASE WHEN sortOrder IS NULL OR sortOrder = 0 THEN 1 ELSE 0 END") // Place 0 or NULL sortOrder at the end
            ->orderBy('sortOrder', 'asc') // Then order by sortOrder
            ->orderBy('created_at', 'asc') // Finally order by created_at
            ->paginate(16); // Paginate the result

        // If there's a search query, modify the product search and apply the same ordering
        if ($search) {
            $subcategoryproducts = null;
            $searchWords = explode(' ', strtolower($search));

            // Generate substrings of length 3 from the search term
            $substrings = [];
            for ($i = 0; $i <= strlen($search) - 3; $i++) {
                $substrings[] = substr($search, $i, 3);
            }

            // Create a regex pattern from the substrings
            $pattern = implode('|', array_map(function ($substr) {
                return preg_quote($substr, '/');
            }, $substrings));

            // Condition 1: Exact match
            $subcategoryproducts = Product::where('status', 1)
                ->where('productName', 'LIKE', '%' . $search . '%')
                ->paginate(16);
            if ($subcategoryproducts->isNotEmpty()) {
                return view('user.category.searchProducts', compact('subcategoryproducts', 'search'));
            }

            // Condition 2: 3-character substring matches
            $subcategoryproducts = Product::where('status', 1)
                ->selectRaw("
                    *,
                    (
                        " . implode(' + ', array_map(function ($word, $index) {
                            return "IF(LOWER(productName) LIKE '{$word}%', " . (20 - $index) . ", 0)";
                        }, $searchWords, array_keys($searchWords))) . "
                        +
                        " . implode(' + ', array_map(function ($word) {
                            return "IF(LOWER(productName) LIKE '%{$word}%', 10, 0)";
                        }, $searchWords)) . "
                        +
                        " . implode(' + ', array_map(function ($substr) {
                            return "IF(LOWER(productName) LIKE '%{$substr}%', 5, 0)";
                        }, $substrings)) . "
                    ) AS relevance_score
                ")
                ->where(function ($query) use ($searchWords, $substrings) {
                    // Match full words
                    foreach ($searchWords as $word) {
                        $query->orWhere('productName', 'LIKE', '%' . $word . '%');
                    }
                    // Match substrings
                    foreach ($substrings as $substr) {
                        $query->orWhere('productName', 'LIKE', '%' . $substr . '%');
                    }
                })
                ->orderByDesc('relevance_score') // Order by highest relevance score
                ->paginate(16);
            if ($subcategoryproducts->isNotEmpty()) {
                return view('user.category.searchProducts', compact('subcategoryproducts', 'search'));
            }

            // Condition 3: Any word matches in any order
            $subcategoryproducts = Product::where('status', 1)
                ->where(function ($query) use ($substrings) {
                    foreach ($substrings as $substr) {
                        $query->orWhere('productName', 'LIKE', '%' . $substr . '%');
                    }
                })
                ->paginate(16);
            if ($subcategoryproducts->isNotEmpty()) {
                return view('user.category.searchProducts', compact('subcategoryproducts', 'search'));
            }

            // Condition 4: Combination of words in any order
            $subcategoryproducts = Product::where('status', 1)
                ->where(function ($query) use ($searchWords) {
                    foreach ($searchWords as $word1) {
                        foreach ($searchWords as $word2) {
                            if ($word1 !== $word2) {
                                $query->orWhere('productName', 'LIKE', '%' . $word1 . $word2 . '%');
                                $query->orWhere('productName', 'LIKE', '%' . $word2 . $word1 . '%');
                            }
                        }
                    }
                })
                ->paginate(16);
            if ($subcategoryproducts->isNotEmpty()) {
                return view('user.category.searchProducts', compact('subcategoryproducts', 'search'));
            }

            // Condition 5: Regex for 3-character substring match
            $subcategoryproducts = Product::where('status', 1)
                ->whereRaw("productName REGEXP '{$pattern}'")
                ->paginate(16);
            if ($subcategoryproducts->isNotEmpty()) {
                return view('user.category.searchProducts', compact('subcategoryproducts', 'search'));
            }

            // If no matches found, return empty results
            $subcategoryproducts = collect([]);
            return view('user.category.searchProducts', compact('subcategoryproducts', 'search'));
        }





        // Fetch the category details
        $category = Category::find($id);
        if ($category->seoUrl) {
            return redirect()->route('user.categorywiseproductSlug', ['slug' => $category->seoUrl], 301);
        }
        // Return the category product view with subcategories and products
        return view('user.category.product', compact(
            'subcategories',
            'category',
            'subcategoryproducts',
            'pageNo',
        ));
    }
    public function categorywiseproductSlug($slug, $search = null)
    {
        // Pagination setup
        $category = Category::where('seoUrl',$slug)->first();
        $pageNo = request()->get('page', 1);
        $itemsPerPage = 16; // Number of items per page
        $toSkip = ($pageNo - 1) * $itemsPerPage;

        // Fetch subcategory IDs
        $subcat = SubCategory::where('categoryId', $category->id)->where('status', 1)->get();
        $subcategoryIds = $subcat->pluck('id');

        // Fetch subcategories
        $subcategories = SubCategory::where('categoryId', $category->id)
            ->where('status', 1)
            ->orderByRaw("CASE WHEN sortOrder = 0 OR sortOrder IS NULL THEN 1 ELSE 0 END")
            ->orderBy('sortOrder', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();


        // Fetch the products, order by main category products first, then by product id to ensure consistency
        $subcategoryproducts = Product::where('categoryId', $category->id)
            ->where('status', 1) // Filter by status
            ->orderByRaw("CASE WHEN subCategoryId IS NULL THEN 0 ELSE 1 END") // Main category products first
            ->orderByRaw("CASE WHEN sortOrder IS NULL OR sortOrder = 0 THEN 1 ELSE 0 END") // Place 0 or NULL sortOrder at the end
            ->orderBy('sortOrder', 'asc') // Then order by sortOrder
            ->orderBy('created_at', 'asc') // Finally order by created_at
            ->paginate(16); // Paginate the result

        // If there's a search query, modify the product search and apply the same ordering
        if ($search) {
            $subcategoryproducts = Product::where('categoryId', $category->id)
                ->where('status', 1) // Filter by status
                ->whereRaw("MATCH(productName) AGAINST(? IN NATURAL LANGUAGE MODE)", [$search])
                ->orderByRaw("CASE WHEN subCategoryId IS NULL THEN 0 ELSE 1 END") // Main category products first
                ->orderByRaw("CASE WHEN sortOrder IS NULL OR sortOrder = 0 THEN 1 ELSE 0 END") // Place 0 or NULL sortOrder at the end
                ->orderBy('sortOrder', 'asc') // Then order by sortOrder
                ->orderBy('created_at', 'asc') // Finally order by created_at
                ->paginate(16); // Paginate the result

            // Return the search view with products
            return view('user.category.searchProducts', compact(
                'subcategoryproducts',
                'search'
            ));
        }

        // Fetch the category details

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
        $subcategoryproducts = Product::where('subCategoryId', $id)->where('status', 1)->orderByRaw("CASE WHEN sortOrderSubCategory = 0 OR sortOrderSubCategory IS NULL THEN 1 ELSE 0 END")->orderBy('sortOrderSubCategory')
            ->orderBy('created_at', 'asc')->paginate(16);
            if ($search) {
                $subcategoryproducts = null;
                $searchWords = explode(' ', strtolower($search));

                // Generate substrings of length 3 from the search term
                $substrings = [];
                for ($i = 0; $i <= strlen($search) - 3; $i++) {
                    $substrings[] = substr($search, $i, 3);
                }

                // Create a regex pattern from the substrings
                $pattern = implode('|', array_map(function ($substr) {
                    return preg_quote($substr, '/');
                }, $substrings));

                // Condition 1: Exact match
                $subcategoryproducts = Product::where('status', 1)
                    ->where('productName', 'LIKE', '%' . $search . '%')
                    ->paginate(16);
                if ($subcategoryproducts->isNotEmpty()) {
                    return view('user.category.searchProducts', compact('subcategoryproducts', 'search'));
                }

                // Condition 2: 3-character substring matches
                $subcategoryproducts = Product::where('status', 1)
                    ->selectRaw("
                        *,
                        (
                            " . implode(' + ', array_map(function ($word, $index) {
                                return "IF(LOWER(productName) LIKE '{$word}%', " . (20 - $index) . ", 0)";
                            }, $searchWords, array_keys($searchWords))) . "
                            +
                            " . implode(' + ', array_map(function ($word) {
                                return "IF(LOWER(productName) LIKE '%{$word}%', 10, 0)";
                            }, $searchWords)) . "
                            +
                            " . implode(' + ', array_map(function ($substr) {
                                return "IF(LOWER(productName) LIKE '%{$substr}%', 5, 0)";
                            }, $substrings)) . "
                        ) AS relevance_score
                    ")
                    ->where(function ($query) use ($searchWords, $substrings) {
                        // Match full words
                        foreach ($searchWords as $word) {
                            $query->orWhere('productName', 'LIKE', '%' . $word . '%');
                        }
                        // Match substrings
                        foreach ($substrings as $substr) {
                            $query->orWhere('productName', 'LIKE', '%' . $substr . '%');
                        }
                    })
                    ->orderByDesc('relevance_score') // Order by highest relevance score
                    ->paginate(16);
                if ($subcategoryproducts->isNotEmpty()) {
                    return view('user.category.searchProducts', compact('subcategoryproducts', 'search'));
                }

                // Condition 3: Any word matches in any order
                $subcategoryproducts = Product::where('status', 1)
                    ->where(function ($query) use ($substrings) {
                        foreach ($substrings as $substr) {
                            $query->orWhere('productName', 'LIKE', '%' . $substr . '%');
                        }
                    })
                    ->paginate(16);
                if ($subcategoryproducts->isNotEmpty()) {
                    return view('user.category.searchProducts', compact('subcategoryproducts', 'search'));
                }

                // Condition 4: Combination of words in any order
                $subcategoryproducts = Product::where('status', 1)
                    ->where(function ($query) use ($searchWords) {
                        foreach ($searchWords as $word1) {
                            foreach ($searchWords as $word2) {
                                if ($word1 !== $word2) {
                                    $query->orWhere('productName', 'LIKE', '%' . $word1 . $word2 . '%');
                                    $query->orWhere('productName', 'LIKE', '%' . $word2 . $word1 . '%');
                                }
                            }
                        }
                    })
                    ->paginate(16);
                if ($subcategoryproducts->isNotEmpty()) {
                    return view('user.category.searchProducts', compact('subcategoryproducts', 'search'));
                }

                // Condition 5: Regex for 3-character substring match
                $subcategoryproducts = Product::where('status', 1)
                    ->whereRaw("productName REGEXP '{$pattern}'")
                    ->paginate(16);
                if ($subcategoryproducts->isNotEmpty()) {
                    return view('user.category.searchProducts', compact('subcategoryproducts', 'search'));
                }

                // If no matches found, return empty results
                $subcategoryproducts = collect([]);
                return view('user.category.searchProducts', compact('subcategoryproducts', 'search'));
            }




        $category = SubCategory::find($id);
        $seoUrl = $category->seoUrl;
        if ($seoUrl) {
            return redirect()->route('user.subCategorySlug', ['slug' => $seoUrl], 301);
        }

        return view('user.category.subCategory', compact(
            'category',
            'subcategoryproducts',
        ));
    }
    public function subCategorySlug($slug, $search = null)
    {
        // Pagination setup
        $pageNo = request()->get('page', 1);
        $category = SubCategory::where('seoUrl', $slug)->first();
        $itemsPerPage = 16; // Number of items per page
        $toSkip = ($pageNo - 1) * $itemsPerPage;
        // $subcategoryproducts = Product::where('categoryId', $id)->orWhere('subCategoryId', $id)->where('status', 1)->paginate(8);
        $subcategoryproducts = Product::where('subCategoryId', $category->id)->where('status', 1)->orderByRaw("CASE WHEN sortOrderSubCategory = 0 OR sortOrderSubCategory IS NULL THEN 1 ELSE 0 END")->orderBy('sortOrderSubCategory')
            ->orderBy('created_at', 'asc')->paginate(16);
        if ($search) {
            $subcategoryproducts = Product::where('subCategoryId', $category->id)
                ->where('status', 1)
                ->whereRaw("MATCH(productName) AGAINST(? IN NATURAL LANGUAGE MODE)", [$search])
                ->orderByRaw("CASE WHEN sortOrderSubCategory = 0 OR sortOrderSubCategory IS NULL THEN 1 ELSE 0 END")->orderBy('sortOrderSubCategory')
                ->orderBy('created_at', 'asc')
                ->paginate(16);
            return view('user.category.searchProducts', compact(

                'subcategoryproducts',
                'search'
            ));
        }


        return view('user.category.subCategory', compact(
            'category',
            'subcategoryproducts',
        ));
    }
    public function subCategoryAjax($id, $search = null)
    {

        $category = SubCategory::where('categoryId', $id)->get();

        return response()->json($category);
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
        if ($search) {
            $subcategoryproducts = null;
            $searchWords = explode(' ', strtolower($search));

            // Generate substrings of length 3 from the search term
            $substrings = [];
            for ($i = 0; $i <= strlen($search) - 3; $i++) {
                $substrings[] = substr($search, $i, 3);
            }

            // Create a regex pattern from the substrings
            $pattern = implode('|', array_map(function ($substr) {
                return preg_quote($substr, '/');
            }, $substrings));

            // Condition 1: Exact match
            $subcategoryproducts = Product::where('status', 1)
                ->where('productName', 'LIKE', '%' . $search . '%')
                ->paginate(16);
            if ($subcategoryproducts->isNotEmpty()) {
                return view('user.category.searchProducts', compact('subcategoryproducts', 'search', 'category'));
            }

            // Condition 2: 3-character substring matches
            $subcategoryproducts = Product::where('status', 1)
            ->selectRaw("
                *,
                (
                    " . implode(' + ', array_map(function ($word, $index) {
                        return "IF(LOWER(productName) LIKE '{$word}%', " . (20 - $index) . ", 0)";
                    }, $searchWords, array_keys($searchWords))) . "
                    +
                    " . implode(' + ', array_map(function ($word) {
                        return "IF(LOWER(productName) LIKE '%{$word}%', 10, 0)";
                    }, $searchWords)) . "
                    +
                    " . implode(' + ', array_map(function ($substr) {
                        return "IF(LOWER(productName) LIKE '%{$substr}%', 5, 0)";
                    }, $substrings)) . "
                ) AS relevance_score
            ")
            ->where(function ($query) use ($searchWords, $substrings) {
                // Match full words
                foreach ($searchWords as $word) {
                    $query->orWhere('productName', 'LIKE', '%' . $word . '%');
                }
                // Match substrings
                foreach ($substrings as $substr) {
                    $query->orWhere('productName', 'LIKE', '%' . $substr . '%');
                }
            })
            ->orderByDesc('relevance_score') // Order by highest relevance score
            ->paginate(16);
            if ($subcategoryproducts->isNotEmpty()) {
                return view('user.category.searchProducts', compact('subcategoryproducts', 'search', 'category',));
            }

            // Condition 3: Any word matches in any order
            $subcategoryproducts = Product::where('status', 1)
                ->where(function ($query) use ($substrings) {
                    foreach ($substrings as $substr) {
                        $query->orWhere('productName', 'LIKE', '%' . $substr . '%');
                    }
                })
                ->paginate(16);
            if ($subcategoryproducts->isNotEmpty()) {
                return view('user.category.searchProducts', compact('subcategoryproducts', 'search', 'category',));
            }

            // Condition 4: Combination of words in any order
            $subcategoryproducts = Product::where('status', 1)
                ->where(function ($query) use ($searchWords) {
                    foreach ($searchWords as $word1) {
                        foreach ($searchWords as $word2) {
                            if ($word1 !== $word2) {
                                $query->orWhere('productName', 'LIKE', '%' . $word1 . $word2 . '%');
                                $query->orWhere('productName', 'LIKE', '%' . $word2 . $word1 . '%');
                            }
                        }
                    }
                })
                ->paginate(16);
            if ($subcategoryproducts->isNotEmpty()) {
                return view('user.category.searchProducts', compact('subcategoryproducts', 'search', 'category',));
            }

            // Condition 5: Regex for 3-character substring match
            $subcategoryproducts = Product::where('status', 1)
                ->whereRaw("productName REGEXP '{$pattern}'")
                ->paginate(16);
            if ($subcategoryproducts->isNotEmpty()) {
                return view('user.category.searchProducts', compact('subcategoryproducts', 'search', 'category',));
            }

            // If no matches found, return empty results
            $subcategoryproducts = collect([]);
            return view('user.category.searchProducts', compact('subcategoryproducts', 'search', 'category',));
        }



        return view('user.category.searchProducts', compact(
            'category',
            'subcategoryproducts',
            'search'
        ));
    }



    public function productdetails($prodid)
    {
        $productdetails = Product::with('category')->where('id', $prodid)->first();
        // dd($productdetails );
        $productSlug = $productdetails->seoUrl;


        $relatedProducts = Product::where('id', '!=', $prodid)->where('categoryId', $productdetails->categoryId)->where('status', 1)->orderBy('sortOrder', 'asc')->orderBy('created_at', 'asc')->paginate(16);
        $popularproducts = Product::where('status', 1)->where('sortOrderPopular', 1)->paginate(16);
        $variants = [];
        $couriertype = Couriertype::where('id', $productdetails->courierTypeId)->first();
        if ($productdetails->variant)
            $variants = Product::whereIn("id", json_decode($productdetails->variant))->select("variantName", "priceB2C", "productName")->get();
        // dd(count($variants));
        if ($productSlug) {
            return redirect()->route('user.productdetailsslug', ['slug' => $productSlug], 301);
        }
        return view('user.details.product', compact('productdetails', 'relatedProducts', 'popularproducts', 'variants', "couriertype"));
    }
    public function productdetailsSlug($slug)
    {

        $userId = Auth::guard('euser')->id();

        $productdetails = Product::with('category')->where('seoUrl', $slug)->orWhere('old_seo_url', $slug)->first();

        if (!$productdetails) {
            abort(404);
        }

        if ($productdetails->seoUrl !== $slug) {
            return redirect()->route('user.productdetailsslug', ['slug' => $productdetails->seoUrl], 301);
        }

        $isInWishlist = Wishlist::where('user_id', $userId)
        ->where('product_id', $productdetails->id)
        ->exists();
        $productSlug = $productdetails->seoUrl;


        $relatedProducts = Product::where('id', '!=', $productdetails->id)->where('categoryId', $productdetails->categoryId)->where('status', 1)->orderBy('sortOrder', 'asc')->orderBy('created_at', 'asc')->paginate(16);
        $popularproducts = Product::where('status', 1)->where('sortOrderPopular', 1)->paginate(16);
        $variants = [];
        $couriertype = Couriertype::where('id', $productdetails->courierTypeId)->first();
        if ($productdetails->variant)
            $variants = Product::whereIn("id", json_decode($productdetails->variant))->select("variantName", "priceB2C", "productName")->get();        // dd(count($variants));

        return view('user.details.product', compact('productdetails', 'relatedProducts', 'popularproducts', 'variants', "couriertype",'isInWishlist'));
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

    public function orderPlaced()
    {
        return view('user.payment_order_placed');
    }

    public function paymentfailed()
    {
        return view('user.payment_failed');
    }
}
