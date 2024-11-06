@extends('admin.layout')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">Manage Coupons</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Manage Coupons</li>
                        </ul>
                    </div>
                    <div class="panel-heading col-md-2">
                        <a href="{{ route('coupons.create') }}" class="btn btn-block btn-primary">Add Coupon</a>
                    </div>
                </div>
            </div>

            <!-- Coupons Table -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="couponTable" class="datatable table table-stripped">
                                    <thead>
                                        <tr>
                                            <th>Sl No.</th>
                                            <th>Coupon Name</th>
                                            <th>Description</th>
                                            <th>Type</th>
                                            <th>Value</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($coupons as $index => $coupon)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $coupon->name }}</td>
                                                <td>{{ $coupon->description }}</td>
                                                <td>{{ $coupon->type == 1 ? 'Flat' : 'Percentage' }}</td>
                                                <td>{{ $coupon->value }}</td>
                                                <td>{{ \Carbon\Carbon::parse($coupon->startDate)->format('d-m-Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($coupon->endDate)->format('d-m-Y') }}</td>
                                                <td>
                                                    <div class="onoffswitch">
                                                        <input type="checkbox" name="onoffswitch{{ $coupon->id }}"
                                                            class="onoffswitch-checkbox"
                                                            id="couponStatus{{ $coupon->id }}" tabindex="0"
                                                            {{ $coupon->status ? 'checked' : '' }}
                                                            onchange="toggleCouponStatus({{ $coupon->id }})">
                                                        <label class="onoffswitch-label"
                                                            for="couponStatus{{ $coupon->id }}"></label>
                                                    </div>
                                                </td>
                                                <td class="center action">
                                                    <a href="{{ route('coupons.edit', $coupon->id) }}"
                                                        class="btn btn-sm bg-success mr-2">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('coupons.destroy', $coupon->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm bg-danger"
                                                            onclick="return confirm('Are you sure to delete this coupon?');"
                                                            title="Delete">
                                                            <i class="fa fa-remove"></i>
                                                        </button>
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
        // $('#couponTable').DataTable({
        //     "paging": true,
        //     "ordering": true,
        //     "info": true,
        //     "columnDefs": [{
        //         "orderable": false,
        //         "targets": 'no-sort'
        //     }]
        // });

        function toggleCouponStatus(couponId) {
            var isChecked = $('#couponStatus' + couponId).is(':checked');
            var status = isChecked ? 1 : 0;

            $.ajax({
                type: "POST",
                url: "{{ route('coupons.couponstatus') }}",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'couponId': couponId,
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

        window.toggleCouponStatus = toggleCouponStatus;
    });
</script>
@endsection
