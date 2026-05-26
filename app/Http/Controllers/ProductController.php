<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Club;    

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
}
