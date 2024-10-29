@extends('admin.layout')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">Manage Products</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active">Manage Products</li>
                        </ul>
                    </div>
                    <div class="panel-heading col-md-2">
                        <a href="{{ route('admin.addproduct') }}" class="btn btn-block btn-primary">Add Products</a>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-3">
                    <label for="sortOrder">Category:</label>
                    <select id="category" class="form-control" onchange="getSubCategories(this.value)">
                        <option value="" selected>-Select a category-</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->categoryName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3">
                    <label for="sortOrder">Sub Category:</label>
                    <select id="subCategory" class="form-control">
                        <option value="" selected>-Select a sub-category-</option>

                    </select>
                </div>
                <div class="col-6">
                    <label for="sortOrder">Sort by Name:</label>
                    <select id="sortByName" class="form-control">
                        <option value="asc">A-Z</option>
                        <option value="desc">Z-A</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="table-responsive">
                                <table id="ProductTable" class="datatable table table-stripped">
                                    <thead>
                                        <tr>
                                            <th class="no-sort">Sl No.</th>
                                            <th>Name</th>
                                            <th class="no-sort">Image</th>
                                            <th class="no-sort">MRP Price</th>
                                            <th>B2C Price</th>
                                            <th class="no-sort">On Top</th>
                                            <th class="no-sort">Sort</th>
                                            <th class="no-sort">Status</th>
                                            <th class="no-sort">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
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

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>


    <script type="text/javascript">
        // $(document).ready(function() {
        //     if ($.fn.DataTable.isDataTable('#ProductTable')) {
        //         $('#ProductTable').DataTable().destroy();
        //     }

        //     $('#ProductTable').DataTable({
        //         "paging": true,
        //         "ordering": true,
        //         "info": true,
        //         "columnDefs": [
        //             { "orderable": false, "targets": 'no-sort' }
        //         ]
        //     });

        //     function toggleOnTop(productId) { 
        //         var isChecked = $('#productOnTop' + productId).is(':checked');
        //         var ontop = isChecked ? 1 : 0;

        //         $.ajax({
        //             type: "POST",
        //             url: "{{ route('admin.productOnTop') }}",
        //             data: {
        //                 '_token': '{{ csrf_token() }}',
        //                 'productId': productId,
        //                 'ontop': ontop
        //             },
        //             success: function(response) {
        //                 toastr.success(response.message);
        //             },
        //             error: function(xhr, status, error) {
        //                 toastr.error('An error occurred: ' + error); 
        //             }
        //         });
        //     }

        //     function toggleOnStatus(productId) { 
        //         var isChecked = $('#productOnStatus' + productId).is(':checked');
        //         var status = isChecked ? 1 : 0;

        //         $.ajax({
        //             type: "POST",
        //             url: "{{ route('admin.productOnStatus') }}",
        //             data: {
        //                 '_token': '{{ csrf_token() }}',
        //                 'productId': productId,
        //                 'status': status
        //             },
        //             success: function(response) {
        //                 toastr.success(response.message);
        //             },
        //             error: function(xhr, status, error) {
        //                 toastr.error('An error occurred: ' + error); 
        //             }
        //         });
        //     }

        //     window.toggleOnTop = toggleOnTop;
        //     window.toggleOnStatus = toggleOnStatus;
        //     window.deleteProduct = deleteProduct;

        //     function deleteProduct(id) {
        //         if (confirm('Are you sure you want to delete this Product?')) {
        //             document.getElementById('delete-form-' + id).submit(); 
        //         }
        //     }
        // });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>

    <script type="text/javascript">
        function deleteProduct(id) {
            if (confirm('Are you sure you want to delete this product?')) {
                $.ajax({
                    url: `/admin/delete-product/${id}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        $('#ProductTable').DataTable().ajax.reload(); // Reload table after deletion
                        toastr.success('Product deleted successfully.'); // Notify success
                    },
                    error: function(error) {
                        toastr.error('Error deleting product.'); // Notify error
                    }
                });
            }
        }

        function toggleOnTop(productId) {
            var isChecked = $('#productOnTop' + productId).is(':checked');
            var ontop = isChecked ? 1 : 0;

            $.ajax({
                type: "POST",
                url: "{{ route('admin.productOnTop') }}",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'productId': productId,
                    'ontop': ontop
                },
                success: function(response) {
                    toastr.success(response.message);
                },
                error: function(xhr, status, error) {
                    toastr.error('An error occurred: ' + error);
                }
            });
        }

        function toggleOnStatus(productId) {
            var isChecked = $('#productOnStatus' + productId).is(':checked');
            var status = isChecked ? 1 : 0;

            $.ajax({
                type: "POST",
                url: "{{ route('admin.productOnStatus') }}",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'productId': productId,
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

        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#ProductTable')) {
                $('#ProductTable').DataTable().destroy();
            }

            var table = $('#ProductTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 100,
                ajax: {
                    url: "{{ route('admin.products.data') }}",
                    data: function(d) {
                        d.sortByName = $('#sortByName').val(); // Send the sorting order
                        d.category = $('#category').val(); // Send the sorting order
                        d.subCategory = $('#subCategory').val(); // Send the sorting order
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'productName',
                        name: 'productName'
                    },
                    {
                        data: 'image1',
                        name: 'image1',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, full, meta) {
                            // If image exists, prepend the path; otherwise, provide a fallback image.
                            const imageUrl = data ? `{{ asset('/${data}') }}` :
                                `{{ asset('blank.png') }}`;
                            return `<img src="${imageUrl}" alt="Product Image" style="height: 35px;" class="img-responsive"/>`;
                        }
                    },

                    {
                        data: 'priceMRP',
                        name: 'priceMRP'
                    },
                    {
                        data: 'priceB2C',
                        name: 'priceB2C'
                    },
                    {
                        data: 'on_top',
                        name: 'on_top',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, full, meta) {
                            return `
                                <div class="onoffswitch">
                                    <input type="checkbox" class="onoffswitch-checkbox" id="productOnTop${full.id}" 
                                    ${data ? 'checked' : ''} onchange="toggleOnTop(${full.id})">
                                    <label class="onoffswitch-label" for="productOnTop${full.id}"></label>
                                </div>`;
                        }
                    },
                    {
                        data: 'sortOrderSubCategory',
                        name: 'sortOrderSubCategory',
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, full, meta) {
                            return `
                                <div class="onoffswitch">
                                    <input type="checkbox" class="onoffswitch-checkbox" id="productOnStatus${full.id}" 
                                    ${data ? 'checked' : ''} onchange="toggleOnStatus(${full.id})">
                                    <label class="onoffswitch-label" for="productOnStatus${full.id}"></label>
                                </div>`;
                        }
                    },
                    {
                        data: 'id',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, full, meta) {
                            return `
                                <a href="/admin/product-edit/${data}" class="btn btn-sm bg-success mr-2">
                                    <i class="fa-solid fa-pencil"></i>
                                </a>
                                 <a href="/products-details/${data}" class="btn btn-sm bg-info mr-2">
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                <button type="button" class="btn btn-sm bg-danger mr-2" onclick="deleteProduct(${data})">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>`;
                        }
                    }
                ]
            });

            $('#sortByName').on('change', function() {
                table.ajax.reload(); // Reload table with the selected sorting order
            });
            $('#category').on('change', function() {
                table.ajax.reload(); // Reload table with the selected sorting order
            });
            $('#subCategory').on('change', function() {
                table.ajax.reload(); // Reload table with the selected sorting order
            });
        });
    </script>
    <script>
        function getSubCategories(id) {
            $.ajax({
                type: "GET",
                url: `{{ route('user.subCategoryAjax', '') }}/${id}`, // Corrected URL syntax
                data: {
                    '_token': '{{ csrf_token() }}',
                },
                success: function(response) {
                    const subCategorySelect = $('#subCategory');
                    subCategorySelect.empty(); // Clear existing options
                    subCategorySelect.append('<option value="" selected>-Select a sub-category-</option>');

                    response.forEach(function(subCategory) {
                        subCategorySelect.append(
                            `<option value="${subCategory.id}">${subCategory.subCategoryName}</option>`
                        );
                    });
                },
                error: function(xhr, status, error) {
                    toastr.error('An error occurred: ' + error);
                }
            });
        }
    </script>
@endsection
