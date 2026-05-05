<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class BusinessController extends Controller
{
    use AuthorizesRequests;
    
    /**
     * Show a single business profile.
     */
    public function show(Business $business)
    {
        abort_if($business->status !== 'active', 404);

        $business->load([
            'user', 'categories', 'photos', 'hours',
            'approvedReviews.user' => fn($q) => $q->latest()->limit(10),
        ]);

        $similarBusinesses = Business::active()
            ->whereHas('categories', fn($q) =>
                $q->whereIn('categories.id', $business->categories->pluck('id'))
            )
            ->where('id', '!=', $business->id)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        /** @var User|null $user */
        $user = Auth::user();

        $isFavorited = $user?->hasFavorited($business) ?? false;

        return view('businesses.show', compact('business', 'similarBusinesses', 'isFavorited'));
    }

    /**
     * Provider: show create form.
     */
    public function create()
    {
        $this->authorize('create', Business::class);
        $categories = Category::orderBy('name')->get();
        return view('businesses.create', compact('categories'));
    }

    /**
     * Provider: store new business.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Business::class);

        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'description'       => 'required|string',
            'phone'             => 'nullable|string|max:20',
            'email'             => 'nullable|email',
            'website'           => 'nullable|url',
            'address'           => 'required|string',
            'city'              => 'required|string',
            'state'             => 'required|string|size:2',
            'zip'               => 'required|string|max:10',
            'service_radius'    => 'integer|min:1|max:200',
            'years_in_business' => 'nullable|integer|min:0',
            'licensed'          => 'boolean',
            'insured'           => 'boolean',
            'categories'        => 'required|array|min:1',
            'categories.*'      => 'exists:categories,id',
            'logo'              => 'nullable|image|max:2048',
        ]);

        $slug = str($validated['name'])->slug()->append('-' . Auth::id());

        /** @var User $user */
        $user = Auth::user();

        $business = $user->business()->create([
            ...$validated,
            'slug'   => $slug,
            'status' => 'pending',
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $business->update(['logo' => $path]);
        }

        $business->categories()->sync($validated['categories']);

        return redirect()->route('dashboard.business')
            ->with('success', 'Business submitted for review!');
    }

    /**
     * Provider: edit form.
     */
    public function edit(Business $business)
    {
        $this->authorize('update', $business);
        $categories = Category::orderBy('name')->get();
        $business->load(['categories', 'hours', 'photos']);
        return view('businesses.edit', compact('business', 'categories'));
    }

    /**
     * Provider: update.
     */
    public function update(Request $request, Business $business)
    {
        $this->authorize('update', $business);

        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'description'       => 'required|string',
            'phone'             => 'nullable|string|max:20',
            'email'             => 'nullable|email',
            'website'           => 'nullable|url',
            'address'           => 'required|string',
            'city'              => 'required|string',
            'state'             => 'required|string|size:2',
            'zip'               => 'required|string|max:10',
            'service_radius'    => 'integer|min:1|max:200',
            'years_in_business' => 'nullable|integer|min:0',
            'licensed'          => 'boolean',
            'insured'           => 'boolean',
            'categories'        => 'required|array|min:1',
            'categories.*'      => 'exists:categories,id',
            'logo'              => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $path;
        }

        $business->update($validated);
        $business->categories()->sync($validated['categories']);

        return redirect()->route('businesses.show', $business)
            ->with('success', 'Business updated successfully.');
    }

    /**
     * Toggle favorite.
     */
    public function toggleFavorite(Business $business)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        /** @var User $user */
        $user = Auth::user();

        $user->favorites()->toggle($business->id);

        return back();
    }
}
