@extends('admin.layout')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">Manage Sub Category</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active">Manage Sub Category</li>
                        </ul>
                    </div>
                    <div class="panel-heading col-md-3">
                        <a href="{{ route('admin.addsubcat') }}" class="btn btn-block btn-primary">Add Sub Category</a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="datatable table table-stripped">
                                    <thead>
                                        <tr>
                                            <th class="no-sort">Sl No.</th>
                                            <th>Category</th>
                                            <th>Sub Category</th>
                                            <th class="no-sort">Image</th>
                                            <th>Sort Order</th>
                                            <th class="no-sort">On Top</th>
                                            <th class="no-sort">On Footer</th>
                                            <th class="no-sort">Status</th>
                                            <th class="no-sort">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($subcategories as $key => $subcategory)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $subcategory->category ? $subcategory->category->categoryName : 'N/A' }}</td>
                                                <td>{{ $subcategory->subCategoryName }}</td>
                                                <td>
                                                    <img src="{{ $subcategory->image ? asset($subcategory->image) : asset('assets/img/noImage.png') }}" alt="No Image" style="height: 80px" />
                                                </td>
                                                <td>{{ $subcategory->sortOrder }}</td>
                                                <td>
                                                    <div class="onoffswitch">
                                                        <input type="checkbox" class="onoffswitch-checkbox" id="on_top{{ $subcategory->id }}" {{ $subcategory->onTop ? 'checked' : '' }} onchange="subcategoryOnTop({{ $subcategory->id }})">
                                                        <label class="onoffswitch-label" for="on_top{{ $subcategory->id }}"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="onoffswitch">
                                                        <input type="checkbox" class="onoffswitch-checkbox" id="on_footer{{ $subcategory->id }}" {{ $subcategory->onFooter ? 'checked' : '' }} onchange="subcategoryOnFooter({{ $subcategory->id }})">
                                                        <label class="onoffswitch-label" for="on_footer{{ $subcategory->id }}"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="onoffswitch">
                                                        <input type="checkbox" class="onoffswitch-checkbox" id="status{{ $subcategory->id }}" {{ $subcategory->status ? 'checked' : '' }} onchange="subcategoryStatus({{ $subcategory->id }})">
                                                        <label class="onoffswitch-label" for="status{{ $subcategory->id }}"></label>
                                                    </div>
                                                </td>
                                                <td class="center action">
                                                    <a href="{{ route('admin.editSubcat', $subcategory->id) }}" class="btn btn-sm bg-success mr-2">
                                                        <i class="fa-solid fa-pencil"></i>
                                                    </a>
                                                    <a href="{{route('user.subCategory', $subcategory->id)}}" target="_blank" class="btn btn-sm bg-info mr-2">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </a>
                                                    <form action="{{ route('admin.deletesubcat', $subcategory->id) }}" method="POST" style="display:inline;" id="delete-form-{{ $subcategory->id }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm bg-danger mr-2" onclick="confirmDelete({{ $subcategory->id }})">
                                                            <i class="fa-regular fa-trash-can"></i>
                                                        </button>
                                                    </form>
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

    <script>    
        $(document).ready(function() {
            var table = $('.datatable').DataTable();    
            if (table) {
                table.destroy();
            }
            $('.datatable').DataTable({
                "paging": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "columnDefs": [
                    { "orderable": false, "targets": 'no-sort' }
                ]
            });

            function confirmDelete(subcategoryId) {
                if (confirm('Are you sure you want to delete this subcategory?')) {
                    document.getElementById('delete-form-' + subcategoryId).submit();
                }
            }

            function subcategoryOnTop(subcategoryId) { 
                var isChecked = $('#on_top' + subcategoryId).is(':checked');
                var ontop = isChecked ? 1 : 0;

                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.subcategoryOnTop') }}",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'subcategoryId': subcategoryId,
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

            function subcategoryOnFooter(subcategoryId) { 
                var isChecked = $('#on_footer' + subcategoryId).is(':checked');
                var onfooter = isChecked ? 1 : 0;

                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.subcategoryOnFooter') }}",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'subcategoryId': subcategoryId,
                        'onfooter': onfooter
                    },
                    success: function(response) {
                        toastr.success(response.message);
                    },
                    error: function(xhr, status, error) {
                        toastr.error('An error occurred: ' + error); 
                    }
                });
            }
            
            function subcategoryStatus(subcategoryId) { 
                var isChecked = $('#status' + subcategoryId).is(':checked');
                var status = isChecked ? 1 : 0;

                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.subcategoryStatus') }}",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'subcategoryId': subcategoryId,
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

            window.subcategoryOnTop = subcategoryOnTop;
            window.subcategoryOnFooter = subcategoryOnFooter;
            window.subcategoryStatus = subcategoryStatus;
            window.confirmDelete = confirmDelete;
        });
    </script>
@endsection
