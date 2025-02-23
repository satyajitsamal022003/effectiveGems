@extends('admin.layout')
@section('page-title', 'Manage Cart')
@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">Manage Cart</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Manage Cart</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="CartTable" class="datatable table table-striped">
                                <thead>
                                    <tr>
                                        <th class="no-sort">Sl No.</th>
                                        <th>Product Name</th>
                                        <th>No. Of People</th>
                                        <th>Estimated Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($CartItems as $index => $CartItem)
                                    @php
                                    $userCount = $CartItems->where('product_id', $CartItem->product_id)->count();
                                    $estimatedAmount = $userCount * (optional($CartItem->productDetails)->priceB2C ?? 0);
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            
                                                {{ optional($CartItem->productDetails)->productName ?? 'N/A' }}
                                            
                                        </td>
                                        <td>{{ $userCount }}</td>
                                        <td>{{ (int) $estimatedAmount }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        if ($.fn.DataTable.isDataTable('#CartTable')) {
            $('#CartTable').DataTable().destroy();
        }

        $('#CartTable').DataTable({
            "paging": true,
            "ordering": true,
            "info": true,
            "columnDefs": [{
                "orderable": false,
                "targets": 'no-sort'
            }]
        });

        $(document).on('click', '.delete-cart-item', function() {
            var cartItemId = $(this).data('id');
            if (confirm('Are you sure you want to remove this item?')) {
                $.ajax({
                    url: '{{ route("admin.cart.delete") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: cartItemId
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Item removed successfully');
                            location.reload();
                        } else {
                            toastr.error('Failed to remove item');
                        }
                    }
                });
            }
        });
    });
</script>
@endsection
