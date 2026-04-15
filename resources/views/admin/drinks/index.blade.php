@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <p class="eyebrow mb-1">Admin panel</p>
            <h1 class="section-title mb-0">Manage drinks</h1>
        </div>
        <a href="{{ route('admin.drinks.create') }}" class="btn btn-cta">Add drink</a>
    </div>

    <div class="card glass-card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Drink</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($drinks as $drink)
                        <tr>
                            <td>{{ $drink->name }}</td>
                            <td>{{ $drink->category->name }}</td>
                            <td>PHP {{ number_format($drink->price, 2) }}</td>
                            <td>{{ $drink->stock }}</td>
                            <td>{{ $drink->is_available ? 'Available' : 'Hidden' }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.drinks.edit', $drink) }}" class="btn btn-sm btn-outline-dark">Edit</a>
                                <form action="{{ route('admin.drinks.destroy', $drink) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $drinks->links() }}</div>
</div>
@endsection
