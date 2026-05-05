<div>
    @extends('layouts.app')
    @section('title', 'Provider Dashboard')

    @section('content')
    <div class="max-w-6xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-extrabold text-gray-900 mb-6">Provider Dashboard</h1>

        @unless($business)
            <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-8 text-center">
                <p class="text-4xl mb-3">🏗️</p>
                <h2 class="text-lg font-bold text-gray-800 mb-2">You don't have a business listing yet.</h2>
                <p class="text-gray-500 mb-5">Create your profile to start receiving leads.</p>
                <a href="{{ route('businesses.create') }}"
                   class="bg-green-600 text-white font-bold px-6 py-3 rounded-xl hover:bg-green-700 transition">
                    Create My Business Listing
                </a>
            </div>
        @else
            {{-- Tabs --}}
            <div class="flex gap-1 border-b border-gray-200 mb-6">
                @foreach(['overview' => 'Overview', 'reviews' => 'Reviews', 'messages' => 'Messages', 'hours' => 'Hours'] as $tab => $label)
                    <button wire:click="switchTab('{{ $tab }}')"
                            class="px-5 py-2.5 text-sm font-semibold transition border-b-2 -mb-px
                                   {{ $activeTab === $tab ? 'border-green-600 text-green-700' : 'border-transparent text-gray-500 hover:text-gray-800' }}">
                        {{ $label }}
                        @if($tab === 'messages' && ($unreadCount ?? 0) > 0)
                            <span class="ml-1 bg-red-500 text-white text-xs rounded-full px-1.5">{{ $unreadCount }}</span>
                        @endif
                    </button>
                @endforeach
            </div>

            {{-- Overview Tab --}}
            @if($activeTab === 'overview')
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
                    <div class="bg-white rounded-2xl border border-gray-200 p-5 text-center">
                        <p class="text-4xl font-extrabold text-green-600">{{ number_format($business->avg_rating, 1) }}</p>
                        <p class="text-sm text-gray-500 mt-1">Avg Rating</p>
                    </div>
                    <div class="bg-white rounded-2xl border border-gray-200 p-5 text-center">
                        <p class="text-4xl font-extrabold text-blue-600">{{ $business->review_count }}</p>
                        <p class="text-sm text-gray-500 mt-1">Reviews</p>
                    </div>
                    <div class="bg-white rounded-2xl border border-gray-200 p-5 text-center">
                        <p class="text-4xl font-extrabold text-purple-600">{{ $messages->count() }}</p>
                        <p class="text-sm text-gray-500 mt-1">Messages</p>
                    </div>
                </div>

                {{-- Rating Breakdown --}}
                <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-5">
                    <h3 class="font-bold text-gray-800 mb-4">Rating Breakdown</h3>
                    @foreach([5,4,3,2,1] as $star)
                        @php $count = $ratingBreakdown[$star] ?? 0; $max = max($ratingBreakdown->max() ?: 1, 1); @endphp
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-sm w-10 text-right text-gray-500">{{ $star }} ★</span>
                            <div class="flex-1 bg-gray-100 rounded-full h-2.5">
                                <div class="bg-yellow-400 h-2.5 rounded-full" style="width: {{ ($count / $max) * 100 }}%"></div>
                            </div>
                            <span class="text-xs text-gray-400 w-6">{{ $count }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="flex gap-4">
                    <a href="{{ route('businesses.edit', $business) }}"
                       class="bg-green-600 text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-green-700 transition">
                        Edit Business
                    </a>
                    <a href="{{ route('businesses.show', $business) }}"
                       class="border border-gray-300 text-gray-700 text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-gray-50 transition">
                        View Public Profile →
                    </a>
                </div>
            @endif

            {{-- Reviews Tab --}}
            @if($activeTab === 'reviews')
                <div class="bg-white rounded-2xl border border-gray-200 p-6">
                    <h3 class="font-bold text-gray-800 mb-5">Pending Reviews</h3>
                    @forelse($pendingReviews as $review)
                        @include('reviews._review', ['review' => $review])
                        @unless($review->owner_response)
                            <form method="POST" action="{{ route('reviews.respond', $review) }}" class="mt-2 mb-4">
                                @csrf
                                <textarea name="response" rows="3" placeholder="Write your response…"
                                          class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm"></textarea>
                                <button type="submit"
                                        class="mt-2 text-sm bg-green-600 text-white font-bold px-4 py-2 rounded-lg hover:bg-green-700">
                                    Post Response
                                </button>
                            </form>
                        @endunless
                    @empty
                        <p class="text-gray-400 text-sm">No pending reviews.</p>
                    @endforelse

                    <h3 class="font-bold text-gray-800 mb-5 mt-8">Recent Reviews</h3>
                    @forelse($recentReviews as $review)
                        @include('reviews._review', ['review' => $review])
                    @empty
                        <p class="text-gray-400 text-sm">No approved reviews yet.</p>
                    @endforelse
                </div>
            @endif

            {{-- Messages Tab --}}
            @if($activeTab === 'messages')
                <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
                    @forelse($messages as $msg)
                        <div class="border-b border-gray-100 pb-4 last:border-0">
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-sm shrink-0">
                                    {{ strtoupper(substr($msg->sender?->name ?? 'G', 0, 1)) }}
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-800">{{ $msg->sender?->name ?? 'Guest' }}</p>
                                    <p class="text-xs text-gray-400">{{ $msg->created_at->diffForHumans() }}</p>
                                    <p class="text-sm text-gray-600 mt-1 whitespace-pre-line">{{ $msg->body }}</p>
                                </div>
                                @unless($msg->isRead())
                                    <span class="bg-red-500 rounded-full w-2.5 h-2.5 mt-1 shrink-0"></span>
                                @endunless
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400 text-sm">No messages yet.</p>
                    @endforelse
                </div>
            @endif

            {{-- Hours Tab --}}
            @if($activeTab === 'hours')
                <div class="bg-white rounded-2xl border border-gray-200 p-6">
                    <h3 class="font-bold text-gray-800 mb-5">Business Hours</h3>
                    @livewire('business-hours-editor', ['business' => $business])
                </div>
            @endif
        @endunless
    </div>
    @endsection
</div>
