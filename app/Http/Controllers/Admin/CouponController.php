<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = Coupon::withCount('usages')->latest()->paginate(10);
        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.coupons.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:coupons,code|max:255',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_limit_per_user' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Coupon::create($validated);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Kupon berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coupon $coupon)
    {
        $coupon->loadCount('usages');
        return view('admin.coupons.edit', compact('coupon'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:coupons,code,' . $coupon->id,
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_limit_per_user' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $coupon->update($validated);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Kupon berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Kupon berhasil dihapus.');
    }

    /**
     * Show the form for bulk coupon generation.
     */
    public function showBulkGenerate()
    {
        return view('admin.coupons.bulk-generate');
    }

    /**
     * Store bulk generated coupons.
     */
    public function storeBulkGenerate(Request $request)
    {
        $validated = $request->validate([
            'prefix' => 'nullable|string|max:10',
            'digit_length' => 'required|integer|min:4|max:12',
            'postfix' => 'nullable|string|max:10',
            'quantity' => 'required|integer|min:1|max:1000',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_limit_per_user' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        try {
            DB::beginTransaction();

            $coupons = [];
            $generatedCodes = [];

            for ($i = 0; $i < $validated['quantity']; $i++) {
                $code = $this->generateUniqueCode(
                    $validated['prefix'] ?? '',
                    $validated['digit_length'],
                    $validated['postfix'] ?? '',
                    $generatedCodes
                );

                $generatedCodes[] = $code;

                $coupons[] = [
                    'code' => $code,
                    'type' => $validated['type'],
                    'value' => $validated['value'],
                    'start_date' => $validated['start_date'] ?? null,
                    'end_date' => $validated['end_date'] ?? null,
                    'usage_limit' => $validated['usage_limit'] ?? null,
                    'usage_limit_per_user' => $validated['usage_limit_per_user'] ?? null,
                    'is_active' => $validated['is_active'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            Coupon::insert($coupons);

            DB::commit();

            return redirect()->route('admin.coupons.index')
                ->with('success', "Berhasil membuat {$validated['quantity']} kupon.");
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal membuat kupon: ' . $e->getMessage());
        }
    }

    /**
     * Generate a unique coupon code.
     */
    private function generateUniqueCode(string $prefix, int $digitLength, string $postfix, array $generatedCodes): string
    {
        $maxAttempts = 10;
        $attempt = 0;

        do {
            // Generate random alphanumeric string
            $randomPart = strtoupper(substr(str_replace(['/', '+', '='], '', base64_encode(random_bytes($digitLength))), 0, $digitLength));

            // Construct the full code
            $code = $prefix . $randomPart . $postfix;

            $attempt++;

            // Check if code is unique (not in database and not in current batch)
            $existsInDb = Coupon::where('code', $code)->exists();
            $existsInBatch = in_array($code, $generatedCodes);

            if (!$existsInDb && !$existsInBatch) {
                return $code;
            }

            if ($attempt >= $maxAttempts) {
                throw new \Exception("Could not generate unique code after {$maxAttempts} attempts.");
            }
        } while (true);
    }
}
