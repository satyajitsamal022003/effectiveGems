@extends('admin.layout')
@section('page-title', 'Edit Banner')
@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">Edit Banner</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="/admin/banners">Banners</a></li>
                        <li class="breadcrumb-item active">Edit Banner</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="/admin/banners/{{ $banner->id }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>Title <span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control" value="{{ old('title', $banner->title) }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea name="description" rows="4" class="form-control">{{ old('description', $banner->description) }}</textarea>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Button Text</label>
                                                <input type="text" name="button_text" class="form-control" value="{{ old('button_text', $banner->button_text) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Button Link</label>
                                                <input type="text" name="button_link" class="form-control" value="{{ old('button_link', $banner->button_link) }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Sort Order</label>
                                                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $banner->sort_order) }}" min="0">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Status</label>
                                                <div class="status-toggle mt-2">
                                                    <input type="checkbox" id="status" class="check" name="status" value="1" {{ old('status', $banner->status) ? 'checked' : '' }}>
                                                    <label for="status" class="checktoggle">checkbox</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Banner Image</label>
                                        <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(this)">
                                        <small class="text-muted">Recommended size: 1920x600 pixels</small>
                                        <div class="mt-2">
                                            <img id="image-preview" src="{{ asset($banner->image) }}" alt="Current Banner" 
                                                style="max-width: 100%;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">
                                    Update Banner
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#image-preview')
                .attr('src', e.target.result)
                .show();
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection
