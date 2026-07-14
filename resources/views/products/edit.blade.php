@extends('layouts.app')
@section('title', 'Edit Product')
@section('heading', 'Edit Product')

@section('content')
<div class="max-w-xl bg-white border rounded-lg p-7">
    <form method="POST" action="{{ route('products.update', $product) }}" class="space-y-5">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Barcode (read-only)</label>
            <input disabled value="{{ $product->barcode }}" class="w-full px-4 py-2.5 border rounded font-mono bg-gray-50">
        </div>
        <div>
            <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Item Title</label>
            <input required name="name" value="{{ old('name', $product->name) }}" class="w-full px-4 py-2.5 border rounded">
        </div>
        <div>
            <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Category</label>
            <select required name="category" class="w-full px-4 py-2.5 border rounded">
                @foreach($categories as $name)
                    <option value="{{ $name }}" {{ $product->category === $name ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Brand</label>
                <select name="brand" class="w-full px-4 py-2.5 border rounded">
                    <option value="">No brand</option>
                    @foreach($brands as $b)
                        <option value="{{ $b->name }}" {{ $product->brand === $b->name ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Unit</label>
                <select name="unit" class="w-full px-4 py-2.5 border rounded">
                    <option value="Piece" {{ $product->unit === 'Piece' ? 'selected' : '' }}>Piece</option>
                    @foreach($units as $u)
                        <option value="{{ $u->name }}" {{ $product->unit === $u->name ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Price (৳)</label>
                <input required type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" class="w-full px-4 py-2.5 border rounded font-mono">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Quantity</label>
                <input required type="number" name="quantity" value="{{ old('quantity', $product->quantity) }}" class="w-full px-4 py-2.5 border rounded font-mono">
            </div>
        </div>
        <div class="flex gap-2">
            <button class="flex-1 bg-ink text-white py-3 font-bold text-sm uppercase rounded">Save Changes</button>
            <a href="{{ route('products.index') }}" class="flex-1 text-center border py-3 font-bold text-sm uppercase rounded">Cancel</a>
        </div>
    </form>
</div>
@endsection
