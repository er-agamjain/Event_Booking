@extends('layouts.app')

@section('title', 'Edit Event')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('organiser.events.index') }}" class="text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left"></i> Back to My Events
            </a>
            <h1 class="text-3xl font-bold text-gray-800 mt-2">Edit Event</h1>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('organiser.events.update', $event) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-bold mb-2">Event Name</label>
                    <input type="text" name="name" value="{{ old('name', $event->name) }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-bold mb-2">Description</label>
                    <textarea name="description" rows="4" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>{{ old('description', $event->description) }}</textarea>
                </div>
                
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Location</label>
                    <input type="text" name="location" value="{{ old('location', $event->location) }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Category</label>
                    <select name="category" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select Category</option>
                        <option value="Conference" {{ $event->category == 'Conference' ? 'selected' : '' }}>Conference</option>
                        <option value="Workshop" {{ $event->category == 'Workshop' ? 'selected' : '' }}>Workshop</option>
                        <option value="Seminar" {{ $event->category == 'Seminar' ? 'selected' : '' }}>Seminar</option>
                        <option value="Concert" {{ $event->category == 'Concert' ? 'selected' : '' }}>Concert</option>
                        <option value="Sports" {{ $event->category == 'Sports' ? 'selected' : '' }}>Sports</option>
                        <option value="Festival" {{ $event->category == 'Festival' ? 'selected' : '' }}>Festival</option>
                        <option value="Exhibition" {{ $event->category == 'Exhibition' ? 'selected' : '' }}>Exhibition</option>
                        <option value="Other" {{ $event->category == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Event Date</label>
                    <input type="date" name="event_date" value="{{ old('event_date', $event->event_date) }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Event Time</label>
                    <div class="flex gap-2 items-center">
                        <input type="time" name="event_time" value="{{ old('event_time', $event->event_time) }}" id="eventTime" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <span id="eventTimeDisplay" class="text-gray-600 font-semibold min-w-16 text-center">--:-- --</span>
                    </div>
                </div>
                
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Capacity</label>
                    <input type="number" name="capacity" value="{{ old('capacity', $event->capacity) }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" min="1" required>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-bold mb-2">Event Image</label>
                    @if($event->image)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->name }}" class="w-32 h-32 object-cover rounded-lg">
                        </div>
                    @endif
                    <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-sm text-gray-600 mt-1">Leave empty to keep current image</p>
                </div>
            </div>
            
            <div class="pt-4 flex space-x-4 border-t border-gray-200">
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors flex-1">
                    <i class="fas fa-save"></i> Update Event
                </button>
                <a href="{{ route('organiser.events.index') }}" class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition-colors flex-1 text-center">
                    Cancel
                </a>
            </div>
            </form>
        </div>

        <!-- Tickets Section -->
        <div class="bg-white rounded-lg shadow p-6 mt-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Event Tickets</h2>
                <a href="{{ route('organiser.tickets.create', $event) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-plus"></i> Add Ticket
                </a>
            </div>
            
            <div class="space-y-4">
                @forelse($event->tickets as $ticket)
                    <div class="border rounded-lg p-4 flex justify-between items-center hover:shadow-md transition">
                        <div>
                            <h4 class="font-bold text-lg text-gray-800">{{ $ticket->name }}</h4>
                            <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600">
                                <span><i class="fas fa-indian-rupee-sign text-green-500"></i> ₹{{ number_format($ticket->price, 2) }}</span>
                                <span><i class="fas fa-ticket-alt text-blue-500"></i> {{ $ticket->quantity - $ticket->quantity_sold }} / {{ $ticket->quantity }} available</span>
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">{{ ucfirst($ticket->ticket_type) }}</span>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('organiser.tickets.edit', $ticket) }}" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('organiser.tickets.destroy', $ticket) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Delete this ticket?')" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-8">No tickets created yet</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
function formatTo12Hour(time24) {
    if (!time24) return '--:-- --';
    const [hours, minutes] = time24.split(':');
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const hour12 = hour % 12 || 12;
    return `${String(hour12).padStart(2, '0')}:${minutes} ${ampm}`;
}

document.addEventListener('DOMContentLoaded', function() {
    const eventInput = document.getElementById('eventTime');
    const eventDisplay = document.getElementById('eventTimeDisplay');

    if (eventInput && eventDisplay) {
        eventInput.addEventListener('change', function() {
            eventDisplay.textContent = formatTo12Hour(this.value);
        });
        eventDisplay.textContent = formatTo12Hour(eventInput.value);
    }
});
</script>
@endsection
