<!-- Interactive Seat Selector -->
<div class="rounded-3xl border border-white/10 bg-white/5 text-white shadow-2xl p-6 space-y-6" id="seatSelectorContainer">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold">Select Your Seats</h2>
        <select id="showTimingSelect" class="px-4 py-2 rounded-lg bg-slate-900 border border-white/10 text-white text-sm">
            <option value="">Choose a show timing...</option>
            @if($event->showTimings->isNotEmpty())
                @foreach($event->showTimings as $showTiming)
                    <option value="{{ $showTiming->id }}">
                        {{ $showTiming->show_date_time->format('M d, Y H:i') }} - {{ $showTiming->venue->name ?? 'Venue' }}
                    </option>
                @endforeach
            @endif
        </select>
    </div>

    <!-- Seat Map -->
    <div id="seatMapContainer" class="hidden">
        <div class="bg-slate-900/60 rounded-xl p-6 border border-white/10">
            <!-- Screen -->
            <div class="text-center text-slate-300 text-sm mb-6 font-semibold pb-4 border-b border-white/10">
                <i class="fas fa-image mr-2"></i>SCREEN
            </div>

            <!-- Seat Grid -->
            <div id="seatGrid" class="grid gap-2 mb-6" style="justify-content: center;">
                <!-- Seats will be dynamically loaded here -->
            </div>

            <!-- Legend -->
            <div class="flex flex-wrap gap-6 text-sm text-slate-200 pt-4 border-t border-white/10 justify-center">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-emerald-500 cursor-pointer"></div>
                    <span>Available</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-slate-500"></div>
                    <span>Booked</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded border-2 border-amber-400"></div>
                    <span>Selected</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Price Info -->
    <div id="categoryPrices" class="hidden">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            <!-- Category prices will be loaded here -->
        </div>
    </div>

    <!-- Selected Seats Summary -->
    <div id="selectedSeatsSummary" class="hidden rounded-xl bg-slate-900/60 border border-white/10 p-4 space-y-3">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold">Selected Seats</h3>
            <button type="button" id="clearSeatsBtn" class="text-sm text-slate-300 hover:text-white transition">
                Clear All
            </button>
        </div>
        <div id="selectedSeatsList" class="flex flex-wrap gap-2">
            <!-- Selected seats will be shown here -->
        </div>
        <div class="pt-3 border-t border-white/10 flex items-center justify-between">
            <div>
                <p class="text-slate-300 text-sm">Total Price</p>
                <p class="text-2xl font-bold text-amber-300">₹<span id="totalPrice">0</span></p>
            </div>
            <form id="bookingForm" method="POST" action="">
                @csrf
                <input type="hidden" name="show_timing_id" id="showTimingIdInput">
                <button type="submit" class="btn-primary-luxury">
                    <i class="fas fa-check"></i> Confirm Booking
                </button>
            </form>
        </div>
    </div>

    <!-- No Show Timing Message -->
    <div id="noShowTimingMessage" class="text-center py-8 text-slate-300">
        <i class="fas fa-clock text-3xl mb-3"></i>
        <p>Please select a show timing to view available seats</p>
    </div>

    @if($event->showTimings->isEmpty())
        <div class="rounded-xl border border-amber-400/30 bg-amber-500/10 text-amber-100 p-4">
            <i class="fas fa-info-circle mr-2"></i>
            <span>Show timings will be available soon. Check back later!</span>
        </div>
    @endif
</div>

