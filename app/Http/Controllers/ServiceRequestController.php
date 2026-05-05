<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ServiceRequestController extends Controller
{

    use AuthorizesRequests;

    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $requests = $user->serviceRequests()
            ->with(['category', 'quotes.business'])
            ->latest()
            ->paginate(10);

        return view('service-requests.index', compact('requests'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('service-requests.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'required|string|min:20',
            'category_id'  => 'nullable|exists:categories,id',
            'city'         => 'required|string',
            'state'        => 'required|string|size:2',
            'zip'          => 'required|string|max:10',
            'desired_date' => 'nullable|date|after_or_equal:today',
            'urgency'      => 'in:flexible,within_week,within_month,asap',
            'budget_min'   => 'nullable|numeric|min:0',
            'budget_max'   => 'nullable|numeric|gte:budget_min',
        ]);

        /** @var User $user */
        $user = Auth::user();

        $serviceRequest = $user->serviceRequests()->create($validated);

        return redirect()->route('service-requests.show', $serviceRequest)
            ->with('success', 'Your request has been posted. Businesses will reach out soon!');
    }

    public function show(ServiceRequest $serviceRequest)
    {
        $this->authorize('view', $serviceRequest);
        $serviceRequest->load(['category', 'quotes.business']);
        return view('service-requests.show', compact('serviceRequest'));
    }

    public function destroy(ServiceRequest $serviceRequest)
    {
        $this->authorize('delete', $serviceRequest);
        $serviceRequest->update(['status' => 'closed']);
        return back()->with('success', 'Request closed.');
    }
}