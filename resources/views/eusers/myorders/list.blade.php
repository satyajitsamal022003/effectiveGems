@extends('user.layout')
@section('title', 'My Orders') 
@section('content')
    @include('eusers.partials.header')
    <section class="container mb-5">
        <div class="account-body">
            <div class="order-filter mb-3">
                <label>Filter Order Status</label>
                <select class="form-select filter-status" aria-label="Default select example">
                    <option selected disabled>Order Status</option>
                    <option value="">All</option>
                    <option value="Placed">Placed</option>
                    <option value="Approved">Approved</option>
                    <option value="Dispatched">Dispatched</option>
                    <option value="Delivered">Delivered</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            </div>
            <div class="cart-page">
                <div id="order-list">
                    @include('eusers.myorders.order_table', ['orders' => $orders])
                </div>
            </div>
        </div>
    </section>
    <script src="{{ url('/') }}/user/assets/js/jquery.js"></script>

    <script>
        $(document).ready(function () {
            $('.filter-status').on('change', function () {
                var status = $(this).val();
                $.ajax({
                    url: "{{ route('euser.myorderlist') }}",
                    method: "GET",
                    data: { orderStatus: status },
                    success: function (response) {
                        $('#order-list').html(response);
                    }
                });
            });
        });
    </script>
@endsection
