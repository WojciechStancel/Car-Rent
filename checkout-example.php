<?php

require __DIR__ . "/vendor/autoload.php";

$stripe_secret_key = "YOUR_STRIPE_SECRET_KEY";

\Stripe\Stripe::setApiKey($stripe_secret_key);

$checkout_session = \Stripe\Checkout\Session::create([
    "mode" => "payment",
    "success_url" => "http://localhost/cars/eureka.php",
    "cancel_url" => "http://localhost/cars/view.php",
    "locale" => "auto",
    "line_items" => [
        [
            "quantity" => 1,
            "price_data" => [
                "currency" => "pln",
                "unit_amount" => 2000,
                "product_data" => [
                    "name" => "T-shirt"
                ]
            ]
        ],
    ]
]);

http_response_code(303);
header("Location: " . $checkout_session->url);
