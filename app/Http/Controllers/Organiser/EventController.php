<?php

namespace App\Http\Controllers\Organiser;

use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $events = Auth::user()->events()->paginate(15);
        return view('organiser.events.index', compact('events'));
    }

    public function create()
    {
        $categories = \App\Models\EventCategory::where('is_active', true)->pluck('category_name', 'id');
        
        // Get unique values for community, gacchh, and tags
        $communities = \App\Models\EventCategory::where('is_active', true)->whereNotNull('community')->distinct()->pluck('community');
        $gacchhs = \App\Models\EventCategory::where('is_active', true)->whereNotNull('gacchh')->distinct()->pluck('gacchh');
        $tagsList = \App\Models\EventCategory::where('is_active', true)->whereNotNull('tags')->distinct()->pluck('tags');
        
        return view('organiser.events.create', compact('categories', 'communities', 'gacchhs', 'tagsList'));
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
        $event = Event::create($validated);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('events', 'public');
            $event->update(['image' => $path]);
        }

        return redirect()->route('organiser.events.show', $event)->with('success', 'Event created');
    }

    public function show(Event $event)
    {
        if ($event->organiser_id !== Auth::id()) {
            abort(403);
        }

        return view('organiser.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        if ($event->organiser_id !== Auth::id()) {
            abort(403);
        }

        return view('organiser.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        if ($event->organiser_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string',
            'event_date' => 'required|date',
            'event_time' => 'required|date_format:H:i',
            'capacity' => 'required|integer|min:1',
            'category' => 'required|string',
        ]);

        $event->update($validated);

        return redirect()->route('organiser.events.show', $event)->with('success', 'Event updated');
    }

    public function destroy(Event $event)
    {
        if ($event->organiser_id !== Auth::id()) {
            abort(403);
        }

        $event->delete();
        return redirect()->route('organiser.events.index')->with('success', 'Event deleted');
    }

    public function publish(Event $event)
    {
        if ($event->organiser_id !== Auth::id()) {
            abort(403);
        }

        $event->update(['status' => 'published']);
        return redirect()->route('organiser.events.show', $event)->with('success', 'Event published successfully');
    }
}
