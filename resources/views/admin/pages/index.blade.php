@extends('admin.layout')
@section('page-title', 'Manage Pages')
@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Pages</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Pages</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif
                        <div class="table-responsive">
                            <table id="PagesTable" class="datatable table table-stripped">
                                <thead>
                                    <tr>
                                        <th class="no-sort text-center">#</th>
                                        <th class="no-sort">Page Name</th>
                                        <th class="no-sort">Heading</th>
                                        <th class="no-sort">Status</th>
                                        <th class="no-sort text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pages as $index => $page)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $page->pageName }}</td>
                                        <td>{{ $page->heading }}</td>
                                        <td>
                                            <div class="onoffswitch">
                                                <input type="checkbox" class="onoffswitch-checkbox"
                                                    id="status{{ $page->id }}"
                                                    {{ $page->status ? 'checked' : '' }}
                                                    onchange="pageStatus({{ $page->id }})">
                                                <label class="onoffswitch-label" for="status{{ $page->id }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-right">
                                            <a href="/admin/pages/{{ $page->id }}/edit" class="btn btn-sm btn-info">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No Pages found</td>
                                    </tr>
                                    @endforelse
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
    // Handle page status toggle
    function pageStatus(pageId) {
        var isChecked = $('#status' + pageId).is(':checked');
        var status = isChecked ? 1 : 0;

        $.ajax({
            type: "POST",
            url: "{{ route('admin.pages.updateStatus') }}",
            data: {
                '_token': '{{ csrf_token() }}',
                'pageId': pageId,
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
        // Initialize DataTable
        if ($.fn.DataTable.isDataTable('#PagesTable')) {
            $('#PagesTable').DataTable().destroy();
        }

        $('#PagesTable').DataTable({
            "paging": true,
            "ordering": true,
            "info": true,
            "columnDefs": [{
                "orderable": false,
                "targets": 'no-sort'
            }]
        });
    });
</script>
@endsection
