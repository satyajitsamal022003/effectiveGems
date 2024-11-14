<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    @foreach ($products as $item)
        <url>
            <loc>{{ route('user.productdetails', $item->id) }}</loc>

            @if ($item->image1)
                <image:image>
                    <image:loc>{{ asset($item->image1) }}</image:loc>
                </image:image>
            @endif

            @if ($item->image2)
                <image:image>
                    <image:loc>{{ asset($item->image2) }}</image:loc>
                </image:image>
            @endif

            @if ($item->image3)
                <image:image>
                    <image:loc>{{ asset($item->image3) }}</image:loc>
                </image:image>
            @endif
        </url>
    @endforeach


</urlset>
