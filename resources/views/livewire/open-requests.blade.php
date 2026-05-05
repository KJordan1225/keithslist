<div>
    {{-- Filters --}}
    <div class="bg-white border-b border-gray-200 py-4 px-4 mb-6">
        <div class="max-w-5xl mx-auto flex flex-col sm:flex-row gap-3">
            <input wire:model.live.debounce.300ms="location"
                   type="text" placeholder="Filter by city, state, or ZIP"
                   class="flex-1 border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">

            <select wire:model.live="categoryId"
                    class="border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                @endforeach
            </select>

            <select wire:model.live="urgency"
                    class="border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
                <option value="">Any Urgency</option>
                <option value="asap">ASAP</option>
                <option value="within_week">Within a week</option>
                <option value="within_month">Within a month</option>
                <option value="flexible">Flexible</option>
            </select>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl px-5 py-3 text-sm mb-5 max-w-5xl mx-auto">
            {{ session('success') }}
        </div>
    @endif

    <div class="max-w-5xl mx-auto px-4">
        <div wire:loading class="text-sm text-gray-400 mb-4 animate-pulse">Loading…</div>

        <div class="space-y-4">
            @forelse($requests as $request)
                <div class="bg-white rounded-2xl border border-gray-200 p-5">
                    <div class="flex items-start gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 flex-wrap">
                                <h3 class="font-bold text-gray-900">{{ $request->title }}</h3>
                                @if($request->urgency === 'asap')
                                    <span class="bg-red-100 text-red-600 text-xs font-bold px-2.5 py-1 rounded-full">🔥 ASAP</span>
                                @elseif($request->urgency === 'within_week')
                                    <span class="bg-orange-100 text-orange-600 text-xs font-bold px-2.5 py-1 rounded-full">⚡ This Week</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500 mt-1">
                                📍 {{ $request->city }}, {{ $request->state }}
                                @if($request->category) · {{ $request->category->icon }} {{ $request->category->name }} @endif
                                · Posted {{ $request->created_at->diffForHumans() }}
                            </p>
                            <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ $request->description }}</p>

                            @if($request->budget_min || $request->budget_max)
                                <p class="text-sm font-semibold text-green-600 mt-2">
                                    💰 Budget:
                                    @if($request->budget_min && $request->budget_max)
                                        ${{ number_format($request->budget_min) }} – ${{ number_format($request->budget_max) }}
                                    @elseif($request->budget_max)
                                        Up to ${{ number_format($request->budget_max) }}
                                    @else
                                        From ${{ number_format($request->budget_min) }}
                                    @endif
                                </p>
                            @endif
                        </div>

                        <div class="shrink-0">
                            @if($quotingRequestId === $request->id)
                                <button wire:click="$set('quotingRequestId', null)"
                                        class="text-sm text-gray-400 hover:text-gray-600">Cancel</button>
                            @else
                                <button wire:click="openQuoteForm({{ $request->id }})"
                                        class="bg-green-600 text-white text-sm font-bold px-4 py-2 rounded-xl hover:bg-green-700 transition">
                                    Submit Quote
                                </button>
                            @endif
                        </div>
                    </div>

                    {{-- Inline Quote Form --}}
                    @if($quotingRequestId === $request->id)
                        <div class="mt-5 pt-5 border-t border-gray-100">
                            <h4 class="text-sm font-bold text-gray-700 mb-3">Your Quote</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-gray-400 text-sm">$</span>
                                    <input wire:model="quoteAmount"
                                           type="number" min="0" placeholder="Your price (optional)"
                                           class="w-full border border-gray-300 rounded-xl pl-7 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
                                </div>
                                <div class="sm:col-span-2">
                                    @error('quoteAmount') <p class="text-red-500 text-xs mb-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <textarea wire:model="quoteMessage"
                                      rows="3" placeholder="Introduce yourself, explain your approach, ask clarifying questions…"
                                      class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 mb-1"></textarea>
                            @error('quoteMessage') <p class="text-red-500 text-xs mb-2">{{ $message }}</p> @enderror
                            <button wire:click="submitQuote"
                                    class="bg-green-600 text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-green-700 transition">
                                Send Quote →
                            </button>
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-2xl border border-gray-200 p-16 text-center text-gray-400">
                    <p class="text-4xl mb-3">📭</p>
                    <p class="font-semibold">No open requests match your filters.</p>
                    <p class="text-sm mt-1">Try broadening your location or category.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6">{{ $requests->links() }}</div>
    </div>
</div>
