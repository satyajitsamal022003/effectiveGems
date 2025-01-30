@extends('admin.layout')
@section('page-title', 'Testimonial-List')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">Manage Testimonial</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Manage Testimonial</li>
                        </ul>
                    </div>
                    <div class="panel-heading col-md-2">
                        <a href="{{ route('admin.addtestimonial') }}" class="btn btn-block btn-primary">Add Testimonial</a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="testimonialTable" class="datatable table table-stripped">
                                    <thead>
                                        <tr>
                                            <th class="no-sort">Sl No.</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Designation</th>
                                            <th>Heading</th>
                                            <th>Description</th>
                                            <th class="no-sort">Status</th>
                                            <th class="no-sort">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($testimonials as $index => $testimonial)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <img src="{{ asset($testimonial->userImage ?? 'assets/img/noImage.png') }}" alt="Testimonial Image" style="height: 35px" />
                                                </td>
                                                <td>{{ $testimonial->userName }}</td>
                                                <td>{{ $testimonial->designation }}</td>
                                                <td>{{ $testimonial->heading }}</td>
                                                <td>{!! Str::limit(strip_tags($testimonial->description), 50) !!}</td>
                                                <td>
                                                    <div class="onoffswitch">
                                                        <input type="checkbox" name="onoffswitch{{ $testimonial->id }}" class="onoffswitch-checkbox" id="status{{ $testimonial->id }}" tabindex="0" {{ $testimonial->status ? 'checked' : '' }} onchange="toggleStatus({{ $testimonial->id }})">
                                                        <label class="onoffswitch-label" for="status{{ $testimonial->id }}"></label>
                                                    </div>
                                                </td>
                                                <td class="center action">
                                                    <a href="{{ route('admin.edittestimonial', $testimonial->id) }}" class="btn btn-sm bg-success mr-2">
                                                        <i class="fa-solid fa-pencil"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-sm bg-info mr-2">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm bg-danger mr-2" onclick="deleteTestimonial({{ $testimonial->id }})">
                                                        <i class="fa-regular fa-trash-can"></i>
                                                    </button>

                                                    <form id="delete-form-{{ $testimonial->id }}" action="{{ route('admin.deletetestimonial', $testimonial->id) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
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

    <script type="text/javascript">
        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#testimonialTable')) {
                $('#testimonialTable').DataTable().destroy();
            }

            $('#testimonialTable').DataTable({
                "paging": true,
                "ordering": true,
                "info": true,
                "columnDefs": [
                    { "orderable": false, "targets": 'no-sort' }
                ]
            });

            function toggleStatus(testimonialId) { 
                var isChecked = $('#status' + testimonialId).is(':checked');
                var status = isChecked ? 1 : 0;

                $.ajax({
                    type: "POST",
                    url: "{{ route('testimonial.toggleOnStatus') }}",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'testimonialId': testimonialId,
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

            function deleteTestimonial(id) {
                if (confirm('Are you sure you want to delete this testimonial?')) {
                    document.getElementById('delete-form-' + id).submit(); 
                }
            }

            window.toggleStatus = toggleStatus;
            window.deleteTestimonial = deleteTestimonial;
        });
    </script>
@endsection
