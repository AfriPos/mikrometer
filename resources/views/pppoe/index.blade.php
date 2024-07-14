<!-- resources/views/pppoe/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>PPPoE Services</h2>
        <a href="{{ route('pppoe.create') }}" class="btn btn-primary mb-3">Create New Service</a>
        <table class="table">
            <thead>
                <tr>
                    <th>Interface</th>
                    <th>Service Name</th>
                    <th>Max MTU</th>
                    <th>Max MRU</th>
                    <th>Profile</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($services as $service)
                    <tr>
                        <td>{{ $service->interface }}</td>
                        <td>{{ $service->service_name }}</td>
                        <td>{{ $service->max_mtu }}</td>
                        <td>{{ $service->max_mru }}</td>
                        <td>{{ $service->profile->name }}</td>
                        <td>
                            <a href="{{ route('pppoe.edit', $service->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('pppoe.destroy', $service->id) }}" method="POST"
                                style="display:inline-block;">
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
