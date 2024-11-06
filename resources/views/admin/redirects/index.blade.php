@extends('admin.layout')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">Manage Redirects</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active">Manage Redirects</li>
                        </ul>
                    </div>
                    <div class="panel-heading col-md-3">
                        <a href="{{ route('redirects.create') }}" class="btn btn-block btn-primary">Add Redirect</a>
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
                                            <th>Old URL</th>
                                            <th>New URL</th>
                                            <th>Status</th>
                                            <th class="no-sort">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($redirects as $key => $redirect)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $redirect->old_url }}</td>
                                                <td>{{ $redirect->new_url }}</td>
                                                <td>
                                                    <div class="onoffswitch">
                                                        <input type="checkbox" class="onoffswitch-checkbox" id="status{{ $redirect->id }}" {{ $redirect->status ? 'checked' : '' }} onchange="redirectStatus({{ $redirect->id }})">
                                                        <label class="onoffswitch-label" for="status{{ $redirect->id }}"></label>
                                                    </div>
                                                </td>
                                                <td class="center action">
                                                    <a href="{{ route('redirects.edit', $redirect->id) }}" class="btn btn-sm bg-success mr-2">
                                                        <i class="fa-solid fa-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('redirects.destroy', $redirect->id) }}" method="POST" style="display:inline;" id="delete-form-{{ $redirect->id }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm bg-danger mr-2" onclick="confirmDelete({{ $redirect->id }})">
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

            function confirmDelete(redirectId) {
                if (confirm('Are you sure you want to delete this redirect?')) {
                    document.getElementById('delete-form-' + redirectId).submit();
                }
            }

            function redirectStatus(redirectId) { 
                var isChecked = $('#status' + redirectId).is(':checked');
                var status = isChecked ? 1 : 0;

                $.ajax({
                    type: "POST",
                    url: "{{ route('redirects.updateStatus') }}",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'redirectId': redirectId,
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

            window.redirectStatus = redirectStatus;
            window.confirmDelete = confirmDelete;
        });
    </script>
@endsection
