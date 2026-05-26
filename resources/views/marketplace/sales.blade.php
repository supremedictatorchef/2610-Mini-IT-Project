<x-top-nav></x-top-nav>
@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #fff8f0;
        font-family: 'Poppins', sans-serif;
    }
    .sales-container {
        max-width: 900px;
        margin: 40px auto;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        padding: 30px;
    }
    h2 {
        color: #ff5722;
        font-weight: 700;
        margin-bottom: 25px;
        text-align: center;
    }
    .stats-box {
        display: flex;
        justify-content: space-around;
        margin-bottom: 30px;
    }
    .stat {
        background-color: #ffe0b2;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        width: 30%;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .stat h4 { color: #ff5722; margin-bottom: 10px; }
    .stat p { font-size: 1.2rem; font-weight: 600; color: #333; }
</style>

<div class="sales-container">
    <h2>{{ $product->title }} Sales Overview</h2>

    <div class="stats-box">
        <div class="stat">
            <h4>Total Units Sold</h4>
            <p>{{ $product->sales_count ?? 0 }}</p>
        </div>
        <div class="stat">
            <h4>Total Revenue (RM)</h4>
            <p>{{ number_format($product->sales_total ?? 0, 2) }}</p>
        </div>
        <div class="stat">
            <h4>Remaining Stock</h4>
            <p>{{ $product->stock }}</p>
        </div>
    </div>

    <h4 style="color:#ff5722;">Recent Transactions</h4>
    <table class="table mt-3">
        <thead>
            <tr>
                <th>Date</th>
                <th>Buyer</th>
                <th>Quantity</th>
                <th>Total (RM)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
                <tr>
                    <td>{{ $sale->created_at->format('d M Y') }}</td>
                    <td>{{ $sale->buyer_name }}</td>
                    <td>{{ $sale->quantity }}</td>
                    <td>{{ number_format($sale->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
