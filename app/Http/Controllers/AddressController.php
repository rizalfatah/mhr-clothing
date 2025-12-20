<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    protected $activityLogger;

    public function __construct(ActivityLogger $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }

    /**
     * Store a new address
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => ['nullable', 'string', 'max:255'],
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:20'],
            'address_line_1' => ['required', 'string'],
            'address_line_2' => ['nullable', 'string'],
            'city' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:10'],
            'notes' => ['nullable', 'string'],
            'is_default' => ['boolean'],
        ]);

        $validated['user_id'] = auth()->id();

        // If this is set as default, unset other defaults
        if ($request->boolean('is_default')) {
            auth()->user()->addresses()->update(['is_default' => false]);
        }

        $address = Address::create($validated);

        // Log activity
        $this->activityLogger->logAddressCreate($validated['label'] ?? 'New Address');

        return redirect()->route('account')->with('success', 'Address added successfully!');
    }

    /**
     * Update an existing address
     */
    public function update(Request $request, Address $address)
    {
        // Ensure user owns this address
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'label' => ['nullable', 'string', 'max:255'],
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:20'],
            'address_line_1' => ['required', 'string'],
            'address_line_2' => ['nullable', 'string'],
            'city' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:10'],
            'notes' => ['nullable', 'string'],
            'is_default' => ['boolean'],
        ]);

        // If this is set as default, unset other defaults
        if ($request->boolean('is_default')) {
            auth()->user()->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $address->update($validated);

        // Log activity
        $this->activityLogger->logAddressUpdate($validated['label'] ?? 'Address');

        return redirect()->route('account')->with('success', 'Address updated successfully!');
    }

    /**
     * Delete an address
     */
    public function destroy(Address $address)
    {
        // Ensure user owns this address
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        $label = $address->label ?? 'Address';
        $address->delete();

        // Log activity
        $this->activityLogger->logAddressDelete($label);

        return redirect()->route('account')->with('success', 'Address deleted successfully!');
    }

    /**
     * Set an address as default
     */
    public function setDefault(Address $address)
    {
        // Ensure user owns this address
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        DB::transaction(function () use ($address) {
            // Unset all other defaults
            auth()->user()->addresses()->update(['is_default' => false]);

            // Set this one as default
            $address->update(['is_default' => true]);
        });

        // Log activity
        $this->activityLogger->logAddressSetDefault($address->label ?? 'Address');

        return redirect()->route('account')->with('success', 'Default address updated!');
    }
}
