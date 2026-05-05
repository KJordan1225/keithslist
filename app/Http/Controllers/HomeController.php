<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $featuredBusinesses = Business::active()
            ->featured()
            ->with(['categories', 'primaryPhoto'])
            ->orderByDesc('avg_rating')
            ->limit(6)
            ->get();

        $topCategories = Category::topLevel()
            ->withCount(['businesses' => fn($q) => $q->active()])
            ->orderByDesc('businesses_count')
            ->limit(12)
            ->get();

        $topRated = Business::active()
            ->with(['categories', 'primaryPhoto'])
            ->where('review_count', '>=', 5)
            ->orderByDesc('avg_rating')
            ->limit(8)
            ->get();

        return view('home.index', compact('featuredBusinesses', 'topCategories', 'topRated'));
    }
}