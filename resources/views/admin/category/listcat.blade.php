@extends('admin.layout')
@section('page-title', 'Category-List') 
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">Manage Category</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Manage Category</li>
                        </ul>
                    </div>
                    <div class="panel-heading col-md-2">
                        <a href="{{route('admin.addcat')}}" class="btn btn-block btn-primary">Add Category</a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="categoryTable" class="datatable table table-stripped">
                                    <thead>
                                        <tr>
                                            <th class="no-sort">Sl No.</th>
                                            <th>Name</th>
                                            <th class="no-sort">Image</th>
                                            <th class="no-sort">No Of Products</th>
                                            <th>Sort Order</th>
                                            <th class="no-sort">On Top</th>
                                            <th class="no-sort">On Footer</th>
                                            <th class="no-sort">Status</th>
                                            <th class="no-sort">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($categories as $index => $category)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td><a href="#">{{ $category->categoryName }}</a></td>
                                                <td>
                                                    <img src="{{ asset($category->image ?? 'assets/img/noImage.png') }}" alt="Category Image" style="height: 35px" />
                                                </td>
                                                <td><a href="#">{{ $category->products_count ?? 0 }}</a></td> <!-- Assuming you have a relationship for products -->
                                                <td>{{ $category->sortOrder }}</td>
                                                <td>
                                                    <div class="onoffswitch">
                                                        <input type="checkbox" name="onoffswitch928" class="onoffswitch-checkbox" id="productOnTop{{ $category->id }}" tabindex="0" {{ $category->onTop ? 'checked' : '' }} onchange="toggleOnTop({{ $category->id }})">
                                                        <label class="onoffswitch-label" for="productOnTop{{ $category->id }}"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="onoffswitch">
                                                        <input type="checkbox" name="onoffswitch928" class="onoffswitch-checkbox" id="productOnFooter{{ $category->id }}" tabindex="0" {{ $category->onFooter ? 'checked' : '' }} onchange="toggleOnFooter({{ $category->id }})">
                                                        <label class="onoffswitch-label" for="productOnFooter{{ $category->id }}"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="onoffswitch">
                                                        <input type="checkbox" name="onoffswitch928" class="onoffswitch-checkbox" id="productOnStatus{{ $category->id }}" tabindex="0" {{ $category->status ? 'checked' : '' }} onchange="toggleOnStatus({{ $category->id }})">
                                                        <label class="onoffswitch-label" for="productOnStatus{{ $category->id }}"></label>
                                                    </div>
                                                </td>
                                                <td class="center action">
                                                    <a href="{{ route('admin.editcat', $category->id) }}" class="btn btn-sm bg-success mr-2">
                                                        <i class="fa-solid fa-pencil"></i>
                                                    </a>
                                                    <a href="{{route('user.index')}}" target="_blank" class="btn btn-sm bg-info mr-2">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm bg-danger mr-2" onclick="deleteCategory({{ $category->id }})">
                                                        <i class="fa-regular fa-trash-can"></i>
                                                    </button>
                                                
                                                    
                                                    <form id="delete-form-{{ $category->id }}" action="{{ route('admin.deleteecat', $category->id) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
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

    <script type="text/javascript">
        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#categoryTable')) {
                $('#categoryTable').DataTable().destroy();
            }

            $('#categoryTable').DataTable({
                "paging": true,
                "ordering": true,
                "info": true,
                "columnDefs": [
                    { "orderable": false, "targets": 'no-sort' }
                ]
            });

            function toggleOnTop(categoryId) { 
                var isChecked = $('#productOnTop' + categoryId).is(':checked');
                var ontop = isChecked ? 1 : 0;

                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.toggleOnTop') }}",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'categoryId': categoryId,
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

            function toggleOnFooter(categoryId) { 
                var isChecked = $('#productOnFooter' + categoryId).is(':checked');
                var onfooter = isChecked ? 1 : 0;

                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.toggleOnFooter') }}",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'categoryId': categoryId,
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
            
            function toggleOnStatus(categoryId) { 
                var isChecked = $('#productOnStatus' + categoryId).is(':checked');
                var status = isChecked ? 1 : 0;

                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.toggleOnStatus') }}",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'categoryId': categoryId,
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

            window.toggleOnTop = toggleOnTop;
            window.toggleOnFooter = toggleOnFooter;
            window.toggleOnStatus = toggleOnStatus;
            window.deleteCategory = deleteCategory;

            function deleteCategory(id) {
                if (confirm('Are you sure you want to delete this category?')) {
                    document.getElementById('delete-form-' + id).submit(); 
                }
            }
        });
    </script>
@endsection
