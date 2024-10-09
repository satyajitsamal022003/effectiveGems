@extends('admin.layout')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">Order Placed</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active">Order Placed</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="ordersTable" class="datatable table table-stripped">
                                    <thead>
                                        <tr>
                                            <th>Sl.No</th>
                                            <th>Order Date</th>
                                            <th>Customer ID</th>
                                            <th>Name</th>
                                            <th>View</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>


    <!-- start modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog order-details-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Order Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="order-list-header">
                    <div class="row">
                        <div class="col-lg-5 col-md-5 col-12">
                            <p>Order Id : <span id="modalOrderId"></span> </p>
                            <p>Order Date : <span id="modalOrderDate"></span> </p>
                        </div>
                        <div class="col-lg-7 col-md-7 col-12">
                            <p class="text-right">Customer Id : <span id="modalCustId"></span></p>
                            <p class="text-right">Customer Name : <span id="modalCustName"></span></p>
                        </div>
                    </div>
                </div>
                <div class="modal-body">

                </div>
                <div class="order-list-bottom">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="tracking-info">
                                <h4>Tracking Information :</h4>
                                <p>Courier Name : DTDC Parcel</p>
                                <p>Tracking Number : Z06066552</p>
                                <p>Dispatch Date : 28 Sep 2024</p>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="sub-total-info">
                                <h4>Sub Total : RS <span id="modalSubTotal"></span> /-</h4>
                                <h4>Total : RS <span id="modalSubTotal2"></span>/-</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- customer request -->
 <!-- Modal -->
<div class="modal fade" id="requestModal" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="requestModalLabel">Request to Customer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="requestForm"> <!-- Add form here -->
                <div class="modal-body">
                    <textarea id="requestMessage" class="form-control" rows="4" placeholder="Enter your message here..." required></textarea>
                    <input type="hidden" id="orderId" name="orderId" value=""> <!-- Ensure name attribute for form submission -->
                    @csrf <!-- CSRF Token -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Send Request</button> <!-- Change to type="submit" -->
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cancel reason modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">Reason For Cancellation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="cancelForm"> <!-- Add form here -->
                <div class="modal-body">
                    <textarea id="cancelmessage" class="form-control" rows="4" placeholder="Enter your message here..." required></textarea>
                    <input type="hidden" id="orderId" name="orderId" value=""> <!-- Ensure name attribute for form submission -->
                    @csrf <!-- CSRF Token -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Send Cancellation</button> <!-- Change to type="submit" -->
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openRequestModal(orderId) {
    $('#orderId').val(orderId); // Set order ID in the hidden input
    $('#requestMessage').val(''); // Clear any previous message
    $('#requestModal').modal('show'); // Show the modal
}

