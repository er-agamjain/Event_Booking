<?php

namespace App\Http\Controllers\User;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Seat;
use App\Notifications\BookingConfirmed;
use App\Notifications\PaymentSuccessful;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function create(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->payment_status !== 'pending') {
            return redirect()->back()->with('info', 'Payment already processed');
        }

        return view('user.payments.create', compact('booking'));
    }

    public function store(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:stripe,paypal,razorpay,upi,netbanking,wallet',
        ]);

        // Redirect to payment processing page instead of immediately completing payment
        return view('user.payments.process', [
            'booking' => $booking->load(['event', 'showTiming', 'seats']),
            'paymentMethod' => $validated['payment_method']
        ]);
    }

    // New method to confirm payment after user completes it
    public function confirm(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->payment_status !== 'pending') {
            return redirect()->route('user.bookings.index')->with('info', 'Payment already processed');
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:stripe,paypal,razorpay,upi,netbanking,wallet',
        ]);

        $paymentMethod = $validated['payment_method'];

        // Process payment based on method
        return match ($paymentMethod) {
            'stripe' => $this->processStripePayment($booking),
            'paypal' => $this->processPayPalPayment($booking),
            'razorpay' => $this->processRazorpayPayment($booking),
            default => $this->processGenericPayment($booking, $paymentMethod),
        };
    }

    private function processStripePayment(Booking $booking)
    {
        // Implementation for Stripe payment
        // This is a placeholder - integrate actual Stripe API
        try {
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'user_id' => Auth::id(),
                'amount' => $booking->total_price,
                'payment_method' => 'stripe',
                'status' => 'pending',
                'payment_date' => now(),
            ]);

            // Keep booking in pending status until admin verifies payment
            // Don't update booking status or send notifications yet

            return redirect()->route('user.payments.pending', ['booking' => $booking->id, 'payment' => $payment->id])
                ->with('success', 'Payment submitted! Awaiting verification.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }

    private function processPayPalPayment(Booking $booking)
    {
        // Implementation for PayPal payment
        // This is a placeholder - integrate actual PayPal API
        try {
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'user_id' => Auth::id(),
                'amount' => $booking->total_price,
                'payment_method' => 'paypal',
                'status' => 'pending',
                'payment_date' => now(),
            ]);

            // Keep booking in pending status until admin verifies payment
            // Don't update booking status or send notifications yet

            return redirect()->route('user.payments.pending', ['booking' => $booking->id, 'payment' => $payment->id])
                ->with('success', 'Payment submitted! Awaiting verification.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }

    private function processRazorpayPayment(Booking $booking)
    {
        // Razorpay payment processing
        try {
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'user_id' => Auth::id(),
                'amount' => $booking->total_price,
                'payment_method' => 'razorpay',
                'status' => 'pending',
                'payment_date' => now(),
            ]);

            // Keep booking in pending status until admin verifies payment
            // Don't update booking status or send notifications yet

            return redirect()->route('user.payments.pending', ['booking' => $booking->id, 'payment' => $payment->id])
                ->with('success', 'Payment submitted! Awaiting verification.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }

    private function processGenericPayment(Booking $booking, string $method)
    {
        // Generic success path for UPI / netbanking / wallets
        try {
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'user_id' => Auth::id(),
                'amount' => $booking->total_price,
                'payment_method' => $method,
                'status' => 'pending',
                'payment_date' => now(),
            ]);

            // Keep booking in pending status until admin verifies payment
            // Don't update booking status or send notifications yet

            return redirect()->route('user.payments.pending', ['booking' => $booking->id, 'payment' => $payment->id])
                ->with('success', 'Payment submitted! Awaiting verification.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }

    public function pending(Booking $booking, Payment $payment)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($payment->booking_id !== $booking->id) {
            abort(403);
        }

        return view('user.payments.pending', compact('booking', 'payment'));
    }

    public function success(Booking $booking, Payment $payment)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($payment->booking_id !== $booking->id) {
            abort(403);
        }

        return view('user.payments.success', compact('booking', 'payment'));
    }

    private function markSeatsAsBooked(Booking $booking)
    {
        // Update all seats associated with this booking to 'booked' status
        $booking->seats()->update(['status' => 'booked']);
    }
}
