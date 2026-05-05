<?php

namespace App\Http\Livewire;

use App\Models\Business;
use App\Models\BusinessHour;
use Livewire\Component;

class BusinessHoursEditor extends Component
{
    public Business $business;

    public array $hours = [];

    public function mount(): void
    {
        $days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];

        foreach ($days as $index => $day) {
            $existing = $this->business->hours->firstWhere('day_of_week', $index);
            $this->hours[$index] = [
                'day_name'   => $day,
                'is_closed'  => $existing?->is_closed ?? ($index === 0 || $index === 6),
                'open_time'  => $existing?->open_time  ?? '08:00',
                'close_time' => $existing?->close_time ?? '17:00',
            ];
        }
    }

    public function save(): void
    {
        $this->validate([
            'hours.*.open_time'  => 'nullable|date_format:H:i',
            'hours.*.close_time' => 'nullable|date_format:H:i',
        ]);

        foreach ($this->hours as $dayIndex => $hour) {
            BusinessHour::updateOrCreate(
                ['business_id' => $this->business->id, 'day_of_week' => $dayIndex],
                [
                    'is_closed'  => $hour['is_closed'],
                    'open_time'  => $hour['is_closed'] ? null : $hour['open_time'],
                    'close_time' => $hour['is_closed'] ? null : $hour['close_time'],
                ]
            );
        }

        $this->dispatch('hours-saved');
        session()->flash('success', 'Business hours updated.');
    }

    public function render()
    {
        return view('livewire.business-hours-editor');
    }
}
