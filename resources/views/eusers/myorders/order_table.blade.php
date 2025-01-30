<table class="rwd-table">
    <tbody>
        <tr>
            <th>ORDER ID</th>
            <th>DATE</th>
            <th>STATUS</th>
            <th>PRICE</th>
            <th>ACTION</th>
        </tr>
        @if ($orders->count() > 0)
        @foreach ($orders as $oder)
        <tr>
            <td data-th="ORDER ID">
                #{{ $oder->id }}
            </td>
            <td data-th="DATE">
                {{ $oder->created_at->format('M d, Y, h:i A') }}
            </td>
            <td data-th="STATUS">
                <span class="process">{{ $oder->orderStatus }}</span>
            </td>
            <td data-th="PRODUCT PRICE">
                ₹{{ $oder->amount }}
            </td>
            <td data-th="ACTION" class="main-btn">
                <a href="{{ route('euser.ordersview', $oder->id) }}" class="as_btn">View Details</a>
            </td>
        </tr>
        @endforeach
        @else
        <tr>
            <td colspan="5" class="text-center" style="color:red; font-weight:bold;">No orders found !</td>
        </tr>
        @endif
    </tbody>
</table>

@if ($orders->count() > 0)
<div class="pagination-container">
    {{ $orders->links() }}
</div>
@endif