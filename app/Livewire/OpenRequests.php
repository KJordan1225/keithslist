<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\ServiceRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class OpenRequests extends Component
{
    use WithPagination;

    public string $location   = '';
    public string $categoryId = '';
    public string $urgency    = '';

    protected $queryString = [
        'location'   => ['except' => ''],
        'categoryId' => ['except' => ''],
        'urgency'    => ['except' => ''],
    ];

    public ?int $quotingRequestId = null;
    public string $quoteAmount    = '';
    public string $quoteMessage   = '';

    public function updatingLocation(): void   { $this->resetPage(); }
    public function updatingCategoryId(): void { $this->resetPage(); }

    public function openQuoteForm(int $id): void
    {
        $this->quotingRequestId = $id;
        $this->quoteAmount  = '';
        $this->quoteMessage = '';
    }

    public function submitQuote(): void
    {
        $this->validate([
            'quoteAmount'  => 'nullable|numeric|min:0',
            'quoteMessage' => 'required|string|min:10|max:1000',
        ]);

        $request = ServiceRequest::findOrFail($this->quotingRequestId);
        $business = Auth::user()->business;

        $business->quotes()->create([
            'service_request_id' => $request->id,
            'amount'             => $this->quoteAmount ?: null,
            'message'            => $this->quoteMessage,
            'status'             => 'pending',
        ]);

        $this->quotingRequestId = null;
        $this->dispatch('quote-submitted');
        session()->flash('success', 'Quote submitted successfully!');
    }

    public function render()
    {
        $requests = ServiceRequest::where('status', 'open')
            ->with(['category', 'user'])
            ->when($this->location, fn($q) =>
                $q->where(fn($inner) =>
                    $inner->where('city', 'like', "%{$this->location}%")
                          ->orWhere('zip', $this->location)
                          ->orWhere('state', $this->location)
                )
            )
            ->when($this->categoryId, fn($q) => $q->where('category_id', $this->categoryId))
            ->when($this->urgency,    fn($q) => $q->where('urgency', $this->urgency))
            ->latest()
            ->paginate(10);

        $categories = Category::orderBy('name')->get();

        return view('livewire.open-requests', compact('requests', 'categories'));
    }
}
