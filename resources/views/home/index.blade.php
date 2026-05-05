@extends('layouts.app')
@section('title', 'Find Trusted Local Pros')

@section('content')
{{-- Hero --}}
<div class="bg-gradient-to-br from-green-700 to-green-500 text-white py-20 px-4">
    <div class="max-w-3xl mx-auto text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4 leading-tight">
            Find Trusted Home Service Pros Near You
        </h1>
        <p class="text-lg text-green-100 mb-8">
            Read real reviews from real homeowners. Get quotes in minutes.
        </p>
        <form action="{{ route('businesses.search') }}" method="GET" class="flex flex-col sm:flex-row gap-3 justify-center">
            <input type="text" name="query" placeholder="What service do you need?"
                   class="flex-1 px-5 py-3 rounded-xl text-gray-900 text-base shadow-md focus:outline-none focus:ring-2 focus:ring-green-300">
            <input type="text" name="location" placeholder="City or ZIP"
                   class="w-full sm:w-48 px-5 py-3 rounded-xl text-gray-900 text-base shadow-md focus:outline-none focus:ring-2 focus:ring-green-300">
            <button type="submit"
                    class="bg-white text-green-700 font-bold px-8 py-3 rounded-xl shadow-md hover:bg-green-50 transition">
                Search
            </button>
        </form>
    </div>
</div>

{{-- Top Categories --}}
<div class="max-w-7xl mx-auto px-4 py-14">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Browse Popular Services</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @foreach($topCategories as $category)
            <a href="{{ route('businesses.search', ['categoryId' => $category->id]) }}"
               class="bg-white rounded-xl border border-gray-200 p-5 text-center hover:shadow-md hover:border-green-400 transition group">
                <div class="text-3xl mb-2">{{ $category->icon ?? '🏠' }}</div>
                <p class="text-sm font-semibold text-gray-700 group-hover:text-green-600">{{ $category->name }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $category->businesses_count }} pros</p>
            </a>
        @endforeach
    </div>
</div>

{{-- Featured Businesses --}}
@if($featuredBusinesses->count())
<div class="bg-white py-14">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">⭐ Featured Pros</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredBusinesses as $business)
                @include('businesses._card', ['business' => $business])
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- Top Rated --}}
<div class="max-w-7xl mx-auto px-4 py-14">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">🏆 Highest Rated</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($topRated as $business)
            @include('businesses._card', ['business' => $business])
        @endforeach
    </div>
</div>

{{-- CTA for Pros --}}
<div class="bg-green-50 border-t border-green-100 py-16 px-4 text-center">
    <h2 class="text-3xl font-bold text-gray-800 mb-3">Are you a home service professional?</h2>
    <p class="text-gray-500 mb-6">List your business and start getting leads from homeowners in your area.</p>
    <a href="{{ route('register') }}"
       class="bg-green-600 text-white font-bold px-8 py-3 rounded-xl hover:bg-green-700 transition text-lg">
        List Your Business Free →
    </a>
</div>
@endsection
