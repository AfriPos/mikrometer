<!-- resources/views/pppoe/user.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>PPPoE Users</h2>
    <form action="{{ route('pppoe.user.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Username</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="profile">Profile</label>
            <select class="form-control" id="profile" name="profile" required>
                @foreach ($profiles as $profile)
                    <option value="{{ $profile->id }}">{{ $profile->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Create User</button>
    </form>

    <h3 class="mt-4">Existing Users</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Username</th>
                <th>Profile</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->profile->name }}</td>
                <td>
                    <a href="{{ route('pppoe.user.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('pppoe.user.destroy', $user->id) }}" method="POST" style="display:inline-block;">
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
