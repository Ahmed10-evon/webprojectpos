@extends('layouts.app')
@section('title', 'New Account')
@section('heading', 'New Staff Account')

@section('content')
<div class="max-w-md bg-white border rounded-lg p-6">
    <form method="POST" action="{{ route('users.store') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Full Name</label>
            <input required name="name" value="{{ old('name') }}" class="w-full px-4 py-2.5 border rounded">
        </div>
        <div>
            <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Email (used to log in)</label>
            <input required type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2.5 border rounded font-mono">
        </div>
        <div>
            <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Password</label>
            <input required type="password" name="password" class="w-full px-4 py-2.5 border rounded">
        </div>
        <div>
            <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Confirm Password</label>
            <input required type="password" name="password_confirmation" class="w-full px-4 py-2.5 border rounded">
        </div>
        <div>
            <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Role</label>
            <select required name="role" class="w-full px-4 py-2.5 border rounded">
                <option value="salesman">Salesman — POS, Products view, Sales, Membership</option>
                <option value="admin">Admin — full access</option>
            </select>
        </div>
        <button class="w-full bg-ink text-white py-2.5 rounded text-xs font-bold uppercase">Create Account</button>
    </form>
</div>
@endsection
