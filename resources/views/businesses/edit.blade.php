@extends('layouts.app')
@section('title', 'Edit ' . $business->name)

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">Edit Business</h1>
            <p class="text-gray-500 mt-1">{{ $business->name }}</p>
        </div>
        <a href="{{ route('businesses.show', $business) }}"
           class="text-sm text-green-600 font-semibold hover:underline">← View Public Profile</a>
    </div>

    <form method="POST" action="{{ route('businesses.update', $business) }}" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        {{-- Basic Info --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-5">
            <h2 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-3">Basic Information</h2>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Business Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $business->name) }}"
                       class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 @error('name') border-red-400 @enderror">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                <textarea name="description" rows="5"
                          class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">{{ old('description', $business->description) }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Business Logo
                    @if($business->logo)
                        <span class="text-gray-400 font-normal">(upload new to replace)</span>
                    @endif
                </label>
                @if($business->logo)
                    <img src="{{ asset('storage/' . $business->logo) }}" class="w-24 h-24 object-contain rounded-xl border mb-3">
                @endif
                <input type="file" name="logo" accept="image/*"
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
            </div>
        </div>

        {{-- Contact --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-5">
            <h2 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-3">Contact Information</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Phone</label>
                    <input type="tel" name="phone" value="{{ old('phone', $business->phone) }}"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Business Email</label>
                    <input type="email" name="email" value="{{ old('email', $business->email) }}"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Website</label>
                <input type="url" name="website" value="{{ old('website', $business->website) }}" placeholder="https://"
                       class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
            </div>
        </div>

        {{-- Location --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-5">
            <h2 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-3">Location & Service Area</h2>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Street Address <span class="text-red-500">*</span></label>
                <input type="text" name="address" value="{{ old('address', $business->address) }}"
                       class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">City <span class="text-red-500">*</span></label>
                    <input type="text" name="city" value="{{ old('city', $business->city) }}"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">State <span class="text-red-500">*</span></label>
                    <input type="text" name="state" value="{{ old('state', $business->state) }}" maxlength="2"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">ZIP <span class="text-red-500">*</span></label>
                    <input type="text" name="zip" value="{{ old('zip', $business->zip) }}"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Service Radius (miles)</label>
                <select name="service_radius"
                        class="border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
                    @foreach([10, 25, 50, 100, 200] as $r)
                        <option value="{{ $r }}" {{ old('service_radius', $business->service_radius) == $r ? 'selected' : '' }}>{{ $r }} miles</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Categories --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-3 mb-4">Service Categories <span class="text-red-500">*</span></h2>
            @error('categories') <p class="text-red-500 text-xs mb-3">{{ $message }}</p> @enderror
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @foreach($categories as $category)
                    @php $checked = in_array($category->id, old('categories', $business->categories->pluck('id')->toArray())); @endphp
                    <label class="flex items-center gap-2.5 text-sm text-gray-700 cursor-pointer p-3 rounded-xl border {{ $checked ? 'border-green-500 bg-green-50' : 'border-gray-200' }} hover:border-green-400 hover:bg-green-50 transition">
                        <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                               class="accent-green-600 w-4 h-4" {{ $checked ? 'checked' : '' }}>
                        <span>{{ $category->icon ?? '' }} {{ $category->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Business Details --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-5">
            <h2 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-3">Business Details</h2>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Years in Business</label>
                <input type="number" name="years_in_business" value="{{ old('years_in_business', $business->years_in_business) }}" min="0"
                       class="w-32 border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
            </div>
            <div class="space-y-3">
                <h3 class="text-sm font-semibold text-gray-700">Credentials & Trust Badges</h3>
                <label class="flex items-center gap-2.5 text-sm text-gray-700 cursor-pointer">
                    <input type="checkbox" name="licensed" value="1" class="accent-green-600 w-4 h-4"
                           {{ old('licensed', $business->licensed) ? 'checked' : '' }}>
                    ✔ Licensed
                </label>
                <label class="flex items-center gap-2.5 text-sm text-gray-700 cursor-pointer">
                    <input type="checkbox" name="insured" value="1" class="accent-green-600 w-4 h-4"
                           {{ old('insured', $business->insured) ? 'checked' : '' }}>
                    ✔ Insured
                </label>
                <label class="flex items-center gap-2.5 text-sm text-gray-700 cursor-pointer">
                    <input type="checkbox" name="background_checked" value="1" class="accent-green-600 w-4 h-4"
                           {{ old('background_checked', $business->background_checked) ? 'checked' : '' }}>
                    ✔ Background Checked
                </label>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit"
                    class="bg-green-600 text-white font-bold px-8 py-3 rounded-xl hover:bg-green-700 transition text-sm">
                Save Changes
            </button>
            <a href="{{ route('businesses.show', $business) }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
        </div>
    </form>
</div>
@endsection
