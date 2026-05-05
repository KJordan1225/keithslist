<div>
    @if($submitted)
        <div class="bg-green-50 border border-green-200 rounded-xl p-6 text-center">
            <p class="text-3xl mb-2">🎉</p>
            <p class="font-bold text-green-800">Thank you for your review!</p>
            <p class="text-sm text-green-600 mt-1">It will appear after our team reviews it.</p>
        </div>
    @else
        <form wire:submit.prevent="submit" class="space-y-5">

            {{-- Overall Rating --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Overall Rating *</label>
                <div class="flex gap-1">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button" wire:click="setRating({{ $i }})"
                                class="text-3xl {{ $i <= $rating ? 'text-yellow-400' : 'text-gray-200' }} hover:text-yellow-300 transition">
                            ★
                        </button>
                    @endfor
                </div>
                @error('rating') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            {{-- Sub Ratings --}}
            <div class="grid grid-cols-2 gap-4 text-sm">
                @foreach([
                    ['Quality', 'qualityRating', 'setQuality'],
                    ['Responsiveness', 'responsivenessRating', 'setResponsiveness'],
                    ['Punctuality', 'punctualityRating', 'setPunctuality'],
                    ['Professionalism', 'professionalismRating', 'setProfessionalism'],
                ] as [$label, $prop, $fn])
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">{{ $label }}</label>
                        <div class="flex gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" wire:click="{{ $fn }}({{ $i }})"
                                        class="text-xl {{ $i <= $this->$prop ? 'text-yellow-400' : 'text-gray-200' }} hover:text-yellow-300 transition">
                                    ★
                                </button>
                            @endfor
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Title --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Review Title</label>
                <input wire:model="title" type="text" placeholder="Summarize your experience"
                       class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
            </div>

            {{-- Body --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Your Review *</label>
                <textarea wire:model="body" rows="5" placeholder="Tell others about your experience (min. 20 characters)"
                          class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400"></textarea>
                @error('body') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                {{-- Service Used --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Service Used</label>
                    <input wire:model="serviceUsed" type="text" placeholder="e.g. Roof repair"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
                </div>
                {{-- Price Paid --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Approx. Price Paid ($)</label>
                    <input wire:model="pricePaid" type="number" min="0" placeholder="0.00"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
                </div>
            </div>

            {{-- Would Hire Again --}}
            <div>
                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 cursor-pointer">
                    <input wire:model="wouldHireAgain" type="checkbox" class="accent-green-600 w-4 h-4">
                    I would hire this business again
                </label>
            </div>

            <button type="submit"
                    class="w-full bg-green-600 text-white font-bold py-3 rounded-xl hover:bg-green-700 transition">
                Submit Review
            </button>
        </form>
    @endif
</div>
