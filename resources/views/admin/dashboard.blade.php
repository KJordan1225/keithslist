@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-extrabold text-gray-900 mb-8">Admin Dashboard</h1>

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-5 mb-10">
        @foreach([
            ['label' => 'Total Businesses', 'value' => $totalBusinesses,   'color' => 'blue',   'icon' => '🏢'],
            ['label' => 'Pending Approval', 'value' => $pendingBusinesses,  'color' => 'yellow', 'icon' => '⏳'],
            ['label' => 'Total Reviews',    'value' => $totalReviews,       'color' => 'green',  'icon' => '⭐'],
            ['label' => 'Pending Reviews',  'value' => $pendingReviews,     'color' => 'red',    'icon' => '🔍'],
        ] as $stat)
            <div class="bg-white rounded-2xl border border-gray-200 p-5">
                <div class="text-2xl mb-1">{{ $stat['icon'] }}</div>
                <p class="text-3xl font-extrabold text-gray-900">{{ number_format($stat['value']) }}</p>
                <p class="text-sm text-gray-500 mt-0.5">{{ $stat['label'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- Quick Links --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="font-bold text-gray-800 mb-4">Business Management</h2>
            <div class="space-y-2">
                <a href="{{ route('admin.businesses', ['status' => 'pending']) }}"
                   class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 transition group">
                    <span class="text-sm font-medium text-gray-700 group-hover:text-green-600">Pending Approvals</span>
                    <span class="bg-yellow-100 text-yellow-700 text-xs font-bold px-2.5 py-1 rounded-full">
                        {{ $pendingBusinesses }}
                    </span>
                </a>
                <a href="{{ route('admin.businesses') }}"
                   class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 transition group">
                    <span class="text-sm font-medium text-gray-700 group-hover:text-green-600">All Businesses</span>
                    <span class="text-xs text-gray-400">→</span>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="font-bold text-gray-800 mb-4">Review Management</h2>
            <div class="space-y-2">
                <a href="{{ route('admin.reviews', ['status' => 'pending']) }}"
                   class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 transition group">
                    <span class="text-sm font-medium text-gray-700 group-hover:text-green-600">Pending Reviews</span>
                    <span class="bg-red-100 text-red-700 text-xs font-bold px-2.5 py-1 rounded-full">
                        {{ $pendingReviews }}
                    </span>
                </a>
                <a href="{{ route('admin.reviews') }}"
                   class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 transition group">
                    <span class="text-sm font-medium text-gray-700 group-hover:text-green-600">All Reviews</span>
                    <span class="text-xs text-gray-400">→</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
