<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Customer;
use Stripe\PaymentMethod;
use Stripe\Subscription;
use Stripe\Invoice;
use Stripe\Price;
use Stripe\Product;
use Stripe\Webhook;
use Stripe\Exception\ApiErrorException;
use App\Models\User;

class StripeService
{
    protected $stripe;

    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        $this->stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
    }

    /**
     * Create or retrieve a Stripe customer
     */
    public function createOrGetCustomer(User $user)
    {
        try {
            // If user already has a Stripe customer ID, retrieve it
            if ($user->stripe_customer_id) {
                return $this->stripe->customers->retrieve($user->stripe_customer_id);
            }

            // Create new customer
            $customer = $this->stripe->customers->create([
                'email' => $user->email,
                'name' => $user->name,
                'metadata' => [
                    'user_id' => $user->id,
                ],
            ]);

            // Save customer ID to user
            $user->update(['stripe_customer_id' => $customer->id]);

            return $customer;
        } catch (ApiErrorException $e) {
            throw new \Exception('Failed to create Stripe customer: ' . $e->getMessage());
        }
    }

    /**
     * Create a payment method
     */
    public function createPaymentMethod($paymentMethodData)
    {
        try {
            return $this->stripe->paymentMethods->create($paymentMethodData);
        } catch (ApiErrorException $e) {
            throw new \Exception('Failed to create payment method: ' . $e->getMessage());
        }
    }

    /**
     * Attach payment method to customer
     */
    public function attachPaymentMethod($paymentMethodId, $customerId)
    {
        try {
            return $this->stripe->paymentMethods->attach($paymentMethodId, [
                'customer' => $customerId,
            ]);
        } catch (ApiErrorException $e) {
            throw new \Exception('Failed to attach payment method: ' . $e->getMessage());
        }
    }

    /**
     * Create a subscription
     */
    public function createSubscription(User $user, $priceId, $paymentMethodId = null)
    {
        try {
            $customer = $this->createOrGetCustomer($user);

            $subscriptionData = [
                'customer' => $customer->id,
                'items' => [
                    ['price' => $priceId],
                ],
                'payment_behavior' => 'default_incomplete',
                'payment_settings' => ['save_default_payment_method' => 'on_subscription'],
                'expand' => ['latest_invoice.payment_intent'],
            ];

            if ($paymentMethodId) {
                $subscriptionData['default_payment_method'] = $paymentMethodId;
            }

            $subscription = $this->stripe->subscriptions->create($subscriptionData);

            // Save subscription ID to user
            $user->update([
                'stripe_subscription_id' => $subscription->id,
                'subscription_ends_at' => now()->addMonth(),
            ]);

            return $subscription;
        } catch (ApiErrorException $e) {
            throw new \Exception('Failed to create subscription: ' . $e->getMessage());
        }
    }

    /**
     * Update subscription
     */
    public function updateSubscription($subscriptionId, $newPriceId)
    {
        try {
            $subscription = $this->stripe->subscriptions->retrieve($subscriptionId);
            
            return $this->stripe->subscriptions->update($subscriptionId, [
                'items' => [
                    [
                        'id' => $subscription->items->data[0]->id,
                        'price' => $newPriceId,
                    ],
                ],
                'proration_behavior' => 'create_prorations',
            ]);
        } catch (ApiErrorException $e) {
            throw new \Exception('Failed to update subscription: ' . $e->getMessage());
        }
    }

    /**
     * Cancel subscription
     */
    public function cancelSubscription($subscriptionId, $immediately = false)
    {
        try {
            if ($immediately) {
                return $this->stripe->subscriptions->cancel($subscriptionId);
            } else {
                return $this->stripe->subscriptions->update($subscriptionId, [
                    'cancel_at_period_end' => true,
                ]);
            }
        } catch (ApiErrorException $e) {
            throw new \Exception('Failed to cancel subscription: ' . $e->getMessage());
        }
    }

    /**
     * Resume subscription
     */
    public function resumeSubscription($subscriptionId)
    {
        try {
            return $this->stripe->subscriptions->update($subscriptionId, [
                'cancel_at_period_end' => false,
            ]);
        } catch (ApiErrorException $e) {
            throw new \Exception('Failed to resume subscription: ' . $e->getMessage());
        }
    }

    /**
     * Get subscription details
     */
    public function getSubscription($subscriptionId)
    {
        try {
            return $this->stripe->subscriptions->retrieve($subscriptionId);
        } catch (ApiErrorException $e) {
            throw new \Exception('Failed to retrieve subscription: ' . $e->getMessage());
        }
    }

    /**
     * Get customer's payment methods
     */
    public function getPaymentMethods($customerId)
    {
        try {
            return $this->stripe->paymentMethods->all([
                'customer' => $customerId,
                'type' => 'card',
            ]);
        } catch (ApiErrorException $e) {
            throw new \Exception('Failed to retrieve payment methods: ' . $e->getMessage());
        }
    }

    /**
     * Create setup intent for saving payment method
     */
    public function createSetupIntent($customerId)
    {
        try {
            return $this->stripe->setupIntents->create([
                'customer' => $customerId,
                'payment_method_types' => ['card'],
            ]);
        } catch (ApiErrorException $e) {
            throw new \Exception('Failed to create setup intent: ' . $e->getMessage());
        }
    }

    /**
     * Get upcoming invoice
     */
    public function getUpcomingInvoice($customerId)
    {
        try {
            return $this->stripe->invoices->upcoming([
                'customer' => $customerId,
            ]);
        } catch (ApiErrorException $e) {
            throw new \Exception('Failed to retrieve upcoming invoice: ' . $e->getMessage());
        }
    }

    /**
     * Get customer's invoices
     */
    public function getInvoices($customerId, $limit = 10)
    {
        try {
            return $this->stripe->invoices->all([
                'customer' => $customerId,
                'limit' => $limit,
            ]);
        } catch (ApiErrorException $e) {
            throw new \Exception('Failed to retrieve invoices: ' . $e->getMessage());
        }
    }

    /**
     * Handle webhook events
     */
    public function handleWebhook($payload, $signature)
    {
        try {
            $event = Webhook::constructEvent(
                $payload,
                $signature,
                config('services.stripe.webhook_secret')
            );

            return $this->processWebhookEvent($event);
        } catch (\Exception $e) {
            throw new \Exception('Webhook signature verification failed: ' . $e->getMessage());
        }
    }

    /**
     * Process webhook events
     */
    protected function processWebhookEvent($event)
    {
        switch ($event->type) {
            case 'customer.subscription.created':
                return $this->handleSubscriptionCreated($event->data->object);
            case 'customer.subscription.updated':
                return $this->handleSubscriptionUpdated($event->data->object);
            case 'customer.subscription.deleted':
                return $this->handleSubscriptionDeleted($event->data->object);
            case 'invoice.payment_succeeded':
                return $this->handlePaymentSucceeded($event->data->object);
            case 'invoice.payment_failed':
                return $this->handlePaymentFailed($event->data->object);
            default:
                return ['status' => 'ignored', 'message' => 'Event type not handled'];
        }
    }

    /**
     * Handle subscription created event
     */
    protected function handleSubscriptionCreated($subscription)
    {
        $user = User::where('stripe_customer_id', $subscription->customer)->first();
        if ($user) {
            $user->update([
                'stripe_subscription_id' => $subscription->id,
                'subscription_ends_at' => now()->createFromTimestamp($subscription->current_period_end),
            ]);
        }
        return ['status' => 'success', 'message' => 'Subscription created'];
    }

    /**
     * Handle subscription updated event
     */
    protected function handleSubscriptionUpdated($subscription)
    {
        $user = User::where('stripe_subscription_id', $subscription->id)->first();
        if ($user) {
            $user->update([
                'subscription_ends_at' => now()->createFromTimestamp($subscription->current_period_end),
            ]);
        }
        return ['status' => 'success', 'message' => 'Subscription updated'];
    }

    /**
     * Handle subscription deleted event
     */
    protected function handleSubscriptionDeleted($subscription)
    {
        $user = User::where('stripe_subscription_id', $subscription->id)->first();
        if ($user) {
            $user->update([
                'stripe_subscription_id' => null,
                'subscription_ends_at' => now(),
                'plan' => null,
            ]);
        }
        return ['status' => 'success', 'message' => 'Subscription cancelled'];
    }

    /**
     * Handle successful payment
     */
    protected function handlePaymentSucceeded($invoice)
    {
        $user = User::where('stripe_customer_id', $invoice->customer)->first();
        if ($user) {
            // Update subscription end date
            $subscription = $this->getSubscription($invoice->subscription);
            $user->update([
                'subscription_ends_at' => now()->createFromTimestamp($subscription->current_period_end),
            ]);
        }
        return ['status' => 'success', 'message' => 'Payment succeeded'];
    }

    /**
     * Handle failed payment
     */
    protected function handlePaymentFailed($invoice)
    {
        $user = User::where('stripe_customer_id', $invoice->customer)->first();
        if ($user) {
            // Handle failed payment - could send notification, suspend account, etc.
            \Log::warning('Payment failed for user: ' . $user->id);
        }
        return ['status' => 'success', 'message' => 'Payment failed handled'];
    }

    /**
     * Get plan price IDs
     */
    public function getPlanPriceIds()
    {
        return [
            'starter' => config('services.stripe.price_ids.starter'),
            'pro' => config('services.stripe.price_ids.pro'),
            'diablo' => config('services.stripe.price_ids.diablo'),
        ];
    }
}
