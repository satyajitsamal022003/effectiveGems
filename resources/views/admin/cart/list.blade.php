@extends('admin.layout')
@section('page-title', 'Cart Details - ' . $productName)
@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">Cart Details - {{ $productName }}</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.aCartData') }}">Cart</a></li>
                        <li class="breadcrumb-item active">{{ $productName }}</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <button class="btn btn-danger mb-3" id="deleteSelected">Delete Selected</button>
                        <div class="table-responsive">
                            <table id="CartTable" class="datatable table table-striped">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th>Sl No.</th>
                                        <th>User ID/Ip</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Created At</th>
                                        <th>Followup</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($CartItems as $index => $Cartdata)
                                    <tr>
                                        <td><input type="checkbox" class="cart-checkbox" value="{{ $Cartdata->id }}"></td>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $Cartdata->cart->userDetails->userid ?? $Cartdata->cart->ip }}</td>
                                        <td>{{ $Cartdata->cart->userDetails->email ?? 'N/A' }}</td>
                                        <td>{{ $Cartdata->cart->userDetails->mobile ?? 'N/A' }}</td>

                                        <td>{{ optional($Cartdata->created_at)->format('d-m-Y H:i') }}</td>
                                        <td>
                                            <div class="onoffswitch">
                                                <input type="checkbox" name="onoffswitch928"
                                                    class="onoffswitch-checkbox"
                                                    id="cartOnStatus{{ $Cartdata->id }}" tabindex="0"
                                                    {{ $Cartdata->follow_off ? 'checked' : '' }}
                                                    onchange="toggleOnStatus({{ $Cartdata->id }})">
                                                <label class="onoffswitch-label"
                                                    for="cartOnStatus{{ $Cartdata->id }}"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-danger" onclick="deleteCart({{ $Cartdata->id }})">Cancel</button>
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

        // Select all checkboxes
        $('#selectAll').on('click', function() {
            $('.cart-checkbox').prop('checked', this.checked);
        });

        // Delete selected carts
        $('#deleteSelected').on('click', function() {
            var selectedIds = $('.cart-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            if (selectedIds.length === 0) {
                toastr.warning('Please select at least one cart item to delete.');
                return;
            }

            if (confirm('Are you sure you want to delete the selected cart items?')) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.cart.massDelete') }}",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'cartIds': selectedIds
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        toastr.error('An error occurred: ' + error);
                    }
                });
            }
        });
    });

    function toggleOnStatus(cartId) {
        var isChecked = $('#cartOnStatus' + cartId).is(':checked');
        var status = isChecked ? 1 : 0;

        $.ajax({
            type: "POST",
            url: "{{ route('admin.cartOnStatus') }}",
            data: {
                '_token': '{{ csrf_token() }}',
                'cartId': cartId,
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

    function deleteCart(cartId) {
        if (confirm('Are you sure you want to remove this cart item?')) {
            $.ajax({
                type: "POST",
                url: "{{ route('admin.cart.delete') }}",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'cartId': cartId
                },
                success: function(response) {
                    toastr.success(response.message);
                    location.reload();
                },
                error: function(xhr, status, error) {
                    toastr.error('An error occurred: ' + error);
                }
            });
        }
    }
</script>

@endsection
