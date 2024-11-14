<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($footerPages as $category)
        <url>
            <loc>{{ route('pages', $category->seoUrl) }}</loc>
            <lastmod>{{ $category->updated_at->toAtomString() }}</lastmod>
            <priority>0.8</priority>
        </url>
    @endforeach
</urlset>
