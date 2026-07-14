@extends('layouts.app')
@section('title', 'Members')
@section('heading', 'Members List')

@section('content')
<div class="bg-white border rounded-lg">
    <form method="GET" class="p-6 pb-4">
        <input type="text" name="q" value="{{ $search }}" placeholder="Search by phone..." class="w-full px-4 py-2.5 border rounded text-sm">
    </form>
    <div class="divide-y">
        @forelse($members as $m)
            @php $daysLeft = $m->daysLeft(); @endphp
            <div class="px-6 py-4 flex items-center justify-between gap-4">
                <div>
                    <p class="font-bold text-sm font-mono">{{ $m->phone }}</p>
                    <p class="text-xs text-gray-500">{{ $m->note }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-[10px] px-2 py-0.5 font-bold uppercase rounded
                        {{ $m->status === 'revoked' ? 'bg-gray-100 text-gray-500' : ($daysLeft <= 0 ? 'bg-red-100 text-red-600' : ($daysLeft <= 14 ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700')) }}">
                        {{ $m->status === 'revoked' ? 'revoked' : ($daysLeft <= 0 ? 'expired' : $daysLeft.' days left') }}
                    </span>
                    @if($m->status === 'active')
                        <form method="POST" action="{{ route('membership.renew', $m) }}">
                            @csrf
                            <button class="px-3 py-1.5 border rounded text-xs font-bold uppercase">Renew +1y</button>
                        </form>
                        <form method="POST" action="{{ route('membership.revoke', $m) }}" onsubmit="return confirm('Revoke this membership?')">
                            @csrf
                            <button class="text-red-500 text-xs font-bold uppercase">Revoke</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-center py-12 text-gray-500 text-sm">No members found.</p>
        @endforelse
    </div>
</div>
@endsection
