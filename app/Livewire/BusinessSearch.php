<?php

namespace App\Http\Livewire;

use App\Models\Business;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class BusinessSearch extends Component
{
    use WithPagination;

    public string $query      = '';
    public string $location   = '';
    public string $categoryId = '';
    public string $sortBy     = 'avg_rating';
    public bool   $licensed   = false;
    public bool   $insured    = false;
    public int    $minRating  = 0;

    protected $queryString = [
        'query'      => ['except' => ''],
        'location'   => ['except' => ''],
        'categoryId' => ['except' => ''],
        'sortBy'     => ['except' => 'avg_rating'],
        'licensed'   => ['except' => false],
        'insured'    => ['except' => false],
        'minRating'  => ['except' => 0],
    ];

    public function updatingQuery(): void    { $this->resetPage(); }
    public function updatingLocation(): void { $this->resetPage(); }
    public function updatingCategoryId(): void { $this->resetPage(); }

    public function render()
    {
        $businesses = Business::active()
            ->with(['categories', 'primaryPhoto'])
            ->when($this->query, function ($q) {
                $q->where(function ($inner) {
                    $inner->where('name', 'like', "%{$this->query}%")
                          ->orWhere('description', 'like', "%{$this->query}%");
                });
            })
            ->when($this->location, function ($q) {
                $q->where(function ($inner) {
                    $inner->where('city', 'like', "%{$this->location}%")
                          ->orWhere('zip', $this->location)
                          ->orWhere('state', $this->location);
                });
            })
            ->when($this->categoryId, fn($q) => $q->withCategory($this->categoryId))
            ->when($this->licensed,   fn($q) => $q->where('licensed', true))
            ->when($this->insured,    fn($q) => $q->where('insured', true))
            ->when($this->minRating,  fn($q) => $q->where('avg_rating', '>=', $this->minRating))
            ->orderBy($this->sortBy === 'name' ? 'name' : $this->sortBy,
                      $this->sortBy === 'name' ? 'asc' : 'desc')
            ->paginate(12);

        $categories = Category::topLevel()->orderBy('name')->get();

        return view('livewire.business-search', compact('businesses', 'categories'));
    }
}
