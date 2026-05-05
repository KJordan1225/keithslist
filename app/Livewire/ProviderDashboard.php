<?php

namespace App\Livewire;

use App\Models\Business;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProviderDashboard extends Component
{
    public ?Business $business = null;
    public string $activeTab = 'overview';

    public function mount(): void
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        $this->business = $user?->business;
    }

    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        $data = [];

        if ($this->business) {
            $data['pendingReviews'] = $this->business->reviews()
                ->where('status', 'pending')
                ->with('user')
                ->latest()
                ->get();

            $data['recentReviews'] = $this->business->approvedReviews()
                ->with('user')
                ->latest()
                ->limit(5)
                ->get();

            $data['messages'] = Message::where('recipient_id', Auth::id())
                ->where('business_id', $this->business->id)
                ->with('sender')
                ->latest()
                ->limit(10)
                ->get();

            $data['unreadCount'] = Message::where('recipient_id', Auth::id())
                ->whereNull('read_at')
                ->count();

            $data['ratingBreakdown'] = $this->business->approvedReviews()
                ->selectRaw('rating, count(*) as count')
                ->groupBy('rating')
                ->pluck('count', 'rating');
        }

        return view('livewire.provider-dashboard', [
            'business' => $this->business,
            ...$data,
        ]);
    }
}