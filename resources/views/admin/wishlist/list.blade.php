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
                                            <th class="no-sort">User Name</th>
                                            <th class="no-sort">Phone</th>
                                            <th>Product Name</th>
                                            <th>Image</th>
                                            <th>Variant</th>
                                            <th>B2C Price</th>
                                            <th>MRP Price</th>
                                            <th>Is Followed</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($Wishlist as $index => $Wishlistdata)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    {{ optional($Wishlistdata->userDetails)->first_name ?? 'N/A' }}
                                                    {{ optional($Wishlistdata->userDetails)->last_name ?? '' }}
                                                </td>
                                                <td>{{ optional($Wishlistdata->userDetails)->mobile ?? 'N/A' }}</td>
                                                <td>{{ optional($Wishlistdata->productDetails)->productName ?? 'N/A' }}</td>
                                                <td>
                                                    @if (!empty(optional($Wishlistdata->productDetails)->image1))
                                                        <img src="{{ asset(optional($Wishlistdata->productDetails)->image1) }}"
                                                            width="50">
                                                    @else
                                                        No Image
                                                    @endif
                                                </td>
                                                <td>{{ optional($Wishlistdata->productDetails)->variantName ?? 'N/A' }}</td>
                                                <td>{{ optional($Wishlistdata->productDetails)->priceB2C ?? 'N/A' }}
                                                </td>
                                                <td>{{ optional($Wishlistdata->productDetails)->priceMRP ?? 'N/A' }}</td>
                                                <td>
                                                    <div class="onoffswitch">
                                                        <input type="checkbox" name="onoffswitch928"
                                                            class="onoffswitch-checkbox"
                                                            id="wichlistOnStatus{{ $Wishlistdata->id }}" tabindex="0"
                                                            {{ $Wishlistdata->follow_off ? 'checked' : '' }}
                                                            onchange="toggleOnStatus({{ $Wishlistdata->id }})">
                                                        <label class="onoffswitch-label"
                                                            for="wichlistOnStatus{{ $Wishlistdata->id }}"></label>
                                                    </div>
                                                </td>
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

        function toggleOnStatus(wishlistId) {
            var isChecked = $('#wichlistOnStatus' + wishlistId).is(':checked');
            var status = isChecked ? 1 : 0;

            $.ajax({
                type: "POST",
                url: "{{ route('admin.wishlistOnStatus') }}",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'wishlistId': wishlistId,
                    'status': status
                },
                success: function(response) {
                    toastr.success(response.message);
                },
                error: function(xhr, status, error) {
                    toastr.error('An error occurred: ' + error);
                }
            });
        }
    </script>

@endsection
