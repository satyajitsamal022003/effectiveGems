@extends('admin.layout')
@section('page-title', $redirect->new_url ?? '')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">Redirects</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="/admin/redirects">Redirects</a></li>
                            <li class="breadcrumb-item active">{{$redirect->new_url ?? ''}}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('redirects.update', $redirect->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-xl-12">
                                        <fieldset class="fieldset-style">
                                            <div class="row">
                                                <!-- Old URL -->
                                                <div class="col-xl-6">
                                                    <div class="form-group">
                                                        <label for="old_url">Old URL</label>
                                                        <input type="text" class="form-control required"
                                                               placeholder="Old URL" name="old_url" required
                                                               value="{{ old('old_url', $redirect->old_url) }}">
                                                    </div>
                                                </div>

                                                <!-- New URL -->
                                                <div class="col-xl-6">
                                                    <div class="form-group">
                                                        <label for="new_url">New URL</label>
                                                        <input type="text" class="form-control required"
                                                               placeholder="New URL" name="new_url" required
                                                               value="{{ old('new_url', $redirect->new_url) }}">
                                                    </div>
                                                </div>

                                                <!-- Status -->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="status">Status</label>
                                                        <select class="form-control required" name="status" required>
                                                            <option value="">--Select Status--</option>
                                                            <option value="1" {{ old('status', $redirect->status) == '1' ? 'selected' : '' }}>Active</option>
                                                            <option value="0" {{ old('status', $redirect->status) == '0' ? 'selected' : '' }}>Inactive</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div> <!-- end col -->
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">
                                        <button class="btn btn-primary" type="submit">Update</button>
                                    </label>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
