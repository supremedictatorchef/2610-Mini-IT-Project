<x-top-nav></x-top-nav>
@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #fff8f0;
        font-family: 'Poppins', sans-serif;
    }

    .sales-container {
        max-width: 1000px;
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
        flex-wrap: wrap;
    }

    .stat {
        background-color: #ffe0b2;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        width: 30%;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        margin-bottom: 10px;
    }

    .stat h4 { color: #ff5722; margin-bottom: 10px; }
    .stat p { font-size: 1.2rem; font-weight: 600; color: #333; }

    h3.sub-header {
        color: #ff5722;
        font-weight: 700;
        text-align: center;
        margin-top: 40px;
        border-bottom: 2px solid #ffcc80;
        display: inline-block;
        padding-bottom: 5px;
    }

    /* ✅ Table Styling */
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 25px;
    }

    .table th, .table td {
        padding: 12px 16px;
        text-align: center;
        border-bottom: 1px solid #ffcc80;
    }

    .table th {
        background-color: #ffe0b2;
        color: #ff5722;
        font-weight: 700;
    }

    .table td {
        background-color: #fffaf3;
        color: #333;
    }

    .table a {
        color: #1976d2;
        font-weight: 600;
        text-decoration: none;
    }

    .table a:hover {
        text-decoration: underline;
    }

    .btn-sm.btn-success {
        background-color: #ff5722;
        border: none;
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 600;
        color: #fff;
        transition: 0.3s;
    }

    .btn-sm.btn-success:hover {
        background-color: #e64a19;
    }

    .badge.bg-success {
        background-color: #4CAF50;
        color: #fff;
        padding: 6px 10px;
        border-radius: 6px;
    }

    .badge.bg-warning {
        background-color: #FFC107;
        color: #fff;
        padding: 6px 10px;
        border-radius: 6px;
    }
</style>

<div class="sales-container">
    {{-- 🔸 Overview Section --}}
    <h2>{{ $product->title }} Sales Overview</h2>

    <div class="stats-box">
        <div class="stat">
            <h4>Total Units Sold</h4>
            <p>{{ $totalQuantity ?? 0 }}</p>
        </div>
        <div class="stat">
            <h4>Total Revenue (RM)</h4>
            <p>{{ number_format($totalRevenue ?? 0, 2) }}</p>
        </div>
        <div class="stat">
            <h4>Remaining Stock</h4>
            <p>{{ $product->stock }}</p>
        </div>
    </div>

    {{-- 🔸 Sub-header --}}
    <h3 class="sub-header">Your Orders</h3>

    {{-- 🔸 Orders Table --}}
    <table class="table mt-4">
        <thead>
            <tr>
                <th>Date</th>
                <th>Payer Name</th>
                <th>Amount (RM)</th>
                <th>Proof</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $order)
                <tr>
                    <td>{{ $order->payment_date }}</td>
                    <td>{{ $order->payer_name }}</td>
                    <td>{{ number_format($order->amount, 2) }}</td>
                    <td>
                        @if($order->proof_image)
                            <a href="{{ asset('storage/'.$order->proof_image) }}" target="_blank">View</a>
                        @else
                            <span class="text-muted">No proof</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $order->verification_status == 'verified' ? 'bg-success' : 'bg-warning' }}">
                            {{ ucfirst($order->verification_status) }}
                        </span>
                    </td>
                    <td>
                        @if($order->verification_status == 'pending')
                            <form action="{{ route('orders.verify', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">Verify</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-muted text-center">No payments yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
