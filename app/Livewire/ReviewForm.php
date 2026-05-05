<?php

namespace App\Livewire;

use App\Models\Business;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ReviewForm extends Component
{
    public Business $business;

    public int $rating = 0;
    public int $qualityRating = 0;
    public int $responsivenessRating = 0;
    public int $punctualityRating = 0;
    public int $professionalismRating = 0;

    public string $title = '';
    public string $body = '';
    public string $serviceUsed = '';
    public string $serviceDate = '';
    public string $pricePaid = '';

    public bool $wouldHireAgain = true;
    public bool $submitted = false;

    protected function rules(): array
    {
        return [
            'rating' => 'required|integer|between:1,5',
            'qualityRating' => 'nullable|integer|between:1,5',
            'responsivenessRating' => 'nullable|integer|between:1,5',
            'punctualityRating' => 'nullable|integer|between:1,5',
            'professionalismRating' => 'nullable|integer|between:1,5',
            'title' => 'nullable|string|max:255',
            'body' => 'required|string|min:20',
            'serviceUsed' => 'nullable|string|max:255',
            'serviceDate' => 'nullable|date|before_or_equal:today',
            'pricePaid' => 'nullable|numeric|min:0',
            'wouldHireAgain' => 'boolean',
        ];
    }

    public function submit(): void
    {
        $this->validate();

        if (!Auth::check()) {
            abort(403);
        }

        $this->business->reviews()->create([
            'user_id' => Auth::id(),
            'rating' => $this->rating,
            'quality_rating' => $this->qualityRating ?: null,
            'responsiveness_rating' => $this->responsivenessRating ?: null,
            'punctuality_rating' => $this->punctualityRating ?: null,
            'professionalism_rating' => $this->professionalismRating ?: null,
            'title' => $this->title ?: null,
            'body' => $this->body,
            'service_used' => $this->serviceUsed ?: null,
            'service_date' => $this->serviceDate ?: null,
            'price_paid' => $this->pricePaid ?: null,
            'would_hire_again' => $this->wouldHireAgain,
            'status' => 'pending',
        ]);

        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.review-form');
    }
}