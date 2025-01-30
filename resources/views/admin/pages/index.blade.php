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
                            <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
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
                                <table class="table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Page Name</th>
                                            <th>Heading</th>
                                            <th>Status</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="sortable-pages">
                                        @forelse($pages as $page)
                                            <tr data-page-id="{{ $page->id }}">
                                                <td class="text-center">
                                                    <i class="fas fa-grip-vertical handle" style="cursor: move;"></i>
                                                </td>
                                                <td>{{ $page->pageName }}</td>
                                                <td>{{ $page->heading }}</td>
                                                <td>
                                                    <div class="onoffswitch">
                                                        <input type="checkbox" class="onoffswitch-checkbox"
                                                            id="status{{ $page->id }}"
                                                            {{ $page->status ? 'checked' : '' }}
                                                            onchange="pageStatus({{ $page->id }})">
                                                        <label class="onoffswitch-label"
                                                            for="status{{ $page->id }}"></label>
                                                    </div>
                                                </td>
                                                <td class="text-right">
                                                    <a href="/admin/pages/{{ $page->id }}/edit"
                                                        class="btn btn-sm btn-info">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No Pages found</td>
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

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
        <script>
            $(document).ready(function() {
                // Initialize sortable
                new Sortable(document.getElementById('sortable-pages'), {
                    handle: '.handle',
                    animation: 150,
                    onEnd: function() {
                        const orders = [];
                        $('#sortable-pages tr').each(function() {
                            orders.push($(this).data('page-id'));
                        });

                        // Update order via AJAX
                        $.ajax({
                            url: '/admin/banners/update-order',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                orders: orders
                            },
                            success: function(response) {
                                if (response.success) {
                                    toastr.success('Banner order updated successfully');
                                } else {
                                    toastr.error('Failed to update banner order');
                                }
                            },
                            error: function() {
                                toastr.error('An error occurred while updating banner order');
                            }
                        });
                    }
                });

                // Handle status toggle
                // $('.status-toggle-checkbox').change(function() {
                //     const pageId = $(this).data('page-id');
                //     $.ajax({
                //         url: '/admin/pages/' + pageId + '/update-status',
                //         type: 'POST',
                //         data: {
                //             _token: '{{ csrf_token() }}'
                //         },
                //         success: function(response) {
                //             if (response.success) {
                //                 toastr.success('Page status updated successfully');
                //             } else {
                //                 toastr.error('Failed to update banner status');
                //             }
                //         },
                //         error: function() {
                //             toastr.error('An error occurred while updating banner status');
                //         }
                //     });
                // });

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
            });
        </script>
    @endpush
@endsection
