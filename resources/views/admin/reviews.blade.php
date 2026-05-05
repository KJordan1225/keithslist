@extends('layouts.app')
@section('title', 'Manage Reviews')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-extrabold text-gray-900">Reviews</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-green-600 hover:underline">← Admin Home</a>
    </div>

    {{-- Filter Tabs --}}
    <div class="flex gap-1 mb-6 border-b border-gray-200">
        @foreach(['all' => 'All', 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $val => $label)
            <a href="{{ route('admin.reviews', $val !== 'all' ? ['status' => $val] : []) }}"
               class="px-4 py-2 text-sm font-semibold border-b-2 -mb-px transition
                      {{ request('status', 'all') === $val
                          ? 'border-green-600 text-green-700'
                          : 'border-transparent text-gray-500 hover:text-gray-800' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="space-y-4">
        @forelse($reviews as $review)
            <div class="bg-white rounded-2xl border border-gray-200 p-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3 flex-wrap text-sm">
                            <span class="font-bold text-gray-900">{{ $review->user->name }}</span>
                            <span class="text-gray-400">reviewed</span>
                            <a href="{{ route('businesses.show', $review->business) }}"
                               class="font-semibold text-green-600 hover:underline">
                                {{ $review->business->name }}
                            </a>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                                {{ $review->status === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $review->status === 'pending'  ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $review->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}">
                                {{ ucfirst($review->status) }}
                            </span>
                        </div>

                        {{-- Stars --}}
                        <div class="flex gap-0.5 mt-2">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }}"
                                     fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>

                        @if($review->title)
                            <p class="font-semibold text-gray-800 text-sm mt-1">{{ $review->title }}</p>
                        @endif
                        <p class="text-gray-600 text-sm mt-1 line-clamp-3">{{ $review->body }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $review->created_at->diffForHumans() }}</p>
                    </div>

                    {{-- Actions --}}
                    @if($review->status === 'pending')
                        <div class="flex gap-2 shrink-0">
                            <form method="POST" action="{{ route('admin.reviews.approve', $review) }}">
                                @csrf
                                <button class="bg-green-600 text-white text-xs font-bold px-3 py-1.5 rounded-lg hover:bg-green-700 transition">
                                    Approve
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.reviews.reject', $review) }}">
                                @csrf
                                <button class="bg-red-50 text-red-600 text-xs font-bold px-3 py-1.5 rounded-lg hover:bg-red-100 transition">
                                    Reject
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center text-gray-400">
                <p>No reviews found.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $reviews->links() }}</div>
</div>
@endsection
