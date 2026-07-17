@extends('layouts.app')
@section('title', 'Profile')
@section('heading', 'My Profile')

@section('content')
<div class="max-w-lg space-y-6">
    <div class="bg-white border rounded-lg p-6">
        <h3 class="font-bold text-sm mb-4">Profile Information</h3>
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf
            @method('PATCH')
            <div>
                <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Name</label>
                <input required name="name" value="{{ old('name', $user->name) }}" class="w-full px-4 py-2.5 border rounded">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Email</label>
                <input required type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-2.5 border rounded font-mono">
            </div>
            <button class="bg-ink text-white px-5 py-2.5 rounded text-xs font-bold uppercase">Save</button>
        </form>
    </div>

    <div class="bg-white border rounded-lg p-6">
        <h3 class="font-bold text-sm mb-4">Update Password</h3>
        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Current Password</label>
                <input type="password" name="current_password" class="w-full px-4 py-2.5 border rounded">
                @error('current_password', 'updatePassword')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-gray-500 mb-2">New Password</label>
                <input type="password" name="password" class="w-full px-4 py-2.5 border rounded">
                @error('password', 'updatePassword')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="w-full px-4 py-2.5 border rounded">
            </div>
            <button class="bg-ink text-white px-5 py-2.5 rounded text-xs font-bold uppercase">Update Password</button>
        </form>
    </div>
</div>
@endsection
