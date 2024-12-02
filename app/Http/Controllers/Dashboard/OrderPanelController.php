<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderPanelController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->input('search', '');
        $orders = Order::with(['request', 'provider', 'customer'])
                        ->search($searchTerm)
                        ->orderBy('id', 'DESC')
                        ->paginate(20);

        return view('portal.orders.index', compact('orders'));
    }
    public function show(Order $order)
    {
        $order->load(['request.category', 'provider.user', 'customer']);
        return view('portal.orders.show', compact('order'));
    }
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }
}
