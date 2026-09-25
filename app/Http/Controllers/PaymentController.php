<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Stripe\Exception\SignatureVerificationException;
use Stripe\StripeClient;
use Stripe\Webhook;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    public function show(Booking $booking): View|RedirectResponse
    {
        if ($booking->payment_status === 'paid') {
            return redirect()->route('bookings.confirmation', $booking);
        }

        if (config('services.stripe.secret')) {
            $stripe = new StripeClient(config('services.stripe.secret'));

            $session = $stripe->checkout->sessions->create([
                'mode' => 'payment',
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'myr',
                        'product_data' => ['name' => "Booking {$booking->booking_number}"],
                        'unit_amount' => (int) round($booking->total_price * 100),
                    ],
                    'quantity' => 1,
                ]],
                'customer_email' => $booking->guest_email,
                'client_reference_id' => $booking->booking_number,
                'success_url' => route('bookings.pay.success', $booking).'?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('bookings.pay', $booking),
            ]);

            return redirect()->away($session->url);
        }

        return view('bookings.pay', compact('booking'));
    }

    public function success(Request $request, Booking $booking): RedirectResponse
    {
        if ($booking->payment_status !== 'paid' && config('services.stripe.secret') && $request->filled('session_id')) {
            $stripe = new StripeClient(config('services.stripe.secret'));
            $session = $stripe->checkout->sessions->retrieve($request->string('session_id'));

            if ($session->payment_status === 'paid') {
                $this->markPaid($booking);
            }
        }

        return redirect()->route('bookings.confirmation', $booking)
            ->with('success', 'Payment received. Your booking is confirmed!');
    }

    public function process(Booking $booking): RedirectResponse
    {
        $this->markPaid($booking);

        return redirect()->route('bookings.confirmation', $booking)
            ->with('success', 'Payment received. Your booking is confirmed!');
    }

    public function webhook(Request $request): Response
    {
        $webhookSecret = config('services.stripe.webhook_secret');

        try {
            $event = $webhookSecret
                ? Webhook::constructEvent($request->getContent(), $request->header('Stripe-Signature', ''), $webhookSecret)
                : json_decode($request->getContent());
        } catch (SignatureVerificationException|\UnexpectedValueException $e) {
            Log::warning('Stripe webhook signature verification failed', ['error' => $e->getMessage()]);

            return response('Invalid signature', 400);
        }

        if (($event->type ?? null) === 'checkout.session.completed') {
            $session = $event->data->object;
            $booking = Booking::where('booking_number', $session->client_reference_id ?? null)->first();

            if ($booking && $booking->payment_status !== 'paid') {
                $this->markPaid($booking);
            }
        }

        return response('Webhook handled', 200);
    }

    private function markPaid(Booking $booking): void
    {
        if ($booking->payment_status === 'paid') {
            return;
        }

        $booking->update([
            'payment_status' => 'paid',
            'status' => $booking->status === 'pending' ? 'confirmed' : $booking->status,
        ]);

        if ($booking->user_id) {
            $pointsEarned = (int) floor($booking->total_price / 10);

            if ($pointsEarned > 0) {
                $booking->user->increment('loyalty_points', $pointsEarned);
            }
        }
    }
}
