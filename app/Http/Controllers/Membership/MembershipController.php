<?php

namespace App\Http\Controllers\Membership;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\MembershipSetting;
use Illuminate\Http\Request;

/**
 * Members List + Enroll Member are available to both roles (front-desk,
 * customer-facing). Discount Settings (money-related config) is admin-only
 * — gated at the route level, see routes/web.php.
 */
class MembershipController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('q', '');
        $settings = MembershipSetting::firstOrCreate([], ['discount_percent' => 10]);

        $members = Membership::query()
            ->when($search, fn ($q) => $q->where('phone', 'like', "%{$search}%"))
            ->latest('start_date')
            ->get();

        return view('membership.index', compact('members', 'search', 'settings'));
    }

    public function create()
    {
        $settings = MembershipSetting::firstOrCreate([], ['discount_percent' => 10]);
        $recent = Membership::latest('start_date')->take(5)->get();

        return view('membership.create', compact('settings', 'recent'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'unique:memberships,phone'],
            'note' => ['nullable', 'string'],
        ]);

        Membership::create([
            'phone' => $data['phone'],
            'note' => $data['note'] ?? null,
            'start_date' => now()->toDateString(),
            'expiry_date' => now()->addYear()->toDateString(),
            'status' => 'active',
        ]);

        return redirect()->route('membership.create')->with('success', "{$data['phone']} enrolled.");
    }

    public function renew(Membership $membership)
    {
        $membership->update([
            'expiry_date' => \Carbon\Carbon::parse($membership->expiry_date)->addYear(),
            'status' => 'active',
        ]);

        return back()->with('success', 'Membership renewed.');
    }

    public function revoke(Membership $membership)
    {
        $membership->update(['status' => 'revoked']);

        return back()->with('success', 'Membership revoked.');
    }

    // --- Admin-only ---

    public function editSettings()
    {
        $settings = MembershipSetting::firstOrCreate([], ['discount_percent' => 10]);

        return view('membership.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->validate(['discount_percent' => ['required', 'numeric', 'min:0', 'max:100']]);

        MembershipSetting::firstOrCreate([])->update($data);

        return back()->with('success', 'Discount saved.');
    }
}
