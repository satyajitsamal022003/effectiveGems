<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function addproduct()
    {
        $categories = Category::where('status', 1)->get();
        $products = Product::where('status', 1)->orderBy('id', 'desc')->get();
        return view('admin.products.add', compact('categories', 'products'));
    }

    public function listproduct()
    {
        $categories = Category::select('categoryName', 'id')->whereNotIn('status', [2])->get();
        return view('admin.products.list', compact('categories'));
    }

    public function getProductsData(Request $request)
    {
        $draw = intval($request->input('draw'));
        $length = intval($request->input('length'));
        $pageNo = intval($request->input('start'));
        $skip = $pageNo;

        $searchValue = $request->input('search.value');
        $category = $request->input('category');
        $subCategory = $request->input('subCategory');
        $sortByName = $request->input('sortByName') ?: 'asc';

        $query = Product::whereNotIn('status', [2]);

        if (!empty($searchValue)) {
            $query->where('productName', 'LIKE', '%' . $searchValue . '%');
        }
        if (!empty($category)) {
            $query->where('categoryId', $category);
        }
        if (!empty($subCategory)) {
            $query->where('subCategoryId', $subCategory);
        }

        $totalFilteredRecords = $query->count();
        $products = $query->orderBy('productName', $sortByName)
            ->skip($skip)
            ->take($length)
            ->get();

        $totalRecords = Product::count();

        $data = $products->map(function ($product, $key) use ($pageNo) {
            $product->DT_RowIndex = $pageNo + $key + 1;
            return $product;
        });

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFilteredRecords,
            'data' => $data
        ]);
    }

    public function storeproduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image3' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'variant' => 'nullable|array',
            'is_variant' => 'nullable',
            'productName' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $productData = $request->except(['_token']);
            $productData['status'] = 1;
            $productData['created_at'] = now();
            $productData['updated_at'] = now();

            $productData['icon'] = $this->uploadFile($request, 'icon', 'producticon');
            $productData['image1'] = $this->uploadFile($request, 'image1', 'product');
            $productData['image2'] = $this->uploadFile($request, 'image2', 'product');
            $productData['image3'] = $this->uploadFile($request, 'image3', 'product');

            $productData['is_variant'] = $request->is_variant ? 1 : 0;
            $productData['variant'] = $request->filled('variant') ? json_encode($request->variant) : null;

            $baseSeoUrl = Str::slug($request->productName);
            $seoUrl = $baseSeoUrl;
            $counter = 1;

            while (Product::where('seoUrl', $seoUrl)->exists()) {
                $seoUrl = "{$baseSeoUrl}-{$counter}";
                $counter++;
            }

            $productData['seoUrl'] = $seoUrl;

            Product::create($productData);

            return redirect()->route('admin.listproduct')->with('message', 'Product added successfully!');
        } catch (\Exception $e) {
            Log::error('Product creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create product. Please try again.');
        }
    }

    public function editproduct($id)
    {
        try {
            $categories = Category::where('status', 1)->get();
            $productsData = Product::where('status', 1)->orderBy('id', 'desc')->get();
            $product = Product::findOrFail($id);
            return view('admin.products.edit', compact('categories', 'productsData', 'product'));
        } catch (\Exception $e) {
            Log::error('Product edit page load failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load product edit page.');
        }
    }

    public function updateproduct(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image3' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'productName' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $product = Product::findOrFail($id);
            $productData = $request->except(['_token']);
            $productData['updated_at'] = now();

            // Handle images
            foreach (['icon' => 'producticon', 'image1' => 'product', 'image2' => 'product', 'image3' => 'product'] as $field => $path) {
                if ($request->hasFile($field)) {
                    $productData[$field] = $this->uploadFile($request, $field, $path);
                } else {
                    $productData[$field] = $product->{$field};
                }
            }

            $productData['is_variant'] = $request->is_variant ? 1 : 0;
            $productData['variant'] = $request->filled('variant') ? json_encode($request->variant) : null;

            $baseSeoUrl = Str::slug($request->productName);
            $seoUrl = $baseSeoUrl;
            $counter = 1;

            while (Product::where('seoUrl', $seoUrl)->where('id', '!=', $id)->exists()) {
                $seoUrl = "{$baseSeoUrl}-{$counter}";
                $counter++;
            }

            $productData['seoUrl'] = $seoUrl;
            $product->update($productData);

            return redirect()->route('admin.listproduct')->with('message', 'Product updated successfully!');
        } catch (\Exception $e) {
            Log::error('Product update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update product. Please try again.');
        }
    }

    public function updateProductPartly(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            $productData = $request->except(['_token']);
            $productData['updated_at'] = now();

            // Validate based on which step is being updated
            $validator = null;
            
            // Step 1: Basic Info
            if ($request->has('productName')) {
                $validator = Validator::make($request->all(), [
                    'productName' => 'required|string|max:255',
                    'categoryId' => 'required|exists:category,id',
                    'price_type' => 'required',
                    'priceB2C' => 'required|numeric',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validation failed',
                        'errors' => $validator->errors()
                    ], 422);
                }

                // If category exists, validate subcategory if provided
                if ($request->filled('subCategoryId')) {
                    $subCategoryValidator = Validator::make($request->all(), [
                        'subCategoryId' => 'exists:sub_category,id'
                    ]);

                    if ($subCategoryValidator->fails()) {
                        return response()->json([
                            'status' => false,
                            'message' => 'Invalid subcategory selected',
                            'errors' => $subCategoryValidator->errors()
                        ], 422);
                    }
                }
            }
            // Step 2: Images
            elseif ($request->hasFile('icon') || $request->hasFile('image1') || $request->hasFile('image2') || $request->hasFile('image3')) {
                $validator = Validator::make($request->all(), [
                    'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    'image1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    'image2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    'image3' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Image validation failed',
                        'errors' => $validator->errors()
                    ], 422);
                }
            }
            // Step 3: Description
            elseif ($request->has('productDesc1')) {
                $validator = Validator::make($request->all(), [
                    'productDesc1' => 'nullable|string',
                    'productDesc2' => 'nullable|string',
                    'productDesc3' => 'nullable|string',
                    'productDesc4' => 'nullable|string',
                    'productDesc5' => 'nullable|string',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Description validation failed',
                        'errors' => $validator->errors()
                    ], 422);
                }
            }

            // Handle file uploads if present
            foreach (['icon' => 'producticon', 'image1' => 'product', 'image2' => 'product', 'image3' => 'product'] as $field => $path) {
                if ($request->hasFile($field)) {
                    $productData[$field] = $this->uploadFile($request, $field, $path);
                } else {
                    unset($productData[$field]); // Don't update if no new file
                }
            }

            // Handle variant data
            if (isset($productData['is_variant'])) {
                $productData['is_variant'] = filter_var($productData['is_variant'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
            }
            if (isset($productData['variant'])) {
                $productData['variant'] = is_array($productData['variant']) ? json_encode($productData['variant']) : $productData['variant'];
            }

            // Handle SEO URL if product name is being updated
            if (isset($productData['productName'])) {
                $baseSeoUrl = Str::slug($productData['productName']);
                $seoUrl = $baseSeoUrl;
                $counter = 1;

                while (Product::where('seoUrl', $seoUrl)->where('id', '!=', $id)->exists()) {
                    $seoUrl = "{$baseSeoUrl}-{$counter}";
                    $counter++;
                }
                $productData['seoUrl'] = $seoUrl;
            }

            $product->update($productData);

            return response()->json([
                'status' => true,
                'message' => 'Product updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Product partial update failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to update product: ' . $e->getMessage()
            ], 500);
        }
    }

    private function uploadFile(Request $request, $fieldName, $path)
    {
        try {
            if ($request->hasFile($fieldName)) {
                $file = $request->file($fieldName);
                $fileName = time() . '_' . Str::random(10) . '_' . $file->getClientOriginalName();
                $file->move(public_path($path), $fileName);
                return $path . '/' . $fileName;
            }
        } catch (\Exception $e) {
            Log::error('File upload failed: ' . $e->getMessage());
            throw new \Exception('File upload failed: ' . $e->getMessage());
        }
        return null;
    }

    public function deleteproduct($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->status = 2;
            $product->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Product deleted successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Product deletion failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete product.'
            ], 500);
        }
    }

    public function productOnTop(Request $request)
    {
        try {
            $product = Product::findOrFail($request->productId);
            $product->on_top = $request->ontop;
            $product->save();

            return response()->json([
                'message' => 'Product on top updated successfully!',
                'success' => true
            ]);
        } catch (\Exception $e) {
            Log::error('Product on top update failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to update product on top status.',
                'success' => false
            ], 500);
        }
    }

    public function productOnStatus(Request $request)
    {
        try {
            $product = Product::findOrFail($request->productId);
            $product->status = $request->status;
            $product->save();

            return response()->json([
                'message' => 'Product status updated successfully!',
                'success' => true
            ]);
        } catch (\Exception $e) {
            Log::error('Product status update failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to update product status.',
                'success' => false
            ], 500);
        }
    }

    public function getSubCategory(Request $request)
    {
        try {
            $subcategories = SubCategory::where('categoryId', $request->categoryId)
                ->whereNotIn('status', [2])
                ->get();

            return response()->json([
                'message' => 'Sub Category',
                'success' => true,
                'data' => $subcategories
            ]);
        } catch (\Exception $e) {
            Log::error('Sub category fetch failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to fetch sub categories.',
                'success' => false
            ], 500);
        }
    }
}
