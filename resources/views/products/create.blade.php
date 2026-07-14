@extends('layouts.app')
@section('title', 'Add Product')
@section('heading', 'Add Product')

@section('content')
<div class="max-w-xl bg-white border rounded-lg p-7">
    <form method="POST" action="{{ route('products.store') }}" class="space-y-5">
        @csrf
        <div>
            <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Barcode</label>
            <input required name="barcode" value="{{ old('barcode') }}" class="w-full px-4 py-2.5 border rounded font-mono" placeholder="Scan or type...">
        </div>
        <div>
            <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Item Title</label>
            <input required name="name" value="{{ old('name') }}" class="w-full px-4 py-2.5 border rounded" placeholder="e.g., Floral Summer Maxi Product">
        </div>
        <div>
            <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Category</label>
            <select required name="category" class="w-full px-4 py-2.5 border rounded">
                <option value="" disabled selected>Select a category...</option>
                @foreach($categories as $name)
                    <option value="{{ $name }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Brand</label>
                <select name="brand" class="w-full px-4 py-2.5 border rounded">
                    <option value="">No brand</option>
                    @foreach($brands as $b)
                        <option value="{{ $b->name }}">{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Unit</label>
                <select name="unit" class="w-full px-4 py-2.5 border rounded">
                    <option value="Piece">Piece</option>
                    @foreach($units as $u)
                        <option value="{{ $u->name }}">{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Unit Price (৳)</label>
                <input required type="number" step="0.01" name="price" value="{{ old('price') }}" class="w-full px-4 py-2.5 border rounded font-mono">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Quantity</label>
                <input required type="number" name="quantity" value="{{ old('quantity', 1) }}" class="w-full px-4 py-2.5 border rounded font-mono">
            </div>
        </div>
        <button class="w-full bg-ink text-white py-3 font-bold text-sm uppercase rounded">Save to Database</button>
    </form>
</div>
@endsection
