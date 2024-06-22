<!-- resources/views/pppoe/profile.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>PPPoE Profiles</h2>
    <form action="{{ route('pppoe.profile.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Profile Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="local_address">Local Address</label>
            <input type="text" class="form-control" id="local_address" name="local_address" required>
        </div>
        <div class="form-group">
            <label for="remote_address">Remote Address Pool</label>
            <input type="text" class="form-control" id="remote_address" name="remote_address" required>
        </div>
        <div class="form-group">
            <label for="dns_server">DNS Server</label>
            <input type="text" class="form-control" id="dns_server" name="dns_server" required>
        </div>
        <button type="submit" class="btn btn-primary">Create Profile</button>
    </form>

    <h3 class="mt-4">Existing Profiles</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Local Address</th>
                <th>Remote Address Pool</th>
                <th>DNS Server</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($profiles as $profile)
            <tr>
                <td>{{ $profile->name }}</td>
                <td>{{ $profile->local_address }}</td>
                <td>{{ $profile->remote_address }}</td>
                <td>{{ $profile->dns_server }}</td>
                <td>
                    <a href="{{ route('pppoe.profile.edit', $profile->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('pppoe.profile.destroy', $profile->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
