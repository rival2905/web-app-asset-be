@extends('layouts.admin') {{-- ganti sesuai layout kamu --}}

@section('title', 'Available Assets')

@section('content')
    <h1>Available Assets</h1>

    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Item Code</th>
                <th>Stock</th>
                <th>Material</th>
                <th>Brand</th>
            </tr>
        </thead>
        <tbody>
            @forelse($assets as $asset)
                <tr>
                    <td>{{ $asset->id }}</td>
                    <td>{{ $asset->name }}</td>
                    <td>{{ $asset->item_code ?? '-' }}</td>
                    <td>{{ $asset->stock }}</td>
                    <td>{{ $asset->assetMaterial->name ?? '-' }}</td>
                    <td>{{ $asset->brand->name ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No available assets.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
