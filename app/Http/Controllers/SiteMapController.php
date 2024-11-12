<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SiteMapController extends Controller
{
    //
    public function index()
    {
        $sitemaps = [
            route('sitemap.categories'),
            route('sitemap.subcategories'),
            route('sitemap.footer-pages'),
            route('sitemap.products'),
            // route('sitemap.images'),
            route('sitemap.productImages'),
            route('sitemap.categoryImages'),
            route('sitemap.subCategoryImages'),
        ];

        return response()->view('sitemap.index', compact('sitemaps'))->header('Content-Type', 'application/xml');
    }
    public function categories()
    {
        $categories = \App\Models\Category::all(); // Adjust this as needed to fetch categories

        return response()->view('sitemap.categories', compact('categories'))->header('Content-Type', 'application/xml');
    }
    public function subcategories()
    {
        $subcategories = \App\Models\Subcategory::all(); // Adjust based on your subcategory model

        return response()->view('sitemap.subcategories', compact('subcategories'))->header('Content-Type', 'application/xml');
    }
    public function footerPages()
    {
        $footerPages = \App\Models\Page::where('onFooter', 1)->get(); // Adjust based on your page model and logic

        return response()->view('sitemap.footer-pages', compact('footerPages'))->header('Content-Type', 'application/xml');
    }
    public function products()
    {
        $productsPerPage = 100;
        $totalProducts = \App\Models\Product::count();
        $totalPages = ceil($totalProducts / $productsPerPage);

        $sitemaps = [];
        for ($page = 1; $page <= $totalPages; $page++) {
            $sitemaps[] = route('sitemap.products.page', ['page' => $page]);
        }

        return response()->view('sitemap.products', compact('sitemaps'))->header('Content-Type', 'application/xml');
    }

    public function productsPage($page)
    {
        $productsPerPage = 100;
        $products = \App\Models\Product::skip(($page - 1) * $productsPerPage)->take($productsPerPage)->get();

        return response()->view('sitemap.products_page', compact('products'))->header('Content-Type', 'application/xml');
    }
    public function productImages()
    {
        $productsPerPage = 100;
        $totalProducts = \App\Models\Product::count();
        $totalPages = ceil($totalProducts / $productsPerPage);

        $sitemaps = [];
        for ($page = 1; $page <= $totalPages; $page++) {
            $sitemaps[] = route('sitemap.product-images.page', ['page' => $page]);
        }

        return response()->view('sitemap.product-images', compact('sitemaps'))->header('Content-Type', 'application/xml');
    }

    public function productImagesPage($page)
    {
        $productsPerPage = 100;
        $products = \App\Models\Product::skip(($page - 1) * $productsPerPage)->take($productsPerPage)->get();

        return response()->view('sitemap.product-images-page', compact('products'))->header('Content-Type', 'application/xml');
    }

    public function categoryImages()
    {
        $productsPerPage = 100;
        $totalProducts = \App\Models\Category::count();
        $totalPages = ceil($totalProducts / $productsPerPage);

        $sitemaps = [];
        for ($page = 1; $page <= $totalPages; $page++) {
            $sitemaps[] = route('sitemap.category-images.page', ['page' => $page]);
        }

        return response()->view('sitemap.category-images', compact('sitemaps'))->header('Content-Type', 'application/xml');
    }
    public function categoryImagesPage($page)
    {
        $productsPerPage = 100;
        $products = \App\Models\Category::skip(($page - 1) * $productsPerPage)->take($productsPerPage)->get();

        return response()->view('sitemap.category-images-page', compact('products'))->header('Content-Type', 'application/xml');
    }
    public function subCategoryImages()
    {
        $productsPerPage = 100;
        $totalProducts = \App\Models\SubCategory::count();
        $totalPages = ceil($totalProducts / $productsPerPage);

        $sitemaps = [];
        for ($page = 1; $page <= $totalPages; $page++) {
            $sitemaps[] = route('sitemap.subcategory-images.page', ['page' => $page]);
        }

        return response()->view('sitemap.sub-category-images', compact('sitemaps'))->header('Content-Type', 'application/xml');
    }
    public function subCategoryImagesPage($page)
    {
        $productsPerPage = 100;
        $products = \App\Models\SubCategory::skip(($page - 1) * $productsPerPage)->take($productsPerPage)->get();

        return response()->view('sitemap.sub-categories-image-page', compact('products'))->header('Content-Type', 'application/xml');
    }
    
}
