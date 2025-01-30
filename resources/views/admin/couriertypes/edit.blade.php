@extends('admin.layout')
@section('page-title', $couriertype->courier_name ?? '')
@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">Edit Courier Type</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.listcouriertype') }}">Courier Types</a></li>
                        <li class="breadcrumb-item active">{{$couriertype->courier_name ?? ''}}</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.updatecouriertype', $couriertype->id) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="tab-content">
                                <div class="tab-pane show active" id="basictab">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <fieldset class="fieldset-style">
                                                <div class="row">
                                                    <div class="col-xl-8">
                                                        <div class="form-group">
                                                            <label>Courier Name</label>
                                                            <input class="form-control" type="text" name="courier_name"
                                                                value="{{ old('courier_name', $couriertype->courier_name) }}"
                                                                placeholder="Enter Courier Name">
                                                            @if ($errors->has('courier_name'))
                                                                <span class="text-danger">{{ $errors->first('courier_name') }}</span>
                                                            @endif
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Courier Price</label>
                                                            <input class="form-control" type="text" name="courier_price"
                                                                value="{{ old('courier_price', $couriertype->courier_price) }}"
                                                                placeholder="Enter Courier Price">
                                                            @if ($errors->has('courier_price'))
                                                                <span class="text-danger">{{ $errors->first('courier_price') }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div> <!-- end col -->
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-4">
                                    <button type="submit" class="btn btn-primary">Update</button>
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
