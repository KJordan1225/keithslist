@extends('layouts.app')
@section('title', 'Manage Businesses')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-extrabold text-gray-900">Businesses</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-green-600 hover:underline">← Admin Home</a>
    </div>

    {{-- Filter Tabs --}}
    <div class="flex gap-1 mb-6 border-b border-gray-200">
        @foreach(['all' => 'All', 'pending' => 'Pending', 'active' => 'Active', 'suspended' => 'Suspended'] as $val => $label)
            <a href="{{ route('admin.businesses', $val !== 'all' ? ['status' => $val] : []) }}"
               class="px-4 py-2 text-sm font-semibold border-b-2 -mb-px transition
                      {{ request('status', 'all') === $val
                          ? 'border-green-600 text-green-700'
                          : 'border-transparent text-gray-500 hover:text-gray-800' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="space-y-4">
        @forelse($businesses as $business)
            <div class="bg-white rounded-2xl border border-gray-200 p-5">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center text-gray-500 font-bold shrink-0">
                        {{ strtoupper(substr($business->name, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3 flex-wrap">
                            <a href="{{ route('businesses.show', $business) }}"
                               class="font-bold text-gray-900 hover:text-green-600">
                                {{ $business->name }}
                            </a>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                                {{ $business->status === 'active'    ? 'bg-green-100 text-green-700' : '' }}
                                {{ $business->status === 'pending'   ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $business->status === 'suspended' ? 'bg-red-100 text-red-700' : '' }}">
                                {{ ucfirst($business->status) }}
                            </span>
                            @if($business->featured)
                                <span class="bg-yellow-50 text-yellow-600 text-xs font-semibold px-2.5 py-1 rounded-full">Featured</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-500 mt-0.5">
                            {{ $business->city }}, {{ $business->state }}
                            · Owner: {{ $business->user->name }} ({{ $business->user->email }})
                            · {{ $business->review_count }} reviews
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">Listed {{ $business->created_at->diffForHumans() }}</p>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-2 flex-wrap shrink-0">
                        @if($business->status === 'pending')
                            <form method="POST" action="{{ route('admin.businesses.approve', $business) }}">
                                @csrf
                                <button class="bg-green-600 text-white text-xs font-bold px-3 py-1.5 rounded-lg hover:bg-green-700 transition">
                                    Approve
                                </button>
                            </form>
                        @endif
                        @if($business->status !== 'suspended')
                            <form method="POST" action="{{ route('admin.businesses.suspend', $business) }}">
                                @csrf
                                <button class="bg-red-50 text-red-600 text-xs font-bold px-3 py-1.5 rounded-lg hover:bg-red-100 transition">
                                    Suspend
                                </button>
                            </form>
                        @endif
                        @if($business->status === 'active')
                            <form method="POST" action="{{ route('admin.businesses.featured', $business) }}">
                                @csrf
                                <button class="bg-yellow-50 text-yellow-700 text-xs font-bold px-3 py-1.5 rounded-lg hover:bg-yellow-100 transition">
                                    {{ $business->featured ? 'Unfeature' : 'Feature' }}
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('businesses.show', $business) }}"
                           class="border border-gray-200 text-gray-600 text-xs font-bold px-3 py-1.5 rounded-lg hover:bg-gray-50 transition">
                            View
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center text-gray-400">
                <p>No businesses found.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $businesses->links() }}</div>
</div>
@endsection
