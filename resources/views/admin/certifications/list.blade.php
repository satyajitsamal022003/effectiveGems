@extends('admin.layout')
@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">Manage Certifications</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Manage Certifications</li>
                    </ul>
                </div>
                <div class="panel-heading col-md-2">
                    <a href="{{route('admin.addcertification')}}" class="btn btn-block btn-primary">Add Certifications</a>
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
                                        <th>Activation Amount</th>
                                        <th class="no-sort">Status</th>
                                        <th class="no-sort">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activationlist as $index => $category)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><a href="#">{{ $category->amount }}</a></td>
                                        <td>
                                            <div class="onoffswitch">
                                                <input type="checkbox" name="onoffswitch928" class="onoffswitch-checkbox" id="productOnStatus{{ $category->id }}" tabindex="0" {{ $category->status ? 'checked' : '' }} onchange="toggleOnStatus({{ $category->id }})">
                                                <label class="onoffswitch-label" for="productOnStatus{{ $category->id }}"></label>
                                            </div>
                                        </td>
                                        <td class="center action">
                                            @if ($category->id != 1 && $category->id != 2)  <!-- Check if the category id is not 1 or 2 -->
                                                <a href="{{ route('admin.editcertification', $category->id) }}" class="btn btn-sm bg-success mr-2">
                                                    <i class="fa-solid fa-pencil"></i>
                                                </a>
                                        
                                                <a class="btn btn-sm bg-danger mr-2" href="{{ route('admin.deletecertification', $category->id) }}"
                                                   onclick="return confirm('Are you sure to delete!');" title="Delete">
                                                   <i class="fa fa-remove"></i>
                                                </a>
                                            @endif
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
            "columnDefs": [{
                "orderable": false,
                "targets": 'no-sort'
            }]
        });

        function toggleOnStatus(categoryId) {
            var isChecked = $('#productOnStatus' + categoryId).is(':checked');
            var status = isChecked ? 1 : 0;

            $.ajax({
                type: "POST",
                url: "{{ route('admin.certificationstatus') }}",
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

        window.toggleOnStatus = toggleOnStatus;

        function deleteCategory(id) {
            if (confirm('Are you sure you want to delete this Certification?')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    });
</script>

@endsection