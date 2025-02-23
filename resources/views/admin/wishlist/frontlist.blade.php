@extends('admin.layout')
@section('page-title', 'Manage Wishlist')
@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">Manage Wishlist</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Manage Wishlist</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="WishlistTable" class="datatable table table-striped">
                                <thead>
                                    <tr>
                                        <th class="no-sort">Sl No.</th>
                                        <th>Product Name</th>
                                        <th>No. Of People</th>
                                        <th>Estimated Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($Wishlist as $index => $Wishlistdata)
                                    @php
                                    $userCount = $Wishlist->where('product_id', $Wishlistdata->product_id)->count();

                                    $estimatedAmount = $userCount * (optional($Wishlistdata->productDetails)->priceB2C ?? 0);
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <a href="{{ route('admin.wishlist.details', ['product_id' => $Wishlistdata->product_id]) }}">
                                                {{ optional($Wishlistdata->productDetails)->productName ?? 'N/A' }}
                                            </a>
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
        if ($.fn.DataTable.isDataTable('#WishlistTable')) {
            $('#WishlistTable').DataTable().destroy();
        }

        $('#WishlistTable').DataTable({
            "paging": true,
            "ordering": true,
            "info": true,
            "columnDefs": [{
                "orderable": false,
                "targets": 'no-sort'
            }]
        });
    });
</script>

@endsection