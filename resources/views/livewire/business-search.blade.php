<div>
    @extends('layouts.app')
    @section('title', 'Find a Pro')

    @section('content')
    <div class="bg-white border-b border-gray-200 py-6 px-4">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-2xl font-extrabold text-gray-900 mb-4">Find a Pro</h1>
            <div class="flex flex-col sm:flex-row gap-3">
                <input wire:model.live.debounce.300ms="query"
                       type="text" placeholder="Service, business name…"
                       class="flex-1 border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
                <input wire:model.live.debounce.300ms="location"
                       type="text" placeholder="City or ZIP"
                       class="w-full sm:w-48 border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
                <select wire:model.live="categoryId"
                        class="border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8 flex gap-8">

        {{-- Filters Sidebar --}}
        <aside class="hidden lg:block w-56 shrink-0">
            <div class="bg-white rounded-2xl border border-gray-200 p-5 space-y-5 sticky top-6">
                <h3 class="font-bold text-gray-800">Filters</h3>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Sort By</label>
                    <select wire:model.live="sortBy"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
                        <option value="avg_rating">Highest Rated</option>
                        <option value="review_count">Most Reviewed</option>
                        <option value="name">Name A–Z</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-2">Minimum Rating</label>
                    @foreach([4, 3, 2] as $r)
                        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer mb-1">
                            <input wire:model.live="minRating" type="radio" value="{{ $r }}" class="accent-green-600">
                            {{ $r }}+ stars
                        </label>
                    @endforeach
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input wire:model.live="minRating" type="radio" value="0" class="accent-green-600"> Any
                    </label>
                </div>

                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input wire:model.live="licensed" type="checkbox" class="accent-green-600"> Licensed
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input wire:model.live="insured" type="checkbox" class="accent-green-600"> Insured
                    </label>
                </div>
            </div>
        </aside>

        {{-- Results --}}
        <div class="flex-1">
            <div wire:loading class="text-sm text-gray-400 mb-4 animate-pulse">Searching…</div>

            <p class="text-sm text-gray-500 mb-4">{{ $businesses->total() }} results found</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                @forelse($businesses as $business)
                    @include('businesses._card', ['business' => $business])
                @empty
                    <div class="col-span-3 text-center py-16 text-gray-400">
                        <p class="text-4xl mb-3">🔍</p>
                        <p class="font-semibold">No businesses found matching your search.</p>
                        <p class="text-sm mt-1">Try adjusting your filters or location.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $businesses->links() }}
            </div>
        </div>
    </div>
    @endsection
</div>
