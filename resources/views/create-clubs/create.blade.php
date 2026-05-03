@extends('layouts.app')

@section('content')
<div class="form-container">
    <h2 class="form-title">Create Club</h2>
    <p class="form-subtitle">Fill in the details below</p>

    <form action="{{ route('create-clubs.store') }}" method="POST" enctype="multipart/form-data" class="styled-form">
        @csrf

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name">
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description"></textarea>
        </div>

        <div class="form-group">
            <label for="profile_picture">Profile_Picture</label>
            <input type="file" name="profile_picture" id="profile_picture">
        </div>

        <div class="form-group">
            <label for="category">Category</label>
            <select name="category" id="category">
                <option value="Arts Clubs">Art Clubs</option>
                <option value="Community Clubs">Community Clubs</option>
                <option value="Religious Clubs">Religious Clubs</option>
                <option value="Games / Entertainment Clubs">Games / Entertainment Clubs</option>
                <option value="Cultural Clubs">Cultural Clubs</option>
                <option value="Tech Clubs">Tech Clubs</option>
                <option value="Recreational / Physical Activities Clubs">Recreational / Physical Activities Clubs</option>
            </select>
        </div>

        <button type="submit" class="btn-submit">Create Club</button>
    </form>
</div>
@endsection




