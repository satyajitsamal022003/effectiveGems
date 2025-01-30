@extends('admin.layout')
@section('page-title', 'Edit Pages')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">Edit Pages</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="/admin/pages">Pages</a></li>
                            <li class="breadcrumb-item active">Edit Page</li>
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

                            <form action="/admin/pages/{{ $pages->id }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Page Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="pageName" class="form-control"
                                                        value="{{ old('pageName', $pages->pageName) }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Heading <span class="text-danger">*</span></label>
                                                    <input type="text" name="heading" class="form-control"
                                                        value="{{ old('heading', $pages->heading) }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Description</label>
                                                    <textarea name="description" id="description1" rows="4" class="form-control">{{ old('description', $pages->description) }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Sort Order</label>
                                                    <input type="number" name="sortOrder" class="form-control"
                                                        value="{{ old('sortOrder', $pages->sortOrder) }}" min="0">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Status</label>
                                                    <div class="status-toggle mt-2">
                                                        <input type="checkbox" id="status" class="check" name="status"
                                                            value="1"
                                                            {{ old('status', $pages->status) ? 'checked' : '' }}>
                                                        <label for="status" class="checktoggle">checkbox</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Seo Url</label>
                                                    <input type="text" name="seoUrl" class="form-control"
                                                        value="{{ old('seoUrl', $pages->seoUrl) }}" min="0">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Meta Title</label>
                                                    <input type="text" name="metaTitle" class="form-control"
                                                        value="{{ old('metaTitle', $pages->metaTitle) }}" min="0">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Meta Description</label>
                                                    <textarea name="metaKeyword" id="description2" class="form-control" min="0">{{ old('metaKeyword', $pages->metaKeyword) }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Meta Keyword</label>
                                                    <textarea name="metaDescription" id="description3" class="form-control" min="0">{{ old('metaDescription', $pages->metaDescription) }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">
                                        Update Page
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('description1');
        CKEDITOR.replace('description2');
        CKEDITOR.replace('description3');
    </script>
@endsection
