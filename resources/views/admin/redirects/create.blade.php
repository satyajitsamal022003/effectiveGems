@extends('admin.layout')
@section('page-title', 'Redirect-Url-Add')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">Redirects</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="https://effectivegems.com/admin_panel/dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="/admin/redirects">Redirects</a></li>
                            <li class="breadcrumb-item active">Add Redirect</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('redirects.store') }}">
                                @csrf
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
                                                               value="{{ old('old_url') }}">
                                                    </div>
                                                </div>

                                                <!-- New URL -->
                                                <div class="col-xl-6">
                                                    <div class="form-group">
                                                        <label for="new_url">New URL</label>
                                                        <input type="text" class="form-control required" 
                                                               placeholder="New URL" name="new_url" required 
                                                               value="{{ old('new_url') }}">
                                                    </div>
                                                </div>

                                                <!-- Status -->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="status">Status</label>
                                                        <select class="form-control required" name="status" required>
                                                            <option value="">--Select Status--</option>
                                                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                                                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div> <!-- end col -->
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">
                                        <button class="btn btn-primary" type="submit">Create</button>
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
