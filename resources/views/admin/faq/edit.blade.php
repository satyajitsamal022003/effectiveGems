@extends('admin.layout')
@section('page-title', $faq->question ?? '')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">Edit Faq</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.listfaq') }}">Faq</a></li>
                            <li class="breadcrumb-item active"> {{ $faq->question ?? '' }}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="faqForm">
                                @csrf
                                <div class="form-group">
                                    <label>Question </label>
                                    <input class="form-control" type="text" placeholder="Question" name="question"
                                        id="question" value="{{ $faq->question ?? '' }}">
                                    <span class="text-danger error-text question_error"></span>
                                </div>

                                <div class="form-group">
                                    <label>Answer </label>
                                    <textarea class="form-control" name="answer" id="answer">{{ $faq->answer ?? '' }}</textarea>
                                    <span class="text-danger error-text answer_error"></span>
                                </div>

                                <button type="submit" class="btn btn-primary">Update Faq</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('answer');
    </script>
    <script>
        $(document).ready(function() {
            $('#faqForm').on('submit', function(e) {
                e.preventDefault();

                $('.error-text').text(''); // Clear previous errors

                toastr.options = {
                    "closeButton": true,
                    "progressBar": true,
                    "timeOut": 1000,
                    "extendedTimeOut": 1000,
                    "hideDuration": 1000,
                    "showDuration": 1000
                };


                const editor = CKEDITOR.instances[`answer`];
                editor.updateElement();

                $.ajax({
                    url: "{{ route('admin.editfaqdata', $faq->id) }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            setTimeout(function() {
                                window.location.href = '{{ route('admin.listfaq') }}';
                            }, 1000);

                            // $('#faqForm')[0].reset();
                        }
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            $('.' + key + '_error').text(value[
                                0]); // Show errors under fields
                        });
                    }
                });
            });
        });
    </script>
@endsection
