@extends('admin.layout')
@section('page-title', 'Courier-Types')
@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">Manage Courier Types</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Manage Courier Types</li>
                    </ul>
                </div>
                <div class="panel-heading col-md-2">
                    <a href="{{ route('admin.addcouriertype') }}" class="btn btn-block btn-primary">Add Courier Type</a>
                </div>
            </div>
        </div>

        <!-- Courier Type List -->
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="courierTypeTable" class="datatable table table-stripped">
                                <thead>
                                    <tr>
                                        <th class="no-sort">Sl No.</th>
                                        <th>Courier Type</th>
                                        <th class="no-sort">Status</th>
                                        <th class="no-sort">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($couriertypes as $index => $courierType)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><a href="#">{{ $courierType->courier_name }}</a></td>
                                        <td>
                                            <div class="onoffswitch">
                                                <input type="checkbox" name="onoffswitch{{ $courierType->id }}" class="onoffswitch-checkbox" id="courierTypeStatus{{ $courierType->id }}" tabindex="0" {{ $courierType->status ? 'checked' : '' }} onchange="toggleCourierTypeStatus({{ $courierType->id }})">
                                                <label class="onoffswitch-label" for="courierTypeStatus{{ $courierType->id }}"></label>
                                            </div>
                                        </td>
                                        <td class="center action">
                                            @if ($courierType->id != 1) <!-- Check if the courierType id is not 1 -->
                                                <a href="{{ route('admin.editcouriertype', $courierType->id) }}" class="btn btn-sm bg-success mr-2">
                                                    <i class="fa-solid fa-pencil"></i>
                                                </a>
                                                <a href="{{ route('admin.deletecouriertype', $courierType->id) }}" class="btn btn-sm bg-danger mr-2" 
                                                   onclick="return confirm('Are you sure you want to delete this courier type?');" title="Delete">
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

<!-- jQuery and Toastr Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        if ($.fn.DataTable.isDataTable('#courierTypeTable')) {
            $('#courierTypeTable').DataTable().destroy();
        }

        $('#courierTypeTable').DataTable({
            "paging": true,
            "ordering": true,
            "info": true,
            "columnDefs": [{
                "orderable": false,
                "targets": 'no-sort'
            }]
        });

        // Toggle Courier Type Status
        function toggleCourierTypeStatus(courierTypeId) {
            var isChecked = $('#courierTypeStatus' + courierTypeId).is(':checked');
            var status = isChecked ? 1 : 0;

            $.ajax({
                type: "POST",
                url: "{{ route('admin.couriertypestatus') }}",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'courierTypeId': courierTypeId,
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

        // Assign function to global scope
        window.toggleCourierTypeStatus = toggleCourierTypeStatus;
    });
</script>

@endsection