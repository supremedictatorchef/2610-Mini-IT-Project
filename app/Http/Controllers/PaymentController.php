<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Club;
use App\Models\Treasurer;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Show the payment form for a product.
     */
    public function create(Product $product)
    {
        // Get the club that owns this product
        $club = $product->club;

        // Ensure treasurer exists (auto-create if missing)
        if (!$club->treasurer) {
            Treasurer::create([
                'club_id' => $club->id,
                'name' => 'Default Treasurer',
                'bank_name' => 'Not set',
                'account_number' => 'Not set',
                'qr_payment' => null,
            ]);
        }

        $treasurer = $club->treasurer;

        return view('marketplace.payment', compact('product', 'club', 'treasurer'));
    }

    /**
     * Store a submitted payment proof.
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'payer_name'     => 'required|string|max:255',
            'transaction_id' => 'required|string|max:255',
            'amount'         => 'required|numeric',
            'payment_date'   => 'required|date',
            'proof_image'    => 'required|image|max:2048',
        ]);

        $path = $request->file('proof_image')->store('payments', 'public');
        
        return redirect()->route('products.show', $product->id)
            ->with('success', 'Payment submitted, pending verification.');
    }
}
