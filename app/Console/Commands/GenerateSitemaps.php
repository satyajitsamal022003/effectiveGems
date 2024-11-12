<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Url;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use Carbon\Carbon;

class GenerateSitemaps extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate sitemaps for categories, subcategories, products, and other URLs';

    public function handle()
    {
        $this->generateCategorySitemap();
        $this->generateSubCategorySitemap();
        $this->generateProductSitemap();
        $this->generateOtherUrlsSitemap();
        $this->generateMainSitemapIndex();

        $this->info('Sitemaps generated successfully.');
    }

    protected function generateCategorySitemap()
    {
        $sitemap = Sitemap::create();

        $categories = Category::where('status', 1)->get();
        foreach ($categories as $category) {
            $url = url('category-products/' . $category->id);
            $sitemap->add(
                Url::create($url)
                    ->setLastModificationDate($category->updated_at ?? Carbon::now())
                    ->addImage(asset($category->image), 'Category Image')
            );
        }

        $sitemap->writeToFile(public_path('sitemaps/categories-sitemap.xml'));
    }

    protected function generateSubCategorySitemap()
    {
        $sitemap = Sitemap::create();

        $subcategories = SubCategory::where('status', 1)->get();
        foreach ($subcategories as $subcategory) {
            $url = url('subcategory/' . $subcategory->id);
            $sitemap->add(
                Url::create($url)
                    ->setLastModificationDate($subcategory->updated_at ?? Carbon::now())
                    ->addImage(asset($subcategory->image), 'defaultImage.jpeg')
            );
        }

        $sitemap->writeToFile(public_path('sitemaps/subcategories-sitemap.xml'));
    }

    protected function generateProductSitemap()
    {
        $sitemap = Sitemap::create();

        $products = Product::where('status', 1)->get();
        foreach ($products as $product) {
            $url = url('product-details/' . $product->id);
            $urlEntry = Url::create($url)
                ->setLastModificationDate($product->updated_at ?? Carbon::now());

            // Add images associated with each product
            foreach (['image1', 'image2', 'image3'] as $imageField) {
                if (!empty($product->$imageField)) {
                    $urlEntry->addImage(
                        asset($product->$imageField),
                        $product->{$imageField . 'Alt'} ?? '',
                        $product->{$imageField . 'Title'} ?? '',
                        $product->{$imageField . 'Caption'} ?? ''
                    );
                }
            }

            $sitemap->add($urlEntry);
        }

        $sitemap->writeToFile(public_path('sitemaps/products-sitemap.xml'));
    }

    protected function generateOtherUrlsSitemap()
    {
        $sitemap = Sitemap::create();

        // Add other URLs, like homepage, contact, etc.
        $sitemap->add(Url::create(url('/'))->setLastModificationDate(Carbon::now()));
        $sitemap->add(Url::create(url('/pages/about-us'))->setLastModificationDate(Carbon::now()));
        $sitemap->add(Url::create(url('/pages/contact-us'))->setLastModificationDate(Carbon::now()));
        $sitemap->add(Url::create(url('/pages/terms-and-conditions'))->setLastModificationDate(Carbon::now()));
        $sitemap->add(Url::create(url('/pages/privacy-policy'))->setLastModificationDate(Carbon::now()));
        $sitemap->add(Url::create(url('/pages/disclaimer'))->setLastModificationDate(Carbon::now()));
        $sitemap->add(Url::create(url('/pages/grievance-redressal'))->setLastModificationDate(Carbon::now()));
        $sitemap->add(Url::create(url('/pages/cancellation-refund'))->setLastModificationDate(Carbon::now()));
        $sitemap->add(Url::create(url('/pages/shipping-and-delivery'))->setLastModificationDate(Carbon::now()));

        $sitemap->writeToFile(public_path('sitemaps/footer-urls-sitemap.xml'));
    }

    protected function generateMainSitemapIndex()
{
    // Create the SitemapIndex
    $sitemapIndex = SitemapIndex::create()
        ->add(url('sitemaps/categories-sitemap.xml'))
        ->add(url('sitemaps/subcategories-sitemap.xml'))
        ->add(url('sitemaps/products-sitemap.xml'))
        ->add(url('sitemaps/footer-urls-sitemap.xml'));

    // Generate the sitemap index file path
    $filePath = public_path('sitemap.xml');
    
    // Write the sitemap index XML to the file
    $sitemapIndex->writeToFile($filePath);

    // Add a comment at the top of the sitemap index file (after the XML declaration)
    $xmlContent = file_get_contents($filePath);
    $comment = "<!-- This XML Sitemap Index file contains 4 sitemaps. -->\n";
    
    // Make sure we are not interfering with the XML declaration
    if (strpos($xmlContent, '<?xml') === 0) {
        $xmlContent = preg_replace('/^<\?xml.*\?>/', '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . $comment, $xmlContent);
    }

    // Save the file with the added comment after the XML declaration
    file_put_contents($filePath, $xmlContent);
}

}
