<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Club;
 use App\Models\Treasurer;    

class ProductController extends Controller
{
    // Show products for a specific club
    public function index(Request $request, Club $club)
    {
        $query = Product::with('club')->where('club_id', $club->id);

        if ($request->search) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        if ($request->sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($request->sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($request->sort === 'latest') {
            $query->latest();
        }

        $products = $query->paginate(12);

        return view('marketplace.index', compact('products', 'club'));
    }

    // Admin: show form
    public function create(Club $club)
    {
        return view('marketplace.create', compact('club'));
    }

    // Admin: store product
    public function store(Request $request, Club $club)
    {
        $request->validate([
            'title' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        Product::create([
            'club_id' => $club->id,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $request->file('image')?->store('products','public'),
            'stock' => $request->stock,
        ]);

        return redirect()->route('clubs.marketplace', $club->id)
                         ->with('success','Product added!');
    }

    // Show product detail
   public function show(Product $product)
{
    return view('marketplace.show', compact('product'));
}

    // Admin: edit product
    public function edit(Product $product)
    {
        return view('marketplace.edit', compact('product'));
    }

    // Admin: update product
    public function update(Request $request, Product $product)
    {
        $product->update($request->only('title','description','price','stock'));

        return redirect()->route('clubs.marketplace', $product->club_id)
                         ->with('success','Product updated!');
    }

    // Admin: delete product
    public function destroy(Product $product)
    {
        $clubId = $product->club_id;
        $product->delete();

        return redirect()->route('clubs.marketplace', $clubId)
                         ->with('success','Product deleted!');
    }

public function adminDashboard(Club $club)
{
    // Ensure treasurer record exists for this club
    if (!$club->treasurer) {
        Treasurer::create([
            'club_id' => $club->id,
            'name' => 'Default Treasurer',
            'bank_name' => 'Not set',
            'account_number' => 'Not set',
            'qr_payment' => null,
        ]);
    }

    $products = $club->products ?? collect();
    $treasurer = $club->treasurer;

    return view('marketplace.admin', compact('club', 'products', 'treasurer'));
}



public function updateTreasurer(Request $request, Club $club)
{
    $treasurer = $club->treasurer ?? new \App\Models\Treasurer(['club_id' => $club->id]);

    $treasurer->name = $request->treasurer_name;
    $treasurer->bank_name = $request->treasurer_bank;
    $treasurer->account_number = $request->treasurer_account;

    if ($request->hasFile('treasurer_qr')) {
        $path = $request->file('treasurer_qr')->store('treasurer_qr', 'public');
        $treasurer->qr_payment = $path;
    }

    $treasurer->save();

    return response()->json(['success' => true]);
}


    public function sales(Product $product)
{
    // Get all order items for this product
    $sales = $product->orderItems()->with('order')->latest()->get();

    // Totals
    $totalQuantity = $product->orderItems()->sum('quantity');
    $totalRevenue  = $product->orderItems()->sum('amount');

    return view('marketplace.sales', compact('product', 'sales', 'totalQuantity', 'totalRevenue'));
}


}
