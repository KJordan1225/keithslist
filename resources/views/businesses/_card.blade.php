<div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition group">
    <a href="{{ route('businesses.show', $business) }}">
        @if($business->primaryPhoto)
            <img src="{{ asset('storage/' . $business->primaryPhoto->path) }}"
                 alt="{{ $business->name }}"
                 class="w-full h-44 object-cover group-hover:scale-105 transition duration-300">
        @elseif($business->logo)
            <div class="w-full h-44 flex items-center justify-center bg-gray-100">
                <img src="{{ asset('storage/' . $business->logo) }}" alt="{{ $business->name }}" class="h-24 object-contain">
            </div>
        @else
            <div class="w-full h-44 bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center text-5xl">
                🏠
            </div>
        @endif
    </a>

    <div class="p-4">
        <div class="flex items-start justify-between gap-2">
            <a href="{{ route('businesses.show', $business) }}"
               class="text-base font-bold text-gray-900 hover:text-green-600 leading-snug">
                {{ $business->name }}
            </a>
            @if($business->featured)
                <span class="bg-yellow-100 text-yellow-700 text-xs font-semibold px-2 py-0.5 rounded-full shrink-0">Featured</span>
            @endif
        </div>

        <p class="text-xs text-gray-500 mt-1">{{ $business->city }}, {{ $business->state }}</p>

        {{-- Rating --}}
        <div class="flex items-center gap-1 mt-2">
            @for($i = 1; $i <= 5; $i++)
                <svg class="w-4 h-4 {{ $i <= round($business->avg_rating) ? 'text-yellow-400' : 'text-gray-200' }}"
                     fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
            @endfor
            <span class="text-sm font-semibold text-gray-700 ml-1">{{ number_format($business->avg_rating, 1) }}</span>
            <span class="text-xs text-gray-400">({{ $business->review_count }})</span>
        </div>

        {{-- Categories --}}
        <div class="flex flex-wrap gap-1 mt-2">
            @foreach($business->categories->take(3) as $cat)
                <span class="bg-green-50 text-green-700 text-xs px-2 py-0.5 rounded-full">{{ $cat->name }}</span>
            @endforeach
        </div>

        {{-- Badges --}}
        <div class="flex gap-2 mt-2">
            @if($business->licensed)
                <span class="text-xs text-blue-600 font-medium">✔ Licensed</span>
            @endif
            @if($business->insured)
                <span class="text-xs text-blue-600 font-medium">✔ Insured</span>
            @endif
        </div>
    </div>
</div>