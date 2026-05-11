@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #e9ecef; /* overall page grey */
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .committee-page {
        max-width: 1000px;
        margin: 50px auto;
        padding: 20px;
    }

    /* Form section */
    .add-form {
        background-color: #f0f0f0; /* grey form container */
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 30px;
        text-align: center;
        margin-bottom: 50px;
    }

    .form-box {
        background-color: #dcdcdc; /* inner form box grey */
        border-radius: 12px;
        padding: 40px;
        max-width: 700px;
        margin: 0 auto;
    }

    .add-form input,
    .add-form textarea,
    .add-form button {
        margin: 8px;
        padding: 10px;
        border-radius: 6px;
        border: 1px solid #bbb;
        width: 100%;
        max-width: 600px;
    }

    .add-form textarea {
        resize: none;
        height: 100px;
    }

    .add-form button {
        background-color: #007bff;
        color: white;
        border: none;
        cursor: pointer;
        width: 100%;
        max-width: 600px;
        padding: 10px;
        border-radius: 6px;
        transition: 0.3s;
    }

    .add-form button:hover {
        background-color: #0056b3;
        transform: scale(1.05);
    }

    /* Committee cards */
    .committee-list {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }

    .committee-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: grey;
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        padding: 25px;
        border-left: 6px solid #007bff;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .committee-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(120deg, rgba(0, 123, 255, 0.2), rgba(255, 255, 255, 0.1));
        transition: all 0.6s ease;
    }

    .committee-card:hover::before {
        left: 100%;
    }

    .committee-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0, 123, 255, 0.3);
    }

    .committee-left {
        flex: 0 0 35%;
        text-align: center;
        border-right: 2px solid #bfbfbf;
        padding-right: 20px;
    }

    .committee-left img {
        width: 180px;
        height: 180px;
        border-radius: 12px;
        object-fit: cover;
        margin-bottom: 10px;
        border: 3px solid #007bff;
        box-shadow: 0 0 15px rgba(0, 123, 255, 0.3);
        transition: 0.3s;
    }

    .committee-left img:hover {
        transform: scale(1.05);
        box-shadow: 0 0 25px rgba(0, 123, 255, 0.6);
    }

    .committee-left h4 {
        margin: 5px 0;
        font-size: 22px;
        color: #222;
        font-weight: 700;
        border-top: 1px solid #bbb;
        border-bottom: 1px solid #bbb;
        padding: 8px 0;
        letter-spacing: 1px;
    }

    .committee-left p {
        font-size: 15px;
        color: #f0f0f0;
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 8px;
        font-style: italic;
    }

    .committee-right {
        flex: 0 0 60%;
        font-size: 15px;
        color: #f0f0f0;
        line-height: 1.6;
        text-align: justify;
        padding-left: 20px;
        position: relative;
    }

    .committee-right::before {
        content: "“";
        font-size: 60px;
        color: #007bff;
        position: absolute;
        top: -20px;
        left: -10px;
        opacity: 0.2;
    }

    .remove-btn {
        background-color:#dc3545;
        color:white;
        border:none;
        padding:6px 10px;
        border-radius:5px;
        cursor:pointer;
        margin-top:10px;
        transition: 0.3s;
    }

    .remove-btn:hover {
        background-color:#b02a37;
        transform: scale(1.1);
    }
</style>

<div class="committee-page">
    <!-- Add Member Form -->
    <div class="add-form">
        <h2 style="text-align:center; font-weight:600; margin-bottom:25px;">
            Committee Members - {{ $club->name }}
        </h2>

        <div class="form-box">
            <form action="{{ route('clubs.committee.add', $club->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="text" name="name" placeholder="Member Name" required>
                <input type="text" name="role" placeholder="Role" required>
                <textarea name="description" placeholder="Description (optional)"></textarea>
                <input type="file" name="profile_picture">
                <button type="submit">Add Member</button>
            </form>
        </div>
    </div>

    <!-- Committee Cards -->
    <div class="committee-list">
        @forelse($committee as $member)
            <div class="committee-card">
                <div class="committee-left">
                    @if($member->profile_picture)
                        <img src="{{ asset('storage/' . $member->profile_picture) }}" alt="{{ $member->name }}">
                    @endif
                    <h4>{{ strtoupper($member->name) }}</h4>
                    <p>{{ strtoupper($member->role) }}</p>
                    <form action="{{ route('clubs.committee.remove', [$club->id, $member->id]) }}" method="POST" onsubmit="return confirm('Delete this member?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="remove-btn">Remove</button>
                    </form>
                </div>

                <div class="committee-right">
                    @if($member->description)
                        {{ $member->description }}
                    @else
                        <em>No description provided.</em>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-center">No committee members added yet.</p>
        @endforelse
    </div>
</div>
@endsection
