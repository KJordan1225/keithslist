<div>
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-2 text-sm mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-3">
        @foreach($hours as $index => $hour)
            <div class="flex items-center gap-4 text-sm">
                <span class="w-24 font-medium text-gray-700">{{ $hour['day_name'] }}</span>

                <label class="flex items-center gap-1.5 text-gray-500 cursor-pointer">
                    <input wire:model.live="hours.{{ $index }}.is_closed"
                           type="checkbox" class="accent-red-500 w-4 h-4">
                    Closed
                </label>

                @unless($hours[$index]['is_closed'])
                    <input wire:model="hours.{{ $index }}.open_time"
                           type="time"
                           class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
                    <span class="text-gray-400">to</span>
                    <input wire:model="hours.{{ $index }}.close_time"
                           type="time"
                           class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
                @endunless
            </div>
        @endforeach
    </div>

    <button wire:click="save"
            class="mt-5 bg-green-600 text-white text-sm font-bold px-6 py-2.5 rounded-xl hover:bg-green-700 transition">
        Save Hours
    </button>
</div>
