@extends('admin.layout')
@section('page-title', 'Faq-List')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">Manage Faq</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Manage Faq</li>
                        </ul>
                    </div>
                    <div class="panel-heading col-md-2">
                        <a href="{{ route('admin.addfaq') }}" class="btn btn-block btn-primary">Add Faq</a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="FaqTable" class="datatable table table-stripped">
                                    <thead>
                                        <tr>
                                            <th class="no-sort">Sl No.</th>
                                            <th class="no-sort">Question</th>
                                            <th class="no-sort">Answer</th>
                                            <th class="no-sort">Status</th>
                                            <th class="no-sort">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($faqs as $index => $faq)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $faq->question ?? 'N/A' }}</td>
                                                <td>{!! $faq->answer ?? 'N/A' !!}</td>
                                                <td>
                                                    <div class="onoffswitch">
                                                        <input type="checkbox" name="onoffswitch928"
                                                            class="onoffswitch-checkbox" id="faqOnStatus{{ $faq->id }}"
                                                            tabindex="0" {{ $faq->is_active ? 'checked' : '' }}
                                                            onchange="toggleOnStatus({{ $faq->id }})">
                                                        <label class="onoffswitch-label"
                                                            for="faqOnStatus{{ $faq->id }}"></label>
                                                    </div>
                                                </td>
                                                <td class="center action">
                                                    <a href="{{ route('admin.editfaq', $faq->id) }}"
                                                        class="btn btn-sm bg-success mr-2">
                                                        <i class="fa-solid fa-pencil"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm bg-danger mr-2"
                                                        onclick="deleteFaq({{ $faq->id }})">
                                                        <i class="fa-regular fa-trash-can"></i>
                                                    </button>
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
        function toggleOnStatus(faqId) {
            var isChecked = $('#faqOnStatus' + faqId).is(':checked');
            var status = isChecked ? 1 : 0;

            $.ajax({
                type: "POST",
                url: "{{ route('admin.faqOnStatus') }}",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'faqId': faqId,
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

        function deleteFaq(id) {
            if (confirm('Are you sure you want to delete this FAQ?')) {
                $.ajax({
                    url: "{{ url('admin/delete-faq') }}/" + id, // Adjust route as needed
                    type: "DELETE",
                    data: {
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            setTimeout(function() {
                                window.location.href = '{{ route('admin.listfaq') }}';
                            }, 1000);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Failed to delete FAQ.');
                    }
                });
            }
        }

        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#FaqTable')) {
                $('#FaqTable').DataTable().destroy();
            }

            $('#FaqTable').DataTable({
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
