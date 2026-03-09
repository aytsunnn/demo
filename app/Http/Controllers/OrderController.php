<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PickupPoint;
use App\Models\Product;
use App\Models\Status;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        if (!auth()->check() || !in_array(auth()->user()->role_id, [1, 2])) {
            abort(403, 'Доступ запрещен');
        }

        $orders = Order::with(['status','pickupPoint','items.product'])->get();


        return view('orders.order', compact('orders'));
    }

    public function create()
    {
        if (!auth()->check()) {
            abort(403, 'Доступ запрещен');
        }

        $products = Product::all();
        $pickupPoints = PickupPoint::all();
        $statuses = Status::all();

        return view('orders.create', compact('products', 'pickupPoints', 'statuses'));
    }

    public function store(Request $request)
    {
        if (!auth()->check()) {
            abort(403, 'Доступ запрещен');
        }

        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date',
            'pickupPoints_id' => 'required|exists:pickupPoints,id',
            'status_id' => 'required|exists:statuses,id',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $order = Order::create([
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'pickupPoints_id' => $request->pickupPoints_id,
            'user_id' => auth()->id(),
            'code' => rand(100000, 999999),
            'status_id' => $request->status_id,
        ]);

        foreach ($request->products as $item) {
            $order->items()->create([
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
            ]);
        }

        return redirect()->route('orders')->with('success', 'Заказ создан');
    }

    public function edit($id)
    {
        if (!auth()->check() || auth()->user()->role_id !== 1) {
            abort(403, 'Доступ запрещен');
        }

        $order = Order::with('items.product')->findOrFail($id);
        $products = Product::all();
        $pickupPoints = PickupPoint::all();
        $statuses = Status::all();

        return view('orders.edit', compact('order', 'products', 'pickupPoints', 'statuses'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->check() || auth()->user()->role_id !== 1) {
            abort(403, 'Доступ запрещен');
        }

        $order = Order::findOrFail($id);

        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date',
            'pickupPoints_id' => 'required|exists:pickupPoints,id',
            'status_id' => 'required|exists:statuses,id',
        ]);

        $order->update([
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'pickupPoints_id' => $request->pickupPoints_id,
            'status_id' => $request->status_id,
        ]);

        $order->items()->delete();

        if ($request->products) {
            foreach ($request->products as $item) {
                $order->items()->create([
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                ]);
            }
        }

        return redirect()->route('orders')->with('success', 'Заказ обновлен');
    }

    public function destroy($id)
    {
        if (!auth()->check() || auth()->user()->role_id !== 1) {
            abort(403, 'Доступ запрещен');
        }

        $order = Order::findOrFail($id);

        // Сначала удаляем связанные товары
        $order->items()->delete();

        // Затем удаляем сам заказ
        $order->delete();

        return redirect()->route('orders')->with('success', 'Заказ удален');
    }
}
