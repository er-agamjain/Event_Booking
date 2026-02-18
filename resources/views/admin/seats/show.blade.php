@extends('layouts.app')

@section('title', 'View Seat')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div>
        <a href="{{ route('admin.seats.index') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
            <i class="fas fa-arrow-left mr-2"></i>Back to Seats
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Seat Details</h1>
    </div>

    <!-- Seat Details -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 pb-6 border-b border-gray-200">
            <div>
                <p class="text-gray-600 text-sm">Seat Number</p>
                <p class="text-lg font-bold">{{ $seat->seat_number }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Row</p>
                <p class="text-lg font-bold">{{ $seat->row_number }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Column</p>
                <p class="text-lg font-bold">{{ $seat->column_number }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Status</p>
                <span class="px-3 py-1 rounded-full text-sm font-semibold inline-block
                    @if($seat->status === 'available') bg-emerald-100 text-emerald-800
                    @elseif($seat->status === 'reserved') bg-amber-100 text-amber-800
                    @elseif($seat->status === 'booked') bg-blue-100 text-blue-800
                    @else bg-red-100 text-red-800
                    @endif
                ">
                    {{ ucfirst($seat->status) }}
                </span>
            </div>
        </div>

        <!-- Category Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-6 border-b border-gray-200">
            <div>
                <p class="text-gray-600 text-sm">Category</p>
                <div class="flex items-center gap-2 mt-1">
                    <span class="w-4 h-4 rounded" style="background-color: {{ $seat->seatCategory->color }};"></span>
                    <p class="text-lg font-bold">{{ $seat->seatCategory->name }}</p>
                </div>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Price</p>
                <p class="text-lg font-bold">₹{{ number_format($seat->current_price ?? $seat->seatCategory->base_price, 2) }}</p>
            </div>
        </div>

        <!-- Event Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-6 border-b border-gray-200">
            <div>
                <p class="text-gray-600 text-sm">Event</p>
                <p class="text-lg font-bold">{{ $seat->showTiming->event->name }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Venue</p>
                <p class="text-lg font-bold">{{ $seat->showTiming->venue->name }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Show Date & Time</p>
                <p class="text-lg font-bold">{{ $seat->showTiming->show_date_time->format('M d, Y H:i') }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Duration</p>
                <p class="text-lg font-bold">{{ $seat->showTiming->duration_minutes }} minutes</p>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-4">
            <a href="{{ route('admin.seats.edit', $seat) }}" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-center">
                <i class="fas fa-edit mr-2"></i>Edit Seat
            </a>
            <a href="{{ route('admin.seats.index') }}" class="flex-1 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition text-center">
                Back to List
            </a>
        </div>
    </div>
</div>
@endsection
