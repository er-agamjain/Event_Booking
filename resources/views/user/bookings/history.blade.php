@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold mb-8 text-white">My Bookings</h1>

        @if($bookings->isNotEmpty())
            <div class="grid gap-4">
                @foreach($bookings as $booking)
                    <div class="rounded-xl border border-white/10 bg-white/5 text-white p-6 hover:bg-white/10 transition">
                        <div class="grid md:grid-cols-5 gap-4">
                            <div>
                                <p class="text-xs text-slate-300">Booking Reference</p>
                                <p class="font-semibold">{{ $booking->booking_reference }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-300">Event</p>
                                <p class="font-semibold">{{ $booking->event->name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-300">Seats</p>
                                <p class="font-semibold">
                                    @if($booking->seats->isNotEmpty())
                                        {{ $booking->seats->count() }} seat(s)
                                    @else
                                        {{ $booking->quantity }} ticket(s)
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-300">Total Price</p>
                                <p class="font-semibold text-amber-300">₹{{ number_format($booking->total_price, 0) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-300">Status</p>
                                <p class="font-semibold">
                                    <span class="px-3 py-1 rounded-full text-xs
                                        {{ $booking->status === 'confirmed' ? 'bg-emerald-500/20 text-emerald-100 border border-emerald-400/30' : 'bg-amber-500/20 text-amber-100 border border-amber-400/30' }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-2 mt-4 pt-4 border-t border-white/10">
                            <a href="{{ route('user.bookings.show', $booking) }}" class="btn-secondary-luxury text-sm">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                            @if($booking->status === 'confirmed')
                                <a href="{{ route('user.tickets.download', $booking) }}" class="btn-primary-luxury text-sm">
                                    <i class="fas fa-download"></i> Download Ticket
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @if($bookings->hasPages())
                <div class="mt-8">
                    {{ $bookings->links() }}
                </div>
            @endif
        @else
            <div class="rounded-xl border border-white/10 bg-white/5 text-white p-12 text-center">
                <i class="fas fa-inbox text-4xl text-slate-400 mb-4"></i>
                <p class="text-slate-300 mb-4">No bookings yet</p>
                <a href="{{ route('user.events.index') }}" class="btn-primary-luxury inline-block">
                    Browse Events
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
