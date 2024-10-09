@extends('admin.layout')
@section('content')
<div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Welcome Admin</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            <div class="row">
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                              <span class="dash-widget-icon bg-primary">
                              <i class="fa-solid fa-image"></i>
                              </span>
                                <div class="dash-count">
                                    <a href="#"><i
                                            class="fa fa-arrow-right text-primary"></i></a>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h3>2</h3>
                                <h6 class="text-muted">Banner</h6>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-primary w-100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                              <span class="dash-widget-icon bg-success">
                              <i class="fa-solid fa-rectangle-list"></i>
                              </span>
                                <div class="dash-count">
                                    <a href="#"><i
                                            class="fa fa-arrow-right text-success"></i></a>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h3>11</h3>
                                <h6 class="text-muted">Category</h6>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-success w-100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                              <span class="dash-widget-icon bg-warning">
                              <i class="fa-solid fa-box"></i>
                              </span>
                                <div class="dash-count">
                                    <a href="#"><i
                                            class="fa fa-arrow-right text-warning"></i></a>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h3>810</h3>
                                <h6 class="text-muted">Product</h6>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-warning w-100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                              <span class="dash-widget-icon bg-danger">
                              <i class="fa-solid fa-question-circle"></i>
                              </span>
                                <div class="dash-count">
                                    <a href="#"><i
                                            class="fa fa-arrow-right text-danger"></i></a>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h3>1</h3>
                                <h6 class="text-muted">Faq</h6>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-danger w-100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection