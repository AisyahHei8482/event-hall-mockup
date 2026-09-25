<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function index(): View
    {
        $staff = User::whereIn('role', ['admin', 'staff'])->orderBy('name')->paginate(20);

        return view('admin.staff.index', compact('staff'));
    }

    public function create(): View
    {
        return view('admin.staff.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:staff,admin'],
        ]);

        $staff = User::create([
            ...$validated,
            'password' => Hash::make($validated['password']),
        ]);

        ActivityLog::record('staff.created', $staff, "Added staff member {$staff->name} ({$staff->role})");

        return redirect()->route('admin.staff.index')->with('success', 'Staff member added.');
    }

    public function update(Request $request, User $staff): RedirectResponse
    {
        abort_unless(in_array($staff->role, ['admin', 'staff']), 404);

        $validated = $request->validate([
            'role' => ['required', 'in:staff,admin'],
        ]);

        if ($staff->id === auth()->id() && $validated['role'] !== 'admin') {
            return back()->withErrors(['role' => 'You cannot demote your own account.']);
        }

        $staff->update($validated);

        ActivityLog::record('staff.updated', $staff, "Changed {$staff->name}'s role to {$validated['role']}");

        return back()->with('success', 'Staff role updated.');
    }

    public function destroy(User $staff): RedirectResponse
    {
        abort_unless(in_array($staff->role, ['admin', 'staff']), 404);
        abort_if($staff->id === auth()->id(), 422);

        ActivityLog::record('staff.deleted', null, "Removed staff member {$staff->name}");

        $staff->delete();

        return back()->with('success', 'Staff member removed.');
    }
}
