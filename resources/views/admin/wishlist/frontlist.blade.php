@extends('admin.layout')
@section('page-title', 'Manage Wishlist')
@section('content')

<div class="page-wrapper">
    <div class="content container-fluid">
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
                                        <th>Sl No.</th>
                                        <th>Product Name</th>
                                        <th>No. Of People</th>
                                        <th>Estimated Amount</th>
                                    </tr>
                                </thead>
                                <tbody></tbody> <!-- Empty body, filled via AJAX -->
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        if ($.fn.DataTable.isDataTable('#WishlistTable')) {
            $('#WishlistTable').DataTable().destroy();
        }

        // Initialize DataTable
        var table = $('#WishlistTable').DataTable({
            "processing": true,
            "serverSide": false,
            "ajax": {
                "url": "{{ route('admin.wishlist.data') }}",
                "type": "GET",
                "dataSrc": function(json) {
                    if (!json.data) {
                        return [];
                    }
                    return json.data;
                }
            },
            "columns": [
                { "data": null, "render": function(data, type, row, meta) { return meta.row + 1; }},
                { "data": "product_name", "render": function(data, type, row) {
                    var url = `{{ route('admin.wishlist.details', ':product_id') }}`.replace(':product_id', row.product_id);
                    return `<a href="${url}" class="text-primary">${data}</a>`;
                }},
                { "data": "user_count" },
                { "data": "estimated_amount" },
                
            ]
        });
    });
</script>

@endsection
