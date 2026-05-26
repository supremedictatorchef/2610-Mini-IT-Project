<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    // Show user orders
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())->with('items.product')->get();
        return view('orders.index', compact('orders'));
    }

    // Checkout (store order)
    public function store(Request $request)
    {
        $cart = session('cart', []);
        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        $order = Order::create([
            'user_id' => auth()->id(),
            'club_id' => $request->club_id,
            'total' => $total,
            'status' => 'pending',
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        session()->forget('cart');
        return redirect()->route('orders.index')->with('success','Order placed!');
    }

    // Show single order
    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }

}

