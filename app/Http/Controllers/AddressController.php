<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
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

        Address::create($validated);

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

        $address->delete();

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

        return redirect()->route('account')->with('success', 'Default address updated!');
    }
}
