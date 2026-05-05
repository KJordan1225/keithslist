@extends('layouts.app')
@section('title', $business->name)

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Main Column --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Header --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <div class="flex items-start gap-5">
                    @if($business->logo)
                        <img src="{{ asset('storage/' . $business->logo) }}" class="w-20 h-20 object-contain rounded-xl border">
                    @else
                        <div class="w-20 h-20 bg-green-100 rounded-xl flex items-center justify-center text-4xl">🏠</div>
                    @endif

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3 flex-wrap">
                            <h1 class="text-2xl font-extrabold text-gray-900">{{ $business->name }}</h1>
                            @if($business->featured)
                                <span class="bg-yellow-100 text-yellow-700 text-xs font-bold px-3 py-1 rounded-full">Featured</span>
                            @endif
                        </div>

                        <p class="text-gray-500 text-sm mt-1">{{ $business->city }}, {{ $business->state }}</p>

                        {{-- Rating --}}
                        <div class="flex items-center gap-2 mt-2">
                            <div class="flex">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= round($business->avg_rating) ? 'text-yellow-400' : 'text-gray-200' }}"
                                         fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-lg font-bold text-gray-800">{{ number_format($business->avg_rating, 1) }}</span>
                            <span class="text-gray-400 text-sm">({{ $business->review_count }} reviews)</span>
                        </div>

                        {{-- Categories --}}
                        <div class="flex flex-wrap gap-2 mt-3">
                            @foreach($business->categories as $cat)
                                <span class="bg-green-50 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">{{ $cat->name }}</span>
                            @endforeach
                        </div>

                        {{-- Trust Badges --}}
                        <div class="flex gap-4 mt-3 text-sm">
                            @if($business->licensed)
                                <span class="text-blue-600 font-semibold">✔ Licensed</span>
                            @endif
                            @if($business->insured)
                                <span class="text-blue-600 font-semibold">✔ Insured</span>
                            @endif
                            @if($business->background_checked)
                                <span class="text-blue-600 font-semibold">✔ Background Checked</span>
                            @endif
                        </div>
                    </div>

                    {{-- Favorite --}}
                    @auth
                        <form method="POST" action="{{ route('businesses.favorite', $business) }}">
                            @csrf
                            <button type="submit" class="text-2xl hover:scale-110 transition" title="Save">
                                {{ $isFavorited ? '❤️' : '🤍' }}
                            </button>
                        </form>
                    @endauth
                </div>
            </div>

            {{-- Photos --}}
            @if($business->photos->count())
            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Photos</h2>
                <div class="grid grid-cols-3 gap-2">
                    @foreach($business->photos as $photo)
                        <img src="{{ asset('storage/' . $photo->path) }}"
                             alt="{{ $photo->caption }}"
                             class="rounded-xl w-full h-32 object-cover">
                    @endforeach
                </div>
            </div>
            @endif

            {{-- About --}}
            @if($business->description)
            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-2">About</h2>
                <p class="text-gray-600 leading-relaxed">{{ $business->description }}</p>
            </div>
            @endif

            {{-- Reviews --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-lg font-bold text-gray-800">Reviews</h2>
                    @auth
                        @if(auth()->id() !== $business->user_id)
                            <a href="{{ route('reviews.create', $business) }}"
                               class="bg-green-600 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-green-700 transition">
                                Write a Review
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-green-600 font-semibold hover:underline">Log in to review</a>
                    @endauth
                </div>

                @forelse($business->approvedReviews->take(10) as $review)
                    @include('reviews._review', ['review' => $review])
                @empty
                    <p class="text-gray-400 text-sm">No reviews yet. Be the first!</p>
                @endforelse
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-5">
            {{-- Contact / Quote --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-6 sticky top-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Request a Quote</h2>
                @livewire('quote-request', ['business' => $business])
            </div>

            {{-- Contact Info --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-5 space-y-3 text-sm">
                @if($business->phone)
                    <div class="flex gap-3 items-center text-gray-700">
                        <span>📞</span><span>{{ $business->phone }}</span>
                    </div>
                @endif
                @if($business->email)
                    <div class="flex gap-3 items-center text-gray-700">
                        <span>✉️</span><span>{{ $business->email }}</span>
                    </div>
                @endif
                @if($business->website)
                    <div class="flex gap-3 items-center">
                        <span>🌐</span>
                        <a href="{{ $business->website }}" target="_blank" class="text-green-600 hover:underline truncate">
                            {{ parse_url($business->website, PHP_URL_HOST) }}
                        </a>
                    </div>
                @endif
                <div class="flex gap-3 items-start text-gray-700">
                    <span>📍</span><span>{{ $business->full_address }}</span>
                </div>
                @if($business->years_in_business)
                    <div class="flex gap-3 items-center text-gray-700">
                        <span>🗓️</span><span>{{ $business->years_in_business }} years in business</span>
                    </div>
                @endif
            </div>

            {{-- Business Hours --}}
            @if($business->hours->count())
            <div class="bg-white rounded-2xl border border-gray-200 p-5">
                <h3 class="font-bold text-gray-800 mb-3 text-sm">Hours</h3>
                <div class="space-y-1 text-sm">
                    @foreach($business->hours as $hour)
                        <div class="flex justify-between {{ now()->dayOfWeek === $hour->day_of_week ? 'font-semibold text-green-700' : 'text-gray-600' }}">
                            <span>{{ $hour->day_name }}</span>
                            <span>{{ $hour->is_closed ? 'Closed' : $hour->open_time . ' – ' . $hour->close_time }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Similar Businesses --}}
    @if($similarBusinesses->count())
    <div class="mt-10">
        <h2 class="text-xl font-bold text-gray-800 mb-5">Similar Businesses</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($similarBusinesses as $business)
                @include('businesses._card', ['business' => $business])
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
