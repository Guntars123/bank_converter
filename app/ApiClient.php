<?php declare(strict_types=1);

namespace App;

use App\Models\Currency;
use GuzzleHttp\Client;

class ApiClient
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function convert(float $amount, string $fromCurrency, string $toCurrency): ?float
    {
        /** @var Currency $currency */

        $currencies = $this->fetch();

        if (!array_key_exists($fromCurrency, $currencies) || !array_key_exists($toCurrency, $currencies)) {
            return null;
        }

        $currency = $currencies[$toCurrency];
        $currencyToEur = $currencies[$fromCurrency];
        $amountInEur = $amount * (1 / $currencyToEur->getRate());

        if ($toCurrency == "EUR") {
            return $amountInEur;
        }

        return $amountInEur * $currency->getRate();
    }

    private function fetch(): array
    {
        $response = $this->client->request('GET', "https://www.latvijasbanka.lv/vk/ecb.xml");
        $records = simplexml_load_string($response->getBody()->getContents());

        $currencies = [];
        foreach ($records->Currencies->Currency as $record) {
            $currencies[(string)$record->ID] = new Currency((string)$record->ID, (float)$record->Rate);
        }
        $currencies["EUR"] = new Currency("EUR", 1);
        return $currencies;
    }
}