<style>
    .seat-btn {
        width: 2.5rem;
        height: 2.5rem;
        padding: 0.5rem;
        border-radius: 0.375rem;
        border: 2px solid transparent;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 0.7rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .seat-available {
        background-color: rgb(16 185 129 / 0.7);
        color: white;
    }

    .seat-available:hover {
        background-color: rgb(16 185 129);
        transform: scale(1.1);
    }

    .seat-booked {
        background-color: rgb(100 116 139 / 0.5);
        color: rgb(148 163 184 / 0.5);
        cursor: not-allowed;
    }

    .seat-selected {
        background-color: rgb(16 185 129);
        border-color: rgb(251 191 36);
        color: white;
    }

    .seat-row {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        align-items: center;
    }

    .row-label {
        width: 2rem;
        text-align: right;
        font-size: 0.75rem;
        color: rgb(148 163 184);
        font-weight: 600;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const showTimingSelect = document.getElementById('showTimingSelect');
    const seatMapContainer = document.getElementById('seatMapContainer');
    const seatGrid = document.getElementById('seatGrid');
    const categoryPrices = document.getElementById('categoryPrices');
    const selectedSeatsSummary = document.getElementById('selectedSeatsSummary');
    const selectedSeatsList = document.getElementById('selectedSeatsList');
    const noShowTimingMessage = document.getElementById('noShowTimingMessage');
    const bookingForm = document.getElementById('bookingForm');
    const showTimingIdInput = document.getElementById('showTimingIdInput');
    const totalPriceSpan = document.getElementById('totalPrice');
    const clearSeatsBtn = document.getElementById('clearSeatsBtn');

    let selectedSeats = new Map(); // Map of seatId -> {id, number, row, category, price}
    let currentSeatsData = [];

    showTimingSelect.addEventListener('change', async function() {
        const showTimingId = this.value;
        
        if (!showTimingId) {
            seatMapContainer.classList.add('hidden');
            categoryPrices.classList.add('hidden');
            selectedSeatsSummary.classList.add('hidden');
            noShowTimingMessage.classList.remove('hidden');
            selectedSeats.clear();
            updateSummary();
            return;
        }

        try {
            const response = await fetch(`/user/show-timings/${showTimingId}/seats`);
            const data = await response.json();

            currentSeatsData = data.seats;
            
            // Update form action and show timing ID
            const eventId = '{{ $event->id }}';
            bookingForm.action = `/user/bookings/${eventId}`;
            showTimingIdInput.value = showTimingId;

            // Clear previous selection
            selectedSeats.clear();

            // Render seat map
            renderSeatMap(data.seats, data.categories);

            // Render category prices
            renderCategoryPrices(data.categories);

            // Show/hide elements
            seatMapContainer.classList.remove('hidden');
            categoryPrices.classList.remove('hidden');
            noShowTimingMessage.classList.add('hidden');
            updateSummary();
        } catch (error) {
            console.error('Error fetching seats:', error);
            alert('Failed to load available seats. Please try again.');
        }
    });

    function renderSeatMap(seatsData, categories) {
        seatGrid.innerHTML = '';

        // Group seats by row
        const seatsByRow = new Map();
        
        seatsData.forEach(category => {
            category.seats.forEach(seat => {
                if (!seatsByRow.has(seat.row)) {
                    seatsByRow.set(seat.row, []);
                }
                seatsByRow.get(seat.row).push({
                    ...seat,
                    category_id: category.category_id,
                    category_name: category.category_name,
                    category_color: category.category_color
                });
            });
        });

        // Sort rows and render
        const sortedRows = Array.from(seatsByRow.keys()).sort((a, b) => a - b);

        sortedRows.forEach(row => {
            const rowSeats = seatsByRow.get(row).sort((a, b) => a.column - b.column);
            const rowDiv = document.createElement('div');
            rowDiv.className = 'seat-row';

            // Row label
            const rowLabel = document.createElement('span');
            rowLabel.className = 'row-label';
            rowLabel.textContent = String.fromCharCode(64 + row); // A, B, C...
            rowDiv.appendChild(rowLabel);

            // Seats in row
            rowSeats.forEach(seat => {
                const seatBtn = document.createElement('button');
                seatBtn.type = 'button';
                    const isAvailable = seat.status === 'available';
                    const statusClass = isAvailable ? 'seat-available' : 'seat-booked';
                    seatBtn.className = `seat-btn ${statusClass}`;
                seatBtn.textContent = seat.column;
                seatBtn.title = `${seat.category_name} - ₹${Math.round(seat.price)} (${seat.status})`;
                seatBtn.dataset.seatId = seat.id;
                seatBtn.dataset.seatNumber = seat.seat_number;
                seatBtn.dataset.categoryName = seat.category_name;
                seatBtn.dataset.price = seat.price;
                seatBtn.dataset.row = row;
                seatBtn.dataset.column = seat.column;

                    if (isAvailable) {
                        seatBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            toggleSeat(seatBtn);
                        });
                    } else {
                        seatBtn.disabled = true;
                    }

                rowDiv.appendChild(seatBtn);
            });

            seatGrid.appendChild(rowDiv);
        });
    }

    function renderCategoryPrices(categories) {
        categoryPrices.innerHTML = '';

        categories.forEach(category => {
            const priceDiv = document.createElement('div');
            priceDiv.className = 'rounded-lg bg-slate-900/60 border border-white/10 p-3 flex items-center justify-between';
            priceDiv.innerHTML = `
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded" style="background-color: ${category.color}60;"></span>
                    <span class="text-sm">${category.name}</span>
                </div>
                <span class="font-semibold text-amber-300">₹${Math.round(category.base_price)}</span>
            `;
            categoryPrices.appendChild(priceDiv);
        });
    }

    function toggleSeat(seatBtn) {
        const seatId = seatBtn.dataset.seatId;
        const price = parseFloat(seatBtn.dataset.price);

        if (selectedSeats.has(seatId)) {
            // Deselect
            selectedSeats.delete(seatId);
            seatBtn.classList.remove('seat-selected');
            seatBtn.classList.add('seat-available');
        } else {
            // Select
            selectedSeats.set(seatId, {
                id: seatId,
                number: seatBtn.dataset.seatNumber,
                row: seatBtn.dataset.row,
                column: seatBtn.dataset.column,
                category: seatBtn.dataset.categoryName,
                price: price
            });
            seatBtn.classList.remove('seat-available');
            seatBtn.classList.add('seat-selected');
        }

        updateSummary();
    }

    function updateSummary() {
        if (selectedSeats.size === 0) {
            selectedSeatsSummary.classList.add('hidden');
            // Clear all existing seat_ids inputs
            document.querySelectorAll('input[name="seat_ids[]"]').forEach(input => input.remove());
        } else {
            selectedSeatsSummary.classList.remove('hidden');
            
            // Update selected seats list
            selectedSeatsList.innerHTML = '';
            let totalPrice = 0;

            const sortedSeats = Array.from(selectedSeats.values())
                .sort((a, b) => a.row - b.row || a.column - b.column);

            // Clear existing seat_ids inputs
            document.querySelectorAll('input[name="seat_ids[]"]').forEach(input => input.remove());

            // Create individual hidden inputs for each seat
            sortedSeats.forEach(seat => {
                const tag = document.createElement('span');
                tag.className = 'px-3 py-1 rounded-full text-xs bg-emerald-500/20 border border-emerald-400/30 text-emerald-100';
                tag.textContent = `${String.fromCharCode(64 + parseInt(seat.row))}${seat.column} (${seat.category})`;
                selectedSeatsList.appendChild(tag);
                
                // Create hidden input for this seat ID
                const seatInput = document.createElement('input');
                seatInput.type = 'hidden';
                seatInput.name = 'seat_ids[]';
                seatInput.value = seat.id;
                bookingForm.appendChild(seatInput);
                
                totalPrice += seat.price;
            });

            totalPriceSpan.textContent = Math.round(totalPrice);
        }
    }

    clearSeatsBtn.addEventListener('click', function() {
        selectedSeats.forEach((seat, seatId) => {
            const seatBtn = document.querySelector(`[data-seat-id="${seatId}"]`);
            if (seatBtn) {
                seatBtn.classList.remove('seat-selected');
                seatBtn.classList.add('seat-available');
            }
        });
        selectedSeats.clear();
        updateSummary();
    });

    // Prevent form submission if no seats selected
    bookingForm.addEventListener('submit', function(e) {
        if (selectedSeats.size === 0) {
            e.preventDefault();
            alert('Please select at least one seat');
            return false;
        }
    });
});
</script>
