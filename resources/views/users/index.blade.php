@extends('layouts.app')
@section('title', 'Staff Accounts')
@section('heading', 'Staff Accounts')

@section('content')
<div class="bg-white border rounded-lg">
    <div class="flex items-center justify-between px-6 pt-6 pb-4">
        <h3 class="font-bold text-sm">Admin &amp; Salesman Logins</h3>
        <a href="{{ route('users.create') }}" class="bg-ink text-white px-4 py-2 text-xs font-bold uppercase rounded">+ New Account</a>
    </div>
    <div class="divide-y">
        @foreach($users as $u)
            <div class="px-6 py-4 flex items-center justify-between gap-4">
                <div>
                    <p class="font-bold text-sm">{{ $u->name }} @if($u->id === auth()->id())<span class="text-gray-400 font-normal">(you)</span>@endif</p>
                    <p class="text-xs text-gray-500 font-mono">{{ $u->email }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-[10px] px-2 py-0.5 font-bold uppercase rounded {{ $u->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">{{ $u->role }}</span>
                    <span class="text-[10px] px-2 py-0.5 font-bold uppercase rounded {{ $u->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ $u->is_active ? 'active' : 'disabled' }}</span>
                    @if($u->id !== auth()->id())
                        <form method="POST" action="{{ route('users.toggle', $u) }}">
                            @csrf
                            <button class="text-xs font-bold uppercase text-brass">{{ $u->is_active ? 'Deactivate' : 'Activate' }}</button>
                        </form>
                        <form method="POST" action="{{ route('users.destroy', $u) }}" onsubmit="return confirm('Delete {{ $u->name }} permanently?')">
                            @csrf @method('DELETE')
                            <button class="text-xs font-bold uppercase text-red-500">Delete</button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