function openCancelModal(orderId) {
    $('#orderId').val(orderId); // Set order ID in the hidden input
    $('#cancelmessage').val(''); // Clear any previous message
    $('#cancelModal').modal('show'); // Show the modal
}
</script>
    <script>
        // Declare the table variable in the global scope
        var table;

        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#ordersTable')) {
                $('#ordersTable').DataTable().destroy();
            }

            // Initialize the DataTable and assign it to the global variable
            table = $('#ordersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.order.data') }}", // Update the route for orders
                    data: function(d) {
                        d.sortByName = $('#sortByName').val(); // Send sorting order
                        d.category = $('#category').val(); // Send filtering category
                    }
                },
                columns: [{
                        data: 'DT_RowIndex', // Sl.No
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'created_at', // Order Date
                        name: 'orderDate'
                    },
                    {
                        data: 'middleName', // Customer ID
                        name: 'customerID'
                    },
                    {
                        data: 'name', // Name
                        name: 'name'
                    },
                    {
                        data: 'id', // View button
                        name: 'view',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, full, meta) {
                            return `<a href="javascript:" data-toggle="modal" data-order='${JSON.stringify(full)}' data-target="#exampleModal"
                                    class="btn btn-sm bg-info mr-2">View</a>`;
                        }
                    },
                    {
                        data: 'amount', // Amount
                        name: 'amount'
                    },
                    {
                        data: 'orderApproved', // Status
                        name: 'status',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, full, meta) {
                            return data == 1 ? 'Approved' : 'Not-Approved';
                        }
                    },
                    {
                        data: 'id', // Action buttons
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, full, meta) {
                            return `
                            <a href="#" title="Approve" onclick="return toggleOnStatus(${data},1)" class="btn btn-sm bg-success mr-2">
                                <i class="fa-regular fa-check"></i>
                            </a>
                            <a href="#" title="Cancel" onclick="openCancelModal(${data})" class="btn btn-sm bg-danger mr-2">
                                <i class="fa-regular fa-xmark"></i>
                            </a>
                             <a href="#" title="Request To Customer" onclick="openRequestModal(${data})" class="btn btn-sm bg-info mr-2">
                                <i class="fa-regular fa-person-circle-question"></i>
                            </a>`;
                        }
                    }
                ]
            });

            $('#sortByName').on('change', function() {
                table.ajax.reload(); // Reload table with selected sorting order
            });

            $('#category').on('change', function() {
                table.ajax.reload(); // Reload table with selected category
            });
        });

        function toggleOnStatus(orderId, status) {
            $.ajax({
                type: "POST",
                url: "{{ route('admin.order.changeStatus') }}",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'orderId': orderId,
                    'orderApproved': status
                },
                success: function(response) {
                    toastr.success(response.message);
                    table.ajax.reload(); // Access the global table variable
                },
                error: function(xhr, status, error) {
                    toastr.error('An error occurred: ' + error);
                }
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            function getCourierType(courierTypeId) {
                // Logic to retrieve courier type details (mock example)
                const courierTypes = {
                    1: {
                        id: 1,
                        courier_price: 0
                    },
                    2: {
                        id: 2,
                        courier_price: 50
                    },
                    3: {
                        id: 3,
                        courier_price: 70
                    },
                    4: {
                        id: 4,
                        courier_price: 90
                    }
                };
                return courierTypes[courierTypeId] || null;
            }
            $('#exampleModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var order = button.data('order'); // Extract the order object

                console.log(order); // You can check the order data in the console for debugging

                var modal = $(this);

                // Set Order Details
                modal.find('#modalOrderId').text(order.id);
                modal.find('#modalOrderDate').text(new Date(order.created_at).toLocaleDateString());
                modal.find('#modalCustId').text(order.userId || 'N/A');
                modal.find('#modalCustName').text(order.name || 'N/A');

                // Assuming you only display the first item, you could loop through all items if needed
                if (order.items.length > 0) {
                    // Loop through each item in order.items
                    order.items.forEach(function(item) {
                        let deliveryPrice = 0;
                        if (item.product_details.courierTypeId) {
                            const courierType = getCourierType(item.product_details
                                .courierTypeId
                            ); // Assume this function gets the courier type object
                            if (courierType) {
                                deliveryPrice = courierType.courier_price;
                                if (courierType.id === 3 || courierType.id === 4) {
                                    deliveryPrice *= item
                                        .quantity; // Adjust delivery price based on quantity
                                }
                            }
                        }

                        // Calculate subtotal for the item
                        const subtotal = (item.product_details.priceB2C + item.activation + item
                            .certificate) * item.quantity + deliveryPrice;

                        // Create a new order list element
                        var orderList = `
            <div class="order-list">
                <div class="order-list-img-text">
                    <a href="#" class="order-list-img"><img src="${item.product_details.image || 'assets/img/default.jpg'}" alt="image"></a>
                    <div class="order-text">
                        <a href="#"><span>${item.product_details.productName}</span></a>
                        <p>QTY : <span>${item.quantity}</span> pcs</p>
                    </div>
                </div>
                <div class="order-list-info">
                    ${item.product_details.activationId == 1 
                        ? '<p>Activation Fee : RS <span>Free</span></p>' 
                        : item.product_details.activationId == 2 
                        ? '' 
                        : `<p>Activation Fee : RS <span>${item.activation}</span></p>`
                    }
                        ${item.product_details.certificationId == 1 
                        ? '<p>Certificate Fee : RS <span>Free</span></p>' 
                        : item.product_details.certificationId == 2 
                        ? '' 
                        : `<p>Certificate Fee : RS <span>${item.certificate}</span></p>`
                    }

                    <p>Delivery Charges : RS <span>${deliveryPrice}</span></p>
                </div>
                <div class="order-list-total">
                    <p>Total : <span>${subtotal}</span></p>
                </div>
            </div>
        `;

                        // Append the new order list to the modal body
                        modal.find('.modal-body').append(orderList);
                    });
                }

                // Tracking Information
                // Replace with actual tracking details if available in your data
                modal.find('.tracking-info p:nth-child(2)').text(
                    'Tracking Number: Z06066552'); // Placeholder

                // Subtotal and total
                modal.find('#modalSubTotal').text(order.amount || 'N/A');
                modal.find('#modalSubTotal2').text(order.amount || 'N/A');
            });
        });
    </script>
    <script>
    $(document).ready(function() {
        $('#requestForm').on('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

            const orderId = $('#orderId').val();
            const message = $('#requestMessage').val();

            $.ajax({
                url: '{{ route('admin.order.requestToCustomer') }}', // Directly use the route helper
                type: 'POST',
                data: {
                    orderId: orderId,
                    message: message,
                    _token: $('meta[name="csrf-token"]').attr('content') // Ensure CSRF token is included
                },
                success: function(response) {
                    alert(response.message);
                    $('#requestModal').modal('hide');
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message);
                }
            });
        });
    });
    
    $(document).ready(function() {
        $('#cancelForm').on('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

            const orderId = $('#orderId').val();
            const cancellationReason  = $('#cancelmessage').val();

            $.ajax({
                url: '{{ route('admin.order.changeStatus') }}', // Directly use the route helper
                type: 'POST',
                data: {
                    orderId: orderId,
                    orderApproved: 0,
                    cancellationReason : cancellationReason ,
                    _token: $('meta[name="csrf-token"]').attr('content') // Ensure CSRF token is included
                },
                success: function(response) {
                    alert(response.message);
                    $('#cancelModal').modal('hide');
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message);
                }
            });
        });
    });
</script>

    <!--end modal-->
@endsection
