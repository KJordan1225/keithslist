<?php

namespace App\Http\Livewire;

use App\Models\Business;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class QuoteRequest extends Component
{
    public Business $business;

    public string $name    = '';
    public string $email   = '';
    public string $phone   = '';
    public string $message = '';
    public string $service = '';
    public bool   $sent    = false;

    protected function rules(): array
    {
        return [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'phone'   => 'nullable|string|max:20',
            'service' => 'nullable|string|max:255',
            'message' => 'required|string|min:10',
        ];
    }

    public function mount(): void
    {
        if (Auth::check()) {
            $this->name  = Auth::user()->name;
            $this->email = Auth::user()->email;
            $this->phone = Auth::user()->phone ?? '';
        }
    }

    public function send(): void
    {
        $this->validate();

        $body = "**Service:** {$this->service}\n\n{$this->message}\n\n— {$this->name} ({$this->email}" .
                ($this->phone ? ", {$this->phone}" : '') . ')';

        // Store as a message to the business owner
        Message::create([
            'sender_id'    => Auth::id() ?? null,
            'recipient_id' => $this->business->user_id,
            'business_id'  => $this->business->id,
            'body'         => $body,
        ]);

        $this->sent = true;
    }

    public function render()
    {
        return view('livewire.quote-request');
    }
}
