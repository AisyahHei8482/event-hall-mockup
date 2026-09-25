@extends('layouts.management')
@section('title', $booking->booking_number)
@section('page_title', 'Booking ' . $booking->booking_number)

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    <!-- Main -->
    <div class="xl:col-span-2 space-y-6">

        <!-- Info -->
        <x-card class="p-6 border-slate-100 shadow-sm">
            <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">{{ $booking->booking_number }}</h2>
                    <div class="text-sm font-bold text-slate-500 mt-1 uppercase tracking-widest">{{ $booking->eventHall->franchise->name ?? '' }} · {{ $booking->eventHall->name ?? '' }}</div>
                </div>
                <div class="flex gap-3">
                    @php
                    $statusTheme = match($booking->status) {
                        'confirmed'  => 'emerald',
                        'pending'    => 'amber',
                        'cancelled'  => 'red',
                        'completed'  => 'slate',
                        'checked_in' => 'blue',
                        default      => 'slate',
                    };
                    @endphp
                    <x-badge variant="{{ $statusTheme }}" class="shadow-sm py-1.5 px-3 uppercase tracking-wider text-xs">
                        {{ ucfirst(str_replace('_',' ',$booking->status)) }}
                    </x-badge>
                    <x-badge variant="{{ $booking->payment_status === 'paid' ? 'emerald' : 'amber' }}" class="shadow-sm py-1.5 px-3 uppercase tracking-wider text-xs">
                        {{ ucfirst($booking->payment_status) }}
                    </x-badge>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-sm">
                <div><div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Guest</div><div class="font-bold text-slate-900">{{ $booking->guest_name }}</div></div>
                <div><div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Email</div><div class="font-bold text-slate-900 truncate">{{ $booking->guest_email }}</div></div>
                <div><div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Phone</div><div class="font-bold text-slate-900">{{ $booking->guest_phone ?: '—' }}</div></div>
                <div><div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Guests</div><div class="font-bold text-slate-900">{{ $booking->guests }} pax</div></div>
                <div><div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Event Date</div><div class="font-bold text-slate-900">{{ $booking->check_in->format('d M Y') }}</div></div>
                <div><div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Time</div><div class="font-bold text-slate-900 font-mono bg-slate-50 px-2 py-0.5 rounded-md inline-block">{{ $booking->start_time }} – {{ $booking->end_time }}</div></div>
                <div><div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Duration</div><div class="font-bold text-slate-900">{{ $booking->duration_hours }} hrs</div></div>
                <div><div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Event Type</div><div class="font-bold text-slate-900">{{ $booking->event_type ?: '—' }}</div></div>
            </div>

            @if($booking->special_requests)
            <div class="mt-6 pt-6 border-t border-slate-100">
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Special Requests</div>
                <div class="bg-amber-50 border border-amber-100 p-4 rounded-xl text-sm font-medium text-amber-900">{{ $booking->special_requests }}</div>
            </div>
            @endif
        </x-card>

        <!-- Financial -->
        <x-card class="p-6 border-slate-100 shadow-sm">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest mb-4">Financial Summary</h3>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between items-center"><dt class="font-bold text-slate-500">Subtotal</dt><dd class="font-black text-slate-900">RM {{ number_format($booking->subtotal,2) }}</dd></div>
                @if($booking->addons_total > 0)<div class="flex justify-between items-center"><dt class="font-bold text-slate-500">Add-ons</dt><dd class="font-black text-slate-900">RM {{ number_format($booking->addons_total,2) }}</dd></div>@endif
                @if($booking->discount_amount > 0)<div class="flex justify-between items-center"><dt class="font-bold text-emerald-600">Discount</dt><dd class="font-black text-emerald-600">−RM {{ number_format($booking->discount_amount,2) }}</dd></div>@endif
                <div class="flex justify-between items-center pt-3 border-t border-slate-100"><dt class="font-black text-slate-900 uppercase tracking-widest">Total</dt><dd class="font-black text-2xl text-brand-600">RM {{ number_format($booking->total_price,2) }}</dd></div>
                <div class="flex justify-between items-center mt-4"><dt class="font-bold text-slate-500">Deposit</dt><dd class="font-black text-amber-600">RM {{ number_format($booking->deposit_amount,2) }}</dd></div>
                <div class="flex justify-between items-center"><dt class="font-bold text-slate-500">Deposit Paid</dt><dd class="font-black text-emerald-600">RM {{ number_format($booking->deposit_paid ?? 0,2) }}</dd></div>
                <div class="flex justify-between items-center pt-3 border-t border-slate-100"><dt class="font-black text-slate-900 uppercase tracking-widest">Balance Due</dt><dd class="font-black text-xl text-slate-900">RM {{ number_format(max(0, $booking->total_price - ($booking->deposit_paid ?? 0)),2) }}</dd></div>
            </dl>
        </x-card>

        <!-- Add-ons -->
        @if($booking->addons->isNotEmpty())
        <x-card class="p-6 border-slate-100 shadow-sm">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest mb-4">Add-ons</h3>
            <div class="space-y-3">
                @foreach($booking->addons as $addon)
                <div class="flex justify-between items-center text-sm">
                    <span class="font-bold text-slate-700">{{ $addon->name }} × {{ $addon->pivot->quantity }}</span>
                    <span class="font-black text-slate-900">RM {{ number_format($addon->pivot->price_at_booking * $addon->pivot->quantity,2) }}</span>
                </div>
                @endforeach
            </div>
        </x-card>
        @endif

        <!-- Event Schedules -->
        <x-card class="overflow-hidden border-slate-100 shadow-sm" x-data="{}">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">Event Schedule</h3>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($booking->eventSchedules as $schedule)
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold bg-brand-50 text-brand-700 px-2 py-1 rounded-md border border-brand-100 mr-2 uppercase tracking-wide shadow-sm">{{ $schedule->activity_type }}</span>
                            <span class="font-bold text-slate-900">{{ $schedule->title }}</span>
                        </div>
                        <span class="text-xs font-bold font-mono text-slate-500 bg-slate-100 px-2 py-1 rounded-md">{{ $schedule->start_time }} – {{ $schedule->end_time }}</span>
                    </div>
                    @if($schedule->description)<p class="text-xs font-medium text-slate-600 mt-2">{{ $schedule->description }}</p>@endif
                    
                    <!-- Tasks inside this schedule -->
                    <div class="mt-4 space-y-2 pl-4 border-l-2 border-slate-100">
                        @foreach($schedule->tasks as $task)
                        <div class="bg-slate-50 rounded-lg p-3 flex justify-between items-center text-sm border border-slate-100">
                            <div>
                                <span class="font-bold text-slate-800">{{ $task->title }}</span>
                                <div class="text-xs text-slate-500 mt-0.5">
                                    @if($task->assigned_to) <span class="text-brand-600 font-bold"><i class="fa-solid fa-user mr-1"></i> {{ $task->assignedUser->name ?? 'User' }}</span> @endif
                                    @if($task->vendor) <span class="text-purple-600 font-bold ml-2"><i class="fa-solid fa-truck mr-1"></i> {{ $task->vendor }}</span> @endif
                                </div>
                            </div>
                            <div>
                                <x-badge variant="{{ $task->status === 'completed' ? 'emerald' : ($task->status === 'in_progress' ? 'blue' : 'slate') }}" class="text-[10px]">{{ ucfirst(str_replace('_',' ',$task->status)) }}</x-badge>
                            </div>
                        </div>
                        @endforeach

                        <!-- Add Task form (Alpine) -->
                        <div x-data="{ openTask: false }" class="mt-2">
                            <button @click="openTask = !openTask" class="text-xs font-bold text-slate-500 hover:text-slate-700">
                                <i class="fa-solid fa-plus mr-1"></i> Add Task
                            </button>
                            <div x-show="openTask" x-transition class="mt-3 bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                                <form method="POST" action="{{ route('management.bookings.tasks.store', [$booking, $schedule]) }}" class="space-y-3">
                                    @csrf
                                    <x-input type="text" name="title" placeholder="Task title *" required />
                                    <div class="grid grid-cols-2 gap-3">
                                        <x-select name="assigned_to">
                                            <option value="">-- Assign Staff --</option>
                                            @foreach($staff as $u)
                                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                                            @endforeach
                                        </x-select>
                                        <x-input type="text" name="vendor" placeholder="Or Vendor Name" />
                                    </div>
                                    <x-button type="submit" variant="slate" class="w-full justify-center">Add Task</x-button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center text-sm font-medium text-slate-500">No schedule items yet.</div>
                @endforelse
            </div>
            <!-- Add Schedule -->
            <div class="px-6 py-5 border-t border-slate-100 bg-slate-50" x-data="{ open: false }">
                <button @click="open = !open" class="text-xs font-bold uppercase tracking-widest text-brand-600 hover:text-brand-700 transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Add Schedule Item
                </button>
                <div x-show="open" x-transition class="mt-4 bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                    <form method="POST" action="{{ route('management.bookings.schedule.store', $booking) }}" class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @csrf
                        <x-input type="text" name="activity_type" placeholder="Activity type *" required />
                        <x-input type="text" name="title" placeholder="Title *" required />
                        <x-input type="date" name="scheduled_date" required value="{{ $booking->check_in->format('Y-m-d') }}" />
                        <x-input type="time" name="start_time" required />
                        <x-input type="time" name="end_time" required />
                        <x-button type="submit" variant="primary" class="w-full justify-center">Add</x-button>
                    </form>
                </div>
            </div>
        </x-card>

        <!-- Staff Assignments -->
        <x-card class="overflow-hidden border-slate-100 shadow-sm" x-data="{}">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">Staff Roster</h3>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($booking->staffAssignments as $assignment)
                <div class="px-6 py-4 flex items-center justify-between">
                    <div>
                        <div class="font-bold text-slate-900">{{ $assignment->user->name ?? 'Unknown User' }}</div>
                        <div class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-1">{{ $assignment->role }}</div>
                        <div class="text-xs font-medium text-slate-500 mt-1">
                            <i class="fa-regular fa-calendar mr-1"></i> {{ \Carbon\Carbon::parse($assignment->date)->format('M d') }}
                            @if($assignment->start_time)
                                &nbsp; <i class="fa-regular fa-clock mr-1"></i> {{ $assignment->start_time }} - {{ $assignment->end_time }}
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        <x-badge variant="{{ $assignment->status === 'confirmed' ? 'blue' : ($assignment->status === 'completed' ? 'emerald' : 'slate') }}" class="mb-2">
                            {{ ucfirst($assignment->status) }}
                        </x-badge>
                        <form method="POST" action="{{ route('management.bookings.staff.destroy', [$booking, $assignment]) }}" class="inline-block" onsubmit="return confirm('Remove staff assignment?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="block text-xs font-bold text-red-500 hover:text-red-700 ml-auto mt-1"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center text-sm font-medium text-slate-500">No staff assigned yet.</div>
                @endforelse
            </div>
            
            <div class="px-6 py-5 border-t border-slate-100 bg-slate-50" x-data="{ openStaff: false }">
                <button @click="openStaff = !openStaff" class="text-xs font-bold uppercase tracking-widest text-brand-600 hover:text-brand-700 transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-user-plus"></i> Assign Staff
                </button>
                <div x-show="openStaff" x-transition class="mt-4 bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                    <form method="POST" action="{{ route('management.bookings.staff.store', $booking) }}" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @csrf
                        <div class="col-span-full">
                            <x-select name="user_id" required>
                                <option value="">-- Select Staff --</option>
                                @foreach($staff as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->role }})</option>
                                @endforeach
                            </x-select>
                        </div>
                        <x-input type="text" name="role" placeholder="Role (e.g. Event Coordinator)" required />
                        <x-input type="date" name="date" required value="{{ $booking->check_in->format('Y-m-d') }}" />
                        <x-input type="time" name="start_time" placeholder="Start Time (Optional)" />
                        <x-input type="time" name="end_time" placeholder="End Time (Optional)" />
                        <div class="col-span-full">
                            <x-button type="submit" variant="primary" class="w-full justify-center">Assign</x-button>
                        </div>
                    </form>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Sidebar -->
    <div class="space-y-4">

        <!-- Quick Update -->
        <!-- Quick Update -->
        <x-card class="p-6 border-slate-100 shadow-sm">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest mb-4">Update Booking</h3>
            <form method="POST" action="{{ route('management.bookings.update', $booking) }}" class="space-y-4">
                @csrf @method('PATCH')
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Booking Status</label>
                    <x-select name="status">
                        @foreach(['pending','confirmed','checked_in','completed','cancelled'] as $s)
                        <option value="{{ $s }}" @selected($booking->status===$s)>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                        @endforeach
                    </x-select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Payment Status</label>
                    <x-select name="payment_status">
                        <option value="unpaid" @selected($booking->payment_status==='unpaid')>Unpaid</option>
                        <option value="paid" @selected($booking->payment_status==='paid')>Paid</option>
                        <option value="refunded" @selected($booking->payment_status==='refunded')>Refunded</option>
                    </x-select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Internal Notes</label>
                    <textarea name="internal_notes" rows="3"
                              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all font-medium text-sm resize-none">{{ $booking->internal_notes }}</textarea>
                </div>
                <x-button type="submit" variant="primary" class="w-full justify-center shadow-lg shadow-brand-500/20 py-3">
                    Update
                </x-button>
            </form>
        </x-card>

        <!-- Record Payment -->
        <x-card class="p-6 border-slate-100 shadow-sm">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest mb-4">Record Payment</h3>
            <form method="POST" action="{{ route('management.bookings.payment', $booking) }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Amount (RM)</label>
                    <x-input type="number" name="amount" step="0.01" min="0.01" required />
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Payment Type</label>
                    <x-select name="payment_type">
                        <option value="deposit">Deposit</option>
                        <option value="full">Full Payment</option>
                    </x-select>
                </div>
                <x-button type="submit" variant="emerald" class="w-full justify-center shadow-lg shadow-emerald-500/20 py-3">
                    Record Payment
                </x-button>
            </form>
        </x-card>

        <!-- Quick Links -->
        <x-card class="p-6 border-slate-100 shadow-sm space-y-3">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest mb-4">Documents</h3>
            <a href="{{ route('management.bookings.invoice', $booking) }}"
               class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-slate-100 hover:bg-slate-200 rounded-xl text-sm font-bold text-slate-700 transition-colors">
                <i class="fa-solid fa-file-invoice text-brand-600"></i> Download Invoice
            </a>
            @if($booking->quotation)
            <a href="{{ route('management.quotations.show', $booking->quotation) }}"
               class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-slate-100 hover:bg-slate-200 rounded-xl text-sm font-bold text-slate-700 transition-colors">
                <i class="fa-solid fa-file-invoice-dollar text-blue-600"></i> View Quotation
            </a>
            @endif
        </x-card>
    </div>
</div>
@endsection
