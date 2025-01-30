@extends('admin.layout')
@section('page-title', 'Manage Banners')
@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Banners</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active">Banners</li>
                    </ul>
                </div>
                <div class="col-auto">
                    <a href="/admin/banners/create" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Banner
                    </a>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover table-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Button</th>
                                        <th>Status</th>
                                        <th class="text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="sortable-banners">
                                    @forelse($banners as $banner)
                                    <tr data-banner-id="{{ $banner->id }}">
                                        <td class="text-center">
                                            <i class="fas fa-grip-vertical handle" style="cursor: move;"></i>
                                        </td>
                                        <td>
                                            <img src="{{ asset($banner->image) }}" alt="{{ $banner->title }}"
                                                style="max-width: 100px; height: auto;">
                                        </td>
                                        <td>{{ $banner->title }}</td>
                                        <td>{{ Str::limit($banner->description, 50) }}</td>
                                        <td>
                                            @if($banner->button_text)
                                            {{ $banner->button_text }}
                                            @if($banner->button_link)
                                            <br><small class="text-muted">{{ $banner->button_link }}</small>
                                            @endif
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td>
                                            <div class="status-toggle">
                                                <input type="checkbox" id="status_{{ $banner->id }}"
                                                    class="check status-toggle-checkbox"
                                                    {{ $banner->status ? 'checked' : '' }}
                                                    data-banner-id="{{ $banner->id }}">
                                                <label for="status_{{ $banner->id }}" class="checktoggle">checkbox</label>
                                            </div>
                                        </td>
                                        <td class="text-right">
                                            <a href="/admin/banners/{{ $banner->id }}/edit"
                                                class="btn btn-sm btn-info">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="/admin/banners/{{ $banner->id }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this banner?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No banners found</td>
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
        new Sortable(document.getElementById('sortable-banners'), {
            handle: '.handle',
            animation: 150,
            onEnd: function() {
                const orders = [];
                $('#sortable-banners tr').each(function() {
                    orders.push($(this).data('banner-id'));
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
        $('.status-toggle-checkbox').change(function() {
            const bannerId = $(this).data('banner-id');
            $.ajax({
                url: '/admin/banners/' + bannerId + '/update-status',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success('Banner status updated successfully');
                    } else {
                        toastr.error('Failed to update banner status');
                    }
                },
                error: function() {
                    toastr.error('An error occurred while updating banner status');
                }
            });
        });
    });
</script>
@endpush
@endsection
