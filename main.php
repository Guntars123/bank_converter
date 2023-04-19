<?php declare(strict_types=1);

require_once "vendor/autoload.php";

use App\ApiClient;

$client = new ApiClient();

$fromCurrency = readline("Please input currency You have: ");
$toCurrency = readline("Please input currency You want: ");
$inputAmount = (float)readline("Enter how much to convert: ");

$result = $client->convert($inputAmount, $fromCurrency, $toCurrency);

if ($result != null) {
    echo "That will be " . number_format($result, 2) . " {$toCurrency};" . PHP_EOL;
} else {
    echo "There is no such currency" . PHP_EOL;
}

