@extends('layouts.app')
@section('title', 'List Your Business')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900">List Your Business</h1>
        <p class="text-gray-500 mt-1">Fill in your details to appear in our directory. Your listing will be reviewed within 24 hours.</p>
    </div>

    <form method="POST" action="{{ route('businesses.store') }}" enctype="multipart/form-data" class="space-y-8">
        @csrf

        {{-- Basic Info --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-5">
            <h2 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-3">Basic Information</h2>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Business Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 @error('name') border-red-400 @enderror">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                <textarea name="description" rows="5"
                          placeholder="Describe your services, expertise, and what makes you stand out…"
                          class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 @error('description') border-red-400 @enderror">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Business Logo</label>
                <input type="file" name="logo" accept="image/*"
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                <p class="text-xs text-gray-400 mt-1">PNG, JPG up to 2MB</p>
            </div>
        </div>

        {{-- Contact --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-5">
            <h2 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-3">Contact Information</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Phone</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Business Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Website</label>
                <input type="url" name="website" value="{{ old('website') }}" placeholder="https://"
                       class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
            </div>
        </div>

        {{-- Location --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-5">
            <h2 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-3">Location & Service Area</h2>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Street Address <span class="text-red-500">*</span></label>
                <input type="text" name="address" value="{{ old('address') }}"
                       class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 @error('address') border-red-400 @enderror">
                @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">City <span class="text-red-500">*</span></label>
                    <input type="text" name="city" value="{{ old('city') }}"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 @error('city') border-red-400 @enderror">
                    @error('city') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">State <span class="text-red-500">*</span></label>
                    <input type="text" name="state" value="{{ old('state') }}" maxlength="2" placeholder="TX"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 @error('state') border-red-400 @enderror">
                    @error('state') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">ZIP <span class="text-red-500">*</span></label>
                    <input type="text" name="zip" value="{{ old('zip') }}"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 @error('zip') border-red-400 @enderror">
                    @error('zip') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Service Radius (miles)</label>
                <select name="service_radius"
                        class="border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
                    @foreach([10, 25, 50, 100, 200] as $r)
                        <option value="{{ $r }}" {{ old('service_radius', 25) == $r ? 'selected' : '' }}>{{ $r }} miles</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Categories --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-3 mb-4">
                Service Categories <span class="text-red-500">*</span>
            </h2>
            @error('categories') <p class="text-red-500 text-xs mb-3">{{ $message }}</p> @enderror
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @foreach($categories as $category)
                    <label class="flex items-center gap-2.5 text-sm text-gray-700 cursor-pointer p-3 rounded-xl border border-gray-200 hover:border-green-400 hover:bg-green-50 transition has-[:checked]:border-green-500 has-[:checked]:bg-green-50">
                        <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                               class="accent-green-600 w-4 h-4"
                               {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
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
                <input type="number" name="years_in_business" value="{{ old('years_in_business') }}" min="0" max="200"
                       class="w-32 border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
            </div>

            <div class="space-y-3">
                <h3 class="text-sm font-semibold text-gray-700">Credentials & Trust Badges</h3>
                <label class="flex items-center gap-2.5 text-sm text-gray-700 cursor-pointer">
                    <input type="checkbox" name="licensed" value="1" class="accent-green-600 w-4 h-4"
                           {{ old('licensed') ? 'checked' : '' }}>
                    ✔ Licensed
                </label>
                <label class="flex items-center gap-2.5 text-sm text-gray-700 cursor-pointer">
                    <input type="checkbox" name="insured" value="1" class="accent-green-600 w-4 h-4"
                           {{ old('insured') ? 'checked' : '' }}>
                    ✔ Insured
                </label>
                <label class="flex items-center gap-2.5 text-sm text-gray-700 cursor-pointer">
                    <input type="checkbox" name="background_checked" value="1" class="accent-green-600 w-4 h-4"
                           {{ old('background_checked') ? 'checked' : '' }}>
                    ✔ Background Checked
                </label>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex items-center gap-4">
            <button type="submit"
                    class="bg-green-600 text-white font-bold px-8 py-3 rounded-xl hover:bg-green-700 transition text-sm">
                Submit Listing for Review →
            </button>
            <p class="text-xs text-gray-400">Your listing will be reviewed and approved within 24 hours.</p>
        </div>
    </form>
</div>
@endsection
