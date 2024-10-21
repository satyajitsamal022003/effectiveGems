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
                <div class="order-list-header">
                    <h6>Customer Information</h6>
                    <div class="row">
                        <div class="col-lg-5 col-md-5 col-12">
                            <p>Customer Email : <span id="modalCustEmail"></span> </p>
                            <p>Customer Mobile : <span id="modalCustMobile"></span> </p>
                        </div>
                        <div class="col-lg-7 col-md-7 col-12">
                            <p class="text-right">Delivery address : <span id="modalCustDel"></span></p>
                            <p class="text-right">Billing Address : <span id="modalCustBil"></span></p>
                        </div>
                    </div>
                </div>
                <div class="order-list-header">
                    <h6>Payments Details</h6>
                    <div class="row">
                        <div class="col-lg-5 col-md-5 col-12">
                            <p>Transaction id : <span id="modalTranId"></span> </p>
                            <p>Mode of paymemt : <span id="modalPaymentMode"></span> </p>
                        </div>
                        <div class="col-lg-7 col-md-7 col-12">
                            <p class="text-right">Date and time : <span id="modalDatenTime"></span></p>
                            <p class="text-right">Payment status : <span id="modalPaymentStatus"></span></p>
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
                                <p>Courier Name : <span class="courierName"></span></p>
                                <p>Tracking Number : <span class="trackingNumber"></span></p>
                                <p>Dispatch Date : <span class="dispatchDate"></span></p>
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
    <div class="modal fade" id="requestModal" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel"
        aria-hidden="true">
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
                        <input type="hidden" id="orderId" name="orderId" value="">
                        <!-- Ensure name attribute for form submission -->
                        @csrf <!-- CSRF Token -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Send Request</button>
                        <!-- Change to type="submit" -->
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Cancel reason modal -->
    <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel"
        aria-hidden="true">
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
                        <input type="hidden" id="orderId" name="orderId" value="">
                        <!-- Ensure name attribute for form submission -->
                        @csrf <!-- CSRF Token -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Send Cancellation</button>
                        <!-- Change to type="submit" -->
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Courier Details -->
    <div class="modal fade" id="couriermodal" tabindex="-1" role="dialog" aria-labelledby="couriermodalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="couriermodalLabel">Enter Courier Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="courierform">
                    <div class="modal-body">
                        <input type="hidden" id="orderId" name="orderId" value="">
                        <div class="form-group">
                            <label for="dispatchDate">Dispatch Date</label>
                            <input type="date" id="dispatchDate" name="dispatchDate" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="courierName">Courier Name</label>
                            @php($couriers = App\Models\Couriername::where('status', 1)->get())
                            <select id="courierName" name="courierName" class="form-control" required>
                                <option value="">Select</option>
                                @foreach ($couriers as $courier)
                                    <option value="{{ $courier->id }}">{{ $courier->name }}</option>
                                @endforeach
                            </select>

                        </div>

                        <div class="form-group">
                            <label for="referenceNo">Reference No</label>
                            <input type="text" id="referenceNo" name="referenceNo" class="form-control"
                                placeholder="Enter Reference No" required>
                        </div>

                        <div class="form-group">
                            <label for="estimateDeliveryDate">Estimate Date of Delivery</label>
                            <input type="date" id="estimateDeliveryDate" name="estimateDeliveryDate"
                                class="form-control" required>
                        </div>

                        <input type="hidden" id="orderId" name="orderId" value="">

                        @csrf <!-- CSRF Token -->
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-warning">Save</button> <!-- Change to type="submit" -->
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- end courier details -->

    <!-- Delivery Details -->
    <div class="modal fade" id="deliverymodal" tabindex="-1" role="dialog" aria-labelledby="deliverymodalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deliverymodalLabel">Enter Delivery Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="deliveryform">
                    <div class="modal-body">
                        <input type="hidden" id="orderId" name="orderId" value="">
                        <div class="form-group">
                            <label for="deliverydate">Delivery On Date</label>
                            <input type="date" id="deliverydate" name="deliverydate" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="receivedby">Received By</label>
                            <input type="text" id="receivedby" name="receivedby" class="form-control"
                                placeholder="Enter Receivers name" required>
                        </div>

                        <input type="hidden" id="orderId" name="orderId" value="">

                        @csrf <!-- CSRF Token -->
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-warning">Save</button> <!-- Change to type="submit" -->
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- end Delivery Detials -->

    <!-- Upload Invoice -->
    <div class="modal fade" id="invoicemodal" tabindex="-1" role="dialog" aria-labelledby="invoicemodalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="invoicemodalLabel">Upload Invoice PDF</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="invoiceform" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" id="orderId" name="orderId" value="">
                        <div class="form-group">
                            <label for="invoiceupload">Upload</label>
                            <input type="file" id="invoiceupload" name="invoiceupload" class="form-control" required>
                        </div>
                        <input type="hidden" id="orderId" name="orderId" value="">

                        @csrf <!-- CSRF Token -->
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">upload</button> <!-- Change to type="submit" -->
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- end invoice modal -->

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

        function openCourierDetailsModal(orderId) {
            $('#orderId').val(orderId); // Set order ID in the hidden input
            $('#courierMessage').val(''); // Clear any previous message
            $('#couriermodal').modal('show'); // Show the modal
        }

        function openDeliveryDetailsModal(orderId) {
            $('#orderId').val(orderId); // Set order ID in the hidden input
            $('#deliverymodal').modal('show'); // Show the modal
        }

        function openInvoiceModal(orderId) {
            $('#orderId').val(orderId); // Set order ID in the hidden input
            $('#invoicemodal').modal('show'); // Show the modal
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
                    data: function(d) {}
                },
                columns: [{
                        data: 'DT_RowIndex', // Sl.No
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'orderDate', // Order Date
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
                            if (full.orderApproved == 0) {
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
                            } else if (full.orderApproved == 1) {
                                return `
                                    <a href="#" title="Enter Courier Details" onclick="openCourierDetailsModal(${data})" class="btn btn-sm bg-warning mr-2">
                                        <i class="fa-regular fa-truck"></i>
                                    </a>
                                    <a href="#" title="Enter PDF Copy of Invoice" onclick="openInvoiceModal(${data})" class="btn btn-sm bg-secondary mr-2">
                                        <i class="fa-regular fa-file-pdf"></i>
                                    </a>`;
                            } else if (full.orderApproved == 4) {
                                return `
                                    <a href="#" title="Enter Your Delivery Details" onclick="openDeliveryDetailsModal(${data})" class="btn btn-sm bg-primary mr-2">
                                        <i class="fa-regular fa-box"></i>
                                    </a>`;
                            } else if (full.orderApproved == 5) {
                                return '';
                            } else {
                                return '';
                            }
                        }
                    }
                ]
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
                const trackingInfo = order?.courierdetails ? JSON.parse(order?.courierDetails) : null;
                if(trackingInfo){
                    modal.find('.courierName').text(trackingInfo.courierName);
                    modal.find('.trackingNumber').text(trackingInfo.referenceNo);
                    modal.find('.dispatchDate').text(trackingInfo.dispatchDate);

                }
                // Set Order Details
                modal.find('#modalOrderId').text(order.id);
                modal.find('#modalOrderDate').text(order.orderDate);
                modal.find('#modalCustId').text(order.userId || 'N/A');
                modal.find('#modalCustName').text(order.name || 'N/A');
                // Assuming `order` is the data you posted above
                modal.find('#modalCustEmail').text(order.email || 'N/A');
                modal.find('#modalCustMobile').text(order.phoneNumber || 'N/A');

                // Delivery Address
                let deliveryAddress =
                    `${order.address || ''}, ${order.city || ''}, ${order.state || ''}, ${order.country || ''}, ${order.zipcode || ''}`;
                modal.find('#modalCustDel').text(deliveryAddress.trim() || 'N/A');

                // Billing Address
                if (order.sameBillingAddress === 1) {
                    // Same as shipping address
                    modal.find('#modalCustBil').text(deliveryAddress.trim() || 'N/A');
                } else {
                    let billingAddress =
                        `${order.billingaddress || ''}, ${order.billingcity || ''}, ${order.billingstate || ''}, ${order.billingcountry || ''}, ${order.billingzipcode || ''}`;
                    modal.find('#modalCustBil').text(billingAddress.trim() || 'N/A');
                }

                // Transaction ID and Payment Mode (use placeholders if missing)
                modal.find('#modalTranId').text(order.transactionId || 'N/A');
                modal.find('#modalPaymentMode').text(order.paymentMode || 'N/A');

                // Date and Time of the order
                modal.find('#modalDatenTime').text(order.orderDate || 'N/A');

                // Payment Status (use a placeholder, example: if paymentCompleted = 1, show "Completed")
                let paymentStatus = order.paymentCompleted ? 'Completed' : 'Pending';
                modal.find('#modalPaymentStatus').text(paymentStatus);
                modal.find('.modal-body').empty();

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
                // modal.find('.tracking-info p:nth-child(2)').text(
                //     'Tracking Number: Z06066552'); // Placeholder

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
                        _token: $('meta[name="csrf-token"]').attr(
                            'content') // Ensure CSRF token is included
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
                const cancellationReason = $('#cancelmessage').val();

                $.ajax({
                    url: '{{ route('admin.order.changeStatus') }}', // Directly use the route helper
                    type: 'POST',
                    data: {
                        orderId: orderId,
                        orderApproved: 0,
                        cancellationReason: cancellationReason,
                        _token: $('meta[name="csrf-token"]').attr(
                            'content') // Ensure CSRF token is included
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

        $(document).ready(function() {
            $('#courierform').on('submit', function(e) {
                e.preventDefault();

                const orderId = $('#orderId').val();
                const dispatchDate = $('#dispatchDate').val();
                const courierName = $('#courierName').val();
                const referenceNo = $('#referenceNo').val();
                const estimateDeliveryDate = $('#estimateDeliveryDate').val();

                $.ajax({
                    url: '{{ route('order.courierdetails') }}',
                    type: 'POST',
                    data: {
                        orderId: orderId,
                        dispatchDate: dispatchDate,
                        courierName: courierName,
                        referenceNo: referenceNo,
                        estimateDeliveryDate: estimateDeliveryDate,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert(response.message);
                        $('#couriermodal').modal('hide');
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON.message);
                    }
                });
            });
        });

        $(document).ready(function() {
            $('#deliveryform').on('submit', function(e) {
                e.preventDefault();

                const orderId = $('#orderId').val();
                const deliverydate = $('#deliverydate').val();
                const receivedby = $('#receivedby').val();

                $.ajax({
                    url: '{{ route('order.deliverydetails') }}',
                    type: 'POST',
                    data: {
                        orderId: orderId,
                        deliverydate: deliverydate,
                        receivedby: receivedby,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert(response.message);
                        $('#deliverymodal').modal('hide');
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON.message);
                    }
                });
            });
        });

        $(document).ready(function() {
            $('#invoiceform').on('submit', function(e) {
                e.preventDefault(); // Prevent form submission

                // Create a new FormData object
                var formData = new FormData(this); // 'this' refers to the form itself
                formData.append('orderId', $('#orderId').val());

                $.ajax({
                    url: '{{ route('order.invoiceupload') }}',
                    type: 'POST',
                    data: formData, // Send the FormData object containing the file
                    processData: false, // Prevent jQuery from processing the data
                    contentType: false, // Prevent jQuery from setting the content-type header
                    success: function(response) {
                        alert(response.message);
                        $('#invoicemodal').modal('hide'); // Close the modal on success
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON.message);
                    }
                });
            });
        });
    </script>

    <script>
        // Set the min attribute to today's date
        document.addEventListener('DOMContentLoaded', function() {
            var today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format
            document.getElementById('dispatchDate').setAttribute('min', today); // Set the min attribute
            document.getElementById('estimateDeliveryDate').setAttribute('min', today);
            document.getElementById('deliverydate').setAttribute('min', today);
        });
    </script>

    <!--end modal-->
@endsection
