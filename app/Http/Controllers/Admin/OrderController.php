<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusUpdated;
use App\Mail\OrderRequestToCustomer;
use App\Mail\OrderCourierDetails;
use App\Mail\OrderdeliveryDetails;
use App\Mail\OrderinvoiceDetails;
use App\Models\Couriername;
use App\Models\Setting;
use App\Models\State;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        return view('admin.orders.list');
    }
    public function pendingOrdersList()
    {
        return view('admin.orders.pendingOrders');
    }
    public function getOrdersData(Request $request)
    {

        $draw = intval($request->input('draw'));
        $length = intval($request->input('length'));
        $pageNo = intval($request->input('start'));
        $skip = $pageNo;
        $searchValue = $request->input('search.value');
        $orderStatus = $request->input('orderStatus');
        // $category = $request->input('category');
        // Base query to fetch orders and eager load order items and their related products
        $query = Order::with(['items.productDetails']); // Eager load order items and product details

        // Apply search filtering if needed
        if (!empty($searchValue)) {
            $query->where(function ($query) use ($searchValue) {
                $query->where('firstName', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('middleName', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('lastName', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('userId', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('amount', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('ip', 'LIKE', '%' . $searchValue . '%');
            });
        }


        // Apply filtering by category if applicable
        $query->where('orderStatus','!=', 'Failed');
        if (!empty($orderStatus)) {
            $query->where('orderStatus', $orderStatus);
        }

        // Get the total filtered records count
        $totalFilteredRecords = $query->count();

        // Apply pagination
        $orders = $query // Filter by payment completed
            ->orderBy('created_at', 'desc')
            ->skip($skip)
            ->take($length)
            ->get();

        // Get the total record count without filtering
        $totalRecords = Order::count();

        // Map the orders to include a custom index and return the data

        $data = $orders->map(function ($order, $key) use ($pageNo) {
            $order->DT_RowIndex = $pageNo + $key + 1;
            $specificDate = new DateTime($order->created_at);
            $order->orderDate = $specificDate->format('d-m-Y h:iA');
            $order->state = State::find($order->state)->stateName;
            $order->country = "India";
            $order->name = $order->firstName . " " . $order->middleName  . " " . $order->lastName;
            // You can add extra details here if needed
            foreach ($order->items as $key => $item) {
                $item->productDetails->image = asset($item->productDetails->image1);
            }
            return $order;
        });

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFilteredRecords,
            'data' => $data
        ]);
    }
    public function getPendingOrdersData(Request $request)
    {

        $draw = intval($request->input('draw'));
        $length = intval($request->input('length'));
        $pageNo = intval($request->input('start'));
        $skip = $pageNo;
        $searchValue = $request->input('search.value');
        $orderStatus = $request->input('orderStatus');
        // $category = $request->input('category');
        // Base query to fetch orders and eager load order items and their related products
        $query = Order::with(['items.productDetails']); // Eager load order items and product details

        // Apply search filtering if needed
        if (!empty($searchValue)) {
            $query->where(function ($query) use ($searchValue) {
                $query->where('firstName', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('middleName', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('lastName', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('userId', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('amount', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('ip', 'LIKE', '%' . $searchValue . '%');
            });
        }


        // Apply filtering by category if applicable
        $query->where('orderStatus', 'Failed');

        // Get the total filtered records count
        $totalFilteredRecords = $query->count();

        // Apply pagination
        $orders = $query // Filter by payment completed
            ->orderBy('created_at', 'desc')
            ->skip($skip)
            ->take($length)
            ->get();

        // Get the total record count without filtering
        $totalRecords = Order::count();

        // Map the orders to include a custom index and return the data

        $data = $orders->map(function ($order, $key) use ($pageNo) {
            $order->DT_RowIndex = $pageNo + $key + 1;
            $specificDate = new DateTime($order->created_at);
            $order->orderDate = $specificDate->format('d-m-Y h:iA');
            $order->state = State::find($order->state)->stateName;
            $order->country = "India";
            $order->name = $order->firstName . " " . $order->middleName  . " " . $order->lastName;
            // You can add extra details here if needed
            foreach ($order->items as $key => $item) {
                $item->productDetails->image = asset($item->productDetails->image1);
            }
            return $order;
        });

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFilteredRecords,
            'data' => $data
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function changeStatus(Request $req)
    {
        $orderId = $req->orderId;
        $orderApproved = $req->orderApproved; // 1 for approved, 0 for canceled


        $cancellationReason = $req->cancellationReason;
        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Update the order status
        $order->orderApproved = $orderApproved;
        if ($orderApproved == 0 && !empty($cancellationReason)) {
            // Save the cancellation reason if the order is canceled
            $order->cancellation_reason = $cancellationReason; // Ensure you have this field in your orders table
        }
        if ($orderApproved == 1) {
            $order->orderStatus = 'Approved ';
        }


        $order->save();

        // Prepare response
        $response = response()->json(['message' => 'Order status updated']);

        // Send email after response
        $this->sendOrderStatusEmail($order, $orderApproved, $cancellationReason);

        // Return the response immediately
        return $response;
    }

    protected function sendOrderStatusEmail(Order $order, $orderApproved, $cancellationReason = null)
    {
        if ($orderApproved == 1) {
            Mail::to($order->email)->send(new OrderStatusUpdated($order, 'approved'));
        } else {
            Mail::to($order->email)->send(new OrderStatusUpdated($order, 'canceled', $cancellationReason));
        }
    }



    public function requestToCustomer(Request $request)
    {
        $order = Order::find($request->orderId); // Fetch the order by ID
        $message = $request->message; // Get the message from the request

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Ensure that the message is always a string
        if (is_array($message)) {
            $message = implode(', ', $message); // Convert array to a comma-separated string
        } elseif (is_object($message)) {
            $message = json_encode($message); // Convert object to a JSON string
        }

        $order->request_to_customer = $message;
        $order->orderApproved = 3;
        $order->orderStatus = 'Requested ';
        $order->save();

        // Send the email using the Mailable
        Mail::to($order->email)->send(new OrderRequestToCustomer($order, $message));

        return response()->json(['message' => 'Request sent successfully!']);
    }

    public function courierdetails(Request $request)
    {
        $order = Order::find($request->orderId);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // if ($order->invoiceDetails == null) {
        //     return response()->json(['message' => 'Please upload invoice first'], 400);
        // }

        $courierDetails = [
            'dispatchDate' => $request->dispatchDate,
            'courierName' => $request->courierName,
            'referenceNo' => $request->referenceNo,
            'estimateDeliveryDate' => $request->estimateDeliveryDate
        ];

        $order->courierdetails = json_encode($courierDetails);

        $order->orderApproved = 4;
        $order->orderStatus = 'Dispatched ';

        $order->save();

        $message = "Here are your courier details:\n"
            . "Dispatch Date: " . $courierDetails['dispatchDate'] . "\n"
            . "Courier Name: " . $courierDetails['courierName'] . "\n"
            . "Reference No: " . $courierDetails['referenceNo'] . "\n"
            . "Estimated Date of Delivery: " . $courierDetails['estimateDeliveryDate'];

        Mail::to($order->email)->send(new OrderCourierDetails($order, $message));

        return response()->json(['message' => 'Courier Details Added Successfully!']);
    }

    public function deliverydetails(Request $request)
    {
        $order = Order::find($request->orderId);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $deliveryDetails = [
            'deliverydate' => $request->deliverydate,
            'receivedby' => $request->receivedby,
        ];

        $order->deliverydetails = json_encode($deliveryDetails);

        $order->orderApproved = 5;
        $order->orderStatus = 'Delivered ';

        $order->save();

        $message = "Here are your Delivery details:\n"
            . "Delivery Date: " . $deliveryDetails['deliverydate'] . "\n"
            . "Receiver Name: " . $deliveryDetails['receivedby'] . "\n";

        Mail::to($order->email)->send(new OrderdeliveryDetails($order, $message));

        return response()->json(['message' => 'Order Details Added Successfully!']);
    }



    public function invoiceupload(Request $request)
    {
        $order = Order::find($request->orderId);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($request->hasFile('invoiceupload')) {
            $file = $request->file('invoiceupload');
            $filename = time() . '_' . $file->getClientOriginalName();

            $file->move(public_path('uploads'), $filename);

            $order->invoiceDetails = $filename;
        } else {
            return response()->json(['message' => 'No invoice file uploaded'], 400);
        }

        // Save the order details in the database
        $order->save();

        // Send the email with the attached invoice PDF
        Mail::to($order->email)->send(new OrderinvoiceDetails($order, $filename));

        return response()->json(['message' => 'Order Details and Invoice Added Successfully!']);
    }







    public function acceptOrder($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return redirect()->back()->with('message', 'Order not found');
        }

        // Approve the order
        $order->orderApproved = 1; // Assuming 1 means approved
        $order->orderStatus = 'Accepted ';
        $order->save();

        // Optionally, send an email to confirm acceptance
        Mail::to($order->email)->send(new OrderStatusUpdated($order, 'approved'));

        return redirect()->back()->with('message', 'Order has been approved.');
    }

    public function cancelOrder($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return redirect()->back()->with('message', 'Order not found');
        }

        // Cancel the order
        $order->orderApproved = 2; // Assuming 0 means canceled
        $order->orderStatus = 'Cancelled ';
        $order->save();

        // Optionally, send an email to confirm cancellation
        Mail::to($order->email)->send(new OrderStatusUpdated($order, 'canceled'));

        return redirect()->back()->with('message', 'Order has been canceled.');
    }

    public function getCourierName(Request $request)
    {
        $courier = Couriername::find($request->courier_id);

        if ($courier) {
            return response()->json(['courierName' => $courier->name]);
        }

        return response()->json(['courierName' => 'Unknown'], 404);
    }

    public function addsetting()
    {
        $setting = Setting::first();
        return view('admin.settings', compact('setting'));
    }

    public function storesetting(Request $request)
    {
        // Use updateOrCreate to insert or update the record
        Setting::updateOrCreate(
            ['id' => 1], // Replace with the condition for finding an existing record
            [
                'header_script' => $request->get('header_script'),
                'footer_script' => $request->get('footer_script'),
            ]
        );

        // Redirect to a specified route with a success message
        return back()->with('message', 'Setting saved successfully');
    }
}
