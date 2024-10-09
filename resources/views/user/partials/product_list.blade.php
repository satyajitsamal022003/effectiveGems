@if($products->isNotEmpty())
    @foreach($products as $product)
        <div class="mega_menu_item">
            <a href="{{route('user.productdetails',$product->id)}}"><span><img src="{{ asset($product->image1) }}" alt="{{ $product->productName }}"></span>{{ $product->productName }}</a>
        </div>
    @endforeach
@else
    <p>No products available for this category.</p>
@endif
