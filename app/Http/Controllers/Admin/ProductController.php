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
        try {
            // Basic validation
            $validator = Validator::make($request->all(), [
                'productName' => 'required|string|max:255',
                'categoryId' => 'required|exists:category,id',
                'price_type' => 'required|string',
                'priceB2C' => 'required|numeric|min:0',
                'priceMRP' => 'nullable|numeric|min:0',
                'priceB2B' => 'nullable|numeric|min:0',
                'min_product_qty' => 'nullable|numeric|min:0',
                'max_product_qty' => 'nullable|numeric|min:0',
                'sortOrder' => 'nullable|integer|min:0',
                'sortOrderCategory' => 'nullable|integer|min:0',
                'sortOrderSubCategory' => 'nullable|integer|min:0',
                'sortOrderPopular' => 'nullable|integer|min:0',
                'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'image1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'image2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'image3' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'variant' => 'nullable|array',
                'is_variant' => 'nullable|boolean',
                'subCategoryId' => 'nullable|exists:sub_category,id',
                'certificationId' => 'nullable|exists:certifications,id',
                'activationId' => 'nullable|exists:activations,id',
                'courierTypeId' => 'nullable|exists:couriertypes,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Additional validation for product range
            if ($request->filled('min_product_qty') && $request->filled('max_product_qty')) {
                if ($request->min_product_qty >= $request->max_product_qty) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors' => ['min_product_qty' => ['Lower product range must be less than higher product range']]
                    ], 422);
                }
            }

            // Prepare product data
            $productData = $request->except(['_token']);
            $productData['status'] = 1;
            $productData['created_at'] = now();
            $productData['updated_at'] = now();

            // Handle file uploads
            foreach (['icon' => 'producticon', 'image1' => 'product', 'image2' => 'product', 'image3' => 'product'] as $field => $path) {
                try {
                    if ($request->hasFile($field)) {
                        // Ensure upload directory exists
                        $uploadPath = public_path($path);
                        if (!file_exists($uploadPath)) {
                            mkdir($uploadPath, 0755, true);
                        }
                        
                        $productData[$field] = $this->uploadFile($request, $field, $path);
                    }
                } catch (\Exception $e) {
                    Log::error("File upload failed for {$field}: " . $e->getMessage());
                    return response()->json([
                        'success' => false,
                        'message' => "Failed to upload {$field}",
                        'errors' => [$field => [$e->getMessage()]]
                    ], 500);
                }
            }

            // Handle variant data
            $productData['is_variant'] = filter_var($request->is_variant, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
            $productData['variant'] = $request->filled('variant') ? json_encode($request->variant) : null;

            // Generate SEO URL
            $baseSeoUrl = Str::slug($request->productName);
            $seoUrl = $baseSeoUrl;
            $counter = 1;

            while (Product::where('seoUrl', $seoUrl)->exists()) {
                $seoUrl = "{$baseSeoUrl}-{$counter}";
                $counter++;
            }
            $productData['seoUrl'] = $seoUrl;

            // Create product
            $product = Product::create($productData);

            return response()->json([
                'success' => true,
                'message' => 'Product added successfully!',
                'data' => $product
            ]);

        } catch (\Exception $e) {
            Log::error('Product creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create product: ' . $e->getMessage()
            ], 500);
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
        try {
            // Basic validation
            $validator = Validator::make($request->all(), [
                'productName' => 'required|string|max:255',
                'categoryId' => 'required|exists:category,id',
                'price_type' => 'required|string',
                'priceB2C' => 'required|numeric|min:0',
                'priceMRP' => 'nullable|numeric|min:0',
                'priceB2B' => 'nullable|numeric|min:0',
                'min_product_qty' => 'nullable|numeric|min:0',
                'max_product_qty' => 'nullable|numeric|min:0',
                'sortOrder' => 'nullable|integer|min:0',
                'sortOrderCategory' => 'nullable|integer|min:0',
                'sortOrderSubCategory' => 'nullable|integer|min:0',
                'sortOrderPopular' => 'nullable|integer|min:0',
                'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'image1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'image2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'image3' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'variant' => 'nullable|array',
                'is_variant' => 'nullable|boolean',
                'subCategoryId' => 'nullable|exists:sub_category,id',
                'certificationId' => 'nullable|exists:certifications,id',
                'activationId' => 'nullable|exists:activations,id',
                'courierTypeId' => 'nullable|exists:couriertypes,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Additional validation for product range
            if ($request->filled('min_product_qty') && $request->filled('max_product_qty')) {
                if ($request->min_product_qty >= $request->max_product_qty) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors' => ['min_product_qty' => ['Lower product range must be less than higher product range']]
                    ], 422);
                }
            }

            $product = Product::findOrFail($id);
            $productData = $request->except(['_token']);
            $productData['updated_at'] = now();

            // Handle file uploads
            foreach (['icon' => 'producticon', 'image1' => 'product', 'image2' => 'product', 'image3' => 'product'] as $field => $path) {
                try {
                    if ($request->hasFile($field)) {
                        $productData[$field] = $this->uploadFile($request, $field, $path, $product->{$field});
                    }
                } catch (\Exception $e) {
                    Log::error("File upload failed for {$field}: " . $e->getMessage());
                    return response()->json([
                        'success' => false,
                        'message' => "Failed to upload {$field}",
                        'errors' => [$field => [$e->getMessage()]]
                    ], 500);
                }
            }

            // Handle variant data
            $productData['is_variant'] = filter_var($request->is_variant, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
            $productData['variant'] = $request->filled('variant') ? json_encode($request->variant) : null;

            // Generate SEO URL
            $baseSeoUrl = Str::slug($request->productName);
            $seoUrl = $baseSeoUrl;
            $counter = 1;

            while (Product::where('seoUrl', $seoUrl)->where('id', '!=', $id)->exists()) {
                $seoUrl = "{$baseSeoUrl}-{$counter}";
                $counter++;
            }
            $productData['seoUrl'] = $seoUrl;

            // Update product
            $product->update($productData);

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully!',
                'data' => $product
            ]);

        } catch (\Exception $e) {
            Log::error('Product update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product: ' . $e->getMessage()
            ], 500);
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
                'success' => false,
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
                'success' => false,
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
                'success' => false,
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
                'success' => false,
                'message' => 'Description validation failed',
                'errors' => $validator->errors()
                    ], 422);
                }
            }

            // Handle file uploads if present
            foreach (['icon' => 'producticon', 'image1' => 'product', 'image2' => 'product', 'image3' => 'product'] as $field => $path) {
                try {
                    if ($request->hasFile($field)) {
                        $productData[$field] = $this->uploadFile($request, $field, $path, $product->{$field});
                    } else {
                        unset($productData[$field]); // Don't update if no new file
                    }
                } catch (\Exception $e) {
                    Log::error("File upload failed for {$field}: " . $e->getMessage());
                    return response()->json([
                        'success' => false,
                        'message' => "Failed to upload {$field}",
                        'errors' => [$field => [$e->getMessage()]]
                    ], 500);
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

            // Determine which tab was updated
            $updatedTab = null;
            if ($request->has('productName')) {
                $updatedTab = 'basic';
            } elseif ($request->hasFile('icon') || $request->hasFile('image1') || $request->hasFile('image2') || $request->hasFile('image3')) {
                $updatedTab = 'images';
            } elseif ($request->has('productDesc1')) {
                $updatedTab = 'description';
            }

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'data' => [
                    'updatedTab' => $updatedTab,
                    'product' => $product
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Product partial update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product: ' . $e->getMessage()
            ], 500);
        }
    }

    private function uploadFile(Request $request, $fieldName, $path, $oldFile = null)
    {
        try {
            if ($request->hasFile($fieldName)) {
                $file = $request->file($fieldName);
                
                // Validate file
                if (!$file->isValid()) {
                    throw new \Exception('Invalid file upload');
                }

                // Validate file size (2MB)
                $maxSize = 2 * 1024 * 1024;
                if ($file->getSize() > $maxSize) {
                    throw new \Exception('File size must be less than 2MB');
                }

                // Validate mime type
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'];
                if (!in_array($file->getMimeType(), $allowedTypes)) {
                    throw new \Exception('Invalid file type. Only JPG, PNG, GIF and SVG files are allowed');
                }

                // Create upload directory if it doesn't exist
                $uploadPath = public_path($path);
                if (!file_exists($uploadPath)) {
                    if (!mkdir($uploadPath, 0755, true)) {
                        throw new \Exception('Failed to create upload directory');
                    }
                }

                // Generate unique filename
                $extension = $file->getClientOriginalExtension();
                $fileName = time() . '_' . Str::random(10) . '.' . $extension;
                $fullPath = $path . '/' . $fileName;

                // Move uploaded file
                if (!$file->move(public_path($path), $fileName)) {
                    throw new \Exception('Failed to move uploaded file');
                }

                // Delete old file if it exists
                if ($oldFile) {
                    $oldFilePath = public_path($oldFile);
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                return $fullPath;
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
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|exists:products,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $product = Product::findOrFail($id);
            
            // Clean up product images
            $imagePaths = [
                $product->icon,
                $product->image1,
                $product->image2,
                $product->image3
            ];

            foreach ($imagePaths as $path) {
                if ($path) {
                    $filePath = public_path($path);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }

            $product->status = 2; // Soft delete
            $product->save();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Product deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product: ' . $e->getMessage()
            ], 500);
        }
    }

    public function productOnTop(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'productId' => 'required|exists:products,id',
                'ontop' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $product = Product::findOrFail($request->productId);
            $product->on_top = $request->ontop;
            $product->save();

            return response()->json([
                'success' => true,
                'message' => 'Product on top status updated successfully',
                'data' => ['on_top' => (bool)$product->on_top]
            ]);
        } catch (\Exception $e) {
            Log::error('Product on top update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product on top status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function productOnStatus(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'productId' => 'required|exists:products,id',
                'status' => 'required|in:0,1,2'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $product = Product::findOrFail($request->productId);
            $product->status = $request->status;
            $product->save();

            $statusMessages = [
                0 => 'inactive',
                1 => 'active',
                2 => 'deleted'
            ];

            return response()->json([
                'success' => true,
                'message' => 'Product status updated successfully',
                'data' => [
                    'status' => $product->status,
                    'status_text' => $statusMessages[$product->status] ?? 'unknown'
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Product status update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getSubCategory(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'categoryId' => 'required|exists:category,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $subcategories = SubCategory::where('categoryId', $request->categoryId)
                ->whereNotIn('status', [2])
                ->select('id', 'subCategoryName', 'categoryId', 'status')
                ->orderBy('subCategoryName')
                ->get();

            if ($subcategories->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No subcategories found for this category',
                    'data' => []
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Subcategories retrieved successfully',
                'data' => $subcategories
            ]);

        } catch (\Exception $e) {
            Log::error('Sub category fetch failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch subcategories: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    private function getValidationRules()
    {
        return [
            'productName' => 'required|string|max:255',
            'categoryId' => 'required|exists:category,id',
            'price_type' => 'required|string',
            'priceB2C' => 'required|numeric|min:0',
            'priceMRP' => 'nullable|numeric|min:0',
            'priceB2B' => 'nullable|numeric|min:0',
            'min_product_qty' => 'nullable|numeric|min:0',
            'max_product_qty' => 'nullable|numeric|min:0',
            'sortOrder' => 'nullable|integer|min:0',
            'sortOrderCategory' => 'nullable|integer|min:0',
            'sortOrderSubCategory' => 'nullable|integer|min:0',
            'sortOrderPopular' => 'nullable|integer|min:0',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image3' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'variant' => 'nullable|array',
            'is_variant' => 'nullable|boolean',
            'subCategoryId' => 'nullable|exists:sub_category,id',
            'certificationId' => 'nullable|exists:certifications,id',
            'activationId' => 'nullable|exists:activations,id',
            'courierTypeId' => 'nullable|exists:couriertypes,id',
        ];
    }
}
