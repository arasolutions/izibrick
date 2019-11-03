<?php


namespace App\Helper;


class Stripe
{

    private $apiKey;

    public  function __construct($apiKey='sk_test_RXvfmDOXdTDWwTuLB5eSFEQo00kWMqsuGe') {
        $this->$apiKey = $apiKey;
        \Stripe\Stripe::setApiKey($apiKey);
    }

    /**
     * @param $name
     * @param $description
     * @param $email
     * @return string
     * @throws \Stripe\Exception\ApiErrorException
     * Créer un client
     */
    public function createCustomer($name, $description, $email){
        $customer = \Stripe\Customer::create([
            'name' => $name,
            'description' => $description,
            'email' => $email,
        ]);

        return $customer->id;
    }

    /**
     * @param $customerId
     * @return static
     * @throws \Stripe\Exception\ApiErrorException
     * Retrouver un client
     */
    public function getCustomer($customerId){
        $customer = \Stripe\Customer::retrieve($customerId);

        return $customer;
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
    public function createCharge($amount, $source, $description, $eur = 'eur'){
        $charge = \Stripe\Charge::create([
            'amount' => $amount,
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
    public function createCard($customerId, $source){
        $card = \Stripe\Customer::createSource(
            $customerId,
            [
                'source' => $source,
            ]
        );

        return $card;
    }

    /**
     * @param $name
     * @param string $type
     * @return static
     * @throws \Stripe\Exception\ApiErrorException
     * Créer un produit
     */
    public function createProduct($name, $type = 'service'){
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
    public function getProduct($productId){
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
    public function createPlan($amount, $product, $currency = 'eur', $interval = 'month'){
        $plan = \Stripe\Plan::create([
            'amount' => $amount,
            'currency' => $currency,
            'interval' => $interval,
            'product' => ['name' => $product],
        ]);

        return $plan;
    }

}