@extends('admin.layout')
@section('page-title', 'Manage Cart')
@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">
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
                                        <th>Sl No.</th>
                                        <th>Product Name</th>
                                        <th>No. Of People</th>
                                        <th>Estimated Amount</th>
                                    </tr>
                                </thead>
                                <tbody></tbody> <!-- Empty body, will be filled via AJAX -->
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        if ($.fn.DataTable.isDataTable('#CartTable')) {
            $('#CartTable').DataTable().destroy();
        }
        // Initialize DataTable
        var table = $('#CartTable').DataTable({
            "processing": true,
            "serverSide": false, // Set true if you need pagination from the backend
            "ajax": {
                "url": "{{ route('admin.cart.data') }}",
                "type": "GET",
                "dataSrc": function(json) {
                    if (!json.data) {
                        return []; // Prevents the 'undefined length' error
                    }
                    return json.data;
                }
            },
            "columns": [{
                    "data": null,
                    "render": function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    "data": "product_name",
                    "render": function(data, type, row) {
                        var url = `{{ route('admin.cart.details', ':product_id') }}`.replace(':product_id', row.product_id);
                        return `<a href="${url}" class="text-primary">${data}</a>`;
                    }
                },
                {
                    "data": "user_count"
                },
                {
                    "data": "estimated_amount"
                }
            ]
        });

        // Delete cart item
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
                            table.ajax.reload(); // Reload DataTable after deletion
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