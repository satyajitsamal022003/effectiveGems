<?php

namespace App\Http\Controllers\eusers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EuserController extends Controller
{
    public function postsignin(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::guard('euser')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('euser.dashboard')->with('success', 'Login successful!');
        }

        return back()->withErrors(['Invalid credentials'])->withInput();
    }

    public function logout()
    {
        Auth::guard('euser')->logout();
        return redirect()->route('eusers.login')->with('message', 'Logged out successfully!');
    }

    public function dashboard(Request $request)
    {
        $user = Auth::guard('euser')->user();
        $ip = $request->getClientIp();
        $orderCount = Order::where(function ($query) use ($user, $ip) {
            $query->where('userId', $user->id)
                  ->orWhere(function ($q) use ($ip) {
                      $q->whereNull('userId')
                        ->where('ip', $ip);
                  });
        })
        ->whereNotIn('orderStatus', ['Failed'])
        ->count();

        $wishlistcount = Wishlist::where('user_id', $user->id)->count();
        return view('eusers.dashboard', compact('user', 'orderCount' ,'wishlistcount'));
    }

    public function myorderlist(Request $request)
    {
        $ip = $request->getClientIp();
        $user = Auth::guard('euser')->user();

        $query = Order::where(function ($query) use ($user, $ip) {
            $query->where('userId', $user->id)
                  ->orWhere(function ($q) use ($ip) {
                      $q->whereNull('userId')
                        ->where('ip', $ip);
                  });
        })
        ->whereNotIn('orderStatus', ['Failed']);


        if ($request->has('orderStatus') && !empty($request->orderStatus)) {
            $query->where('orderStatus', $request->orderStatus); 
        }

        $orders = $query->paginate(10); 

        if ($request->ajax()) {
            return view('eusers.myorders.order_table', compact('orders'))->render();
        }

        return view('eusers.myorders.list', compact('orders'));
    }


    public function ordersview($id)
    {
        $order = Order::with(['items.productDetails'])->find($id);

        return view('eusers.myorders.view', compact('order'));
    }

    public function wishlist()
    {
        return view('eusers.wishlist');
    }
}
