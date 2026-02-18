<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('organiser')->latest()->paginate(20);
        return view('admin.events', compact('events'));
    }

    public function create()
    {
        $categories = EventCategory::where('is_active', true)->pluck('category_name', 'id');
        
        // Get unique values for community, gacchh, and tags
        $communities = EventCategory::where('is_active', true)->whereNotNull('community')->distinct()->pluck('community');
        $gacchhs = EventCategory::where('is_active', true)->whereNotNull('gacchh')->distinct()->pluck('gacchh');
        $tagsList = EventCategory::where('is_active', true)->whereNotNull('tags')->distinct()->pluck('tags');
        
        return view('admin.events.create', compact('categories', 'communities', 'gacchhs', 'tagsList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string',
            'event_date' => 'required|date|after:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_free' => 'boolean',
            'base_price' => 'required_if:is_free,false|nullable|numeric|min:0',
            'category_id' => 'nullable|exists:event_categories,id',
            'community' => 'nullable|string|max:255',
            'gacchh' => 'nullable|string|max:255',
            'tags' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $validated['organiser_id'] = Auth::id();
        $validated['status'] = 'published'; // Admin events auto-published
        $event = Event::create($validated);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('events', 'public');
            $event->update(['image' => $path]);
        }

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully! No commission will be applied.');
    }

    public function approve(Event $event)
    {
        $event->update(['status' => 'published']);
        return redirect()->back()->with('success', 'Event approved');
    }

    public function reject(Event $event)
    {
        $event->update(['status' => 'cancelled']);
        return redirect()->back()->with('success', 'Event rejected');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->back()->with('success', 'Event deleted');
    }

    public function createTicket(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'ticket_type' => 'required|in:free,paid',
        ]);

        $event->tickets()->create($validated);

        return redirect()->back()->with('success', 'Ticket created manually');
    }
}
