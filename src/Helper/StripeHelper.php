<?php


namespace App\Helper;


class StripeHelper
{

    private $apiKey;

    public function __construct($apiKey = 'sk_test_RXvfmDOXdTDWwTuLB5eSFEQo00kWMqsuGe')
    {
        if ($_ENV['STRIPE_SECRET_KEY']) {
            $apiKey = $_ENV['STRIPE_SECRET_KEY'];
        }
        $this->$apiKey = $apiKey;
        \Stripe\Stripe::setApiKey($apiKey);
    }

    /**
     * @param $name
     * @param $description
     * @param $email
     * @return string
     * @throws \Stripe\Exception\ApiErrorException Créer un client
     */
    public function createCustomer($name, $description, $email)
    {

        $customer = \Stripe\Customer::create([
            'name' => $name,
            'description' => $description,
            'email' => $email,
            'preferred_locales' => ['fr'],

        ]);

        return $customer->id;
    }

    /**
     * @param $customerId
     * @param $name
     * @param $description
     * @param $email
     * @param $addressLine1
     * @param null $addressLine2
     * @param null $addressCity
     * @param null $addressPostalCode
     * @param string $addressCountry
     * @return string
     * @throws \Stripe\Exception\ApiErrorException Créer un client
     */
    public function updateCustomer($customerId, $name, $description, $email, $addressLine1, $addressLine2 = null, $addressCity = null, $addressPostalCode = null, $addressCountry = 'FRANCE')
    {
        $customer = \Stripe\Customer::update(
            $customerId, [
                'name' => $name,
                'description' => $description,
                'email' => $email,
                'address' => [
                    'line1' => $addressLine1,
                    'line2' => $addressLine2,
                    'city' => $addressCity,
                    'postal_code' => $addressPostalCode,
                    'country' => $addressCountry,
                ]]
        );
        return $customer->id;
    }


    /**
     * @param $customerId
     * @param $defaultSource
     * @return string
     * @throws \Stripe\Exception\ApiErrorException Créer un client
     */
    public function updateCustomerDefaultSource($customerId, $defaultSource)
    {
        $customer = \Stripe\Customer::update(
            $customerId, [
                'default_source' => $defaultSource
            ]
        );
        return $customer->id;
    }

    /**
     * @param $customerId
     * @return static
     * @throws \Stripe\Exception\ApiErrorException
     * Retrouver un client
     */
    public function getCustomer($customerId)
    {
        if (!$customerId) {
            return null;
        }
        $customer = \Stripe\Customer::retrieve(["id" => $customerId, "expand" => ["default_source"]]);

        return $customer;
    }

    /**
     * @param $customerId
     * @return static
     * @throws \Stripe\Exception\ApiErrorException
     * Retrouver la prochaine facture
     */
    public function getInvoiceUpcoming($customerId)
    {
        $invoice = \Stripe\Invoice::upcoming(["customer" => $customerId]);

        return $invoice;
    }

    /**
     * @param $amount
     * @param $source
     * @param $description
     * @param string $eur
     * @return static
     * Lancer le paiement
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function createCharge($amount, $source, $description, $eur = 'eur')
    {
        $charge = \Stripe\Charge::create([
            'amount' => $amount * 100,
            'currency' => $eur,
            'source' => $source,
            'description' => $description,
        ]);

        return $charge;
    }

    /**
     * @param $customerId
     * @param $source
     * @return \Stripe\ApiResource
     * @throws \Stripe\Exception\ApiErrorException
     * Créer une carte
     */
    public function createCard($customerId, $source)
    {
        $card = \Stripe\Customer::createSource(
            $customerId,
            [
                'source' => $source,
            ]
        );

        return $card;
    }

    /**
     * @param $customerId
     * @param $card
     * @return \Stripe\ApiResource
     * @throws \Stripe\Exception\ApiErrorException
     * Créer une carte
     */
    public function deleteCard($customerId, $card)
    {
        $card = \Stripe\Customer::deleteSource(
            $customerId,
            $card
        );

        return $card;
    }

    /**
     * @param $customerId
     * @return \Stripe\ApiResource
     * @throws \Stripe\Exception\ApiErrorException
     * Créer une carte
     */
    public function getAllCards($customerId)
    {
        $cards = \Stripe\Customer::allSources(
            $customerId,
            ['object' => 'card', 'limit' => 20]
        );

        return $cards->data;
    }

    /**
     * @param $name
     * @param string $type
     * @return static
     * @throws \Stripe\Exception\ApiErrorException
     * Créer un produit
     */
    public function createProduct($name, $type = 'service')
    {
        $product = \Stripe\Product::create([
            'name' => $name,
            'type' => $type,
        ]);

        return $product;
    }

    /**
     * @param $productId
     * @return static
     * @throws \Stripe\Exception\ApiErrorException
     * Retrouver un produit
     */
    public function getProduct($productId)
    {
        $product = \Stripe\Product::retrieve($productId);

        return $product;
    }

    /**
     * @param $amount
     * @param $product
     * @param string $currency
     * @param string $interval
     * @return static
     * @throws \Stripe\Exception\ApiErrorException
     * Créer un plan tarifaire (une offre)
     */
    public function createPlan($amount, $product, $currency = 'eur', $interval = 'month')
    {
        $plan = \Stripe\Plan::create([
            'amount' => $amount,
            'currency' => $currency,
            'interval' => $interval,
            'product' => ['name' => $product],
        ]);

        return $plan;
    }

    /**
     * @param $planTarifaireId
     * @return \Stripe\ApiResource
     * @throws \Stripe\Exception\ApiErrorException
     * Récupère un plan tarifaire
     */
    public function getPlan($planTarifaireId)
    {
        $plan = \Stripe\Plan::retrieve($planTarifaireId);

        return $plan;
    }

    /**
     * @param $customerId
     * @param $planTarifaireId
     * @return static
     * @throws \Stripe\Exception\ApiErrorException
     * Créer un abonnement pour un utilisateur
     */
    public function createSubscription($customerId, $planTarifaireId, $trialDays = 0)
    {
        $subsription = \Stripe\Subscription::create([
            "customer" => $customerId,
            "default_tax_rates" => [$_ENV['STRIPE_TVA_KEY']],
            "items" => [
                [
                    "plan" => $planTarifaireId,
                ],
            ],
            'trial_period_days' => $trialDays,
        ]);

        return $subsription;
    }

    /**
     * Annuler un abonnement pour un utilisateur
     * @param $substriptionId
     * @return \Stripe\Subscription
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function cancelSubscription($substriptionId)
    {

        $subscription = \Stripe\Subscription::retrieve($substriptionId);

        if ($subscription != null) {
            $subscription = $subscription->cancel();
        }

        return $subscription;
    }

    /**
     * @param $customerId
     * @return \Stripe\ApiResource
     * @throws \Stripe\Exception\ApiErrorException
     * Récupère un plan tarifaire
     */
    public function getListInvoices($customerId)
    {
        $invoices = \Stripe\Invoice::all(['customer' => $customerId]);

        return $invoices;
    }

}