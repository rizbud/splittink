<?php

namespace App\Console\Commands;

use App\Models\Currency;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchCurrencyExchangeRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-currencies-rate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch latest currency exchange rate from the internet amd store it in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Fetch rate from https://github.com/fawazahmed0/exchange-api
        $url = 'https://cdn.jsdelivr.net/npm/@fawazahmed0/currency-api@latest/v1/currencies/usd.min.json';

        try {
            $this->info('Fetching currency exchange rate...');
            $response = Http::get($url);

            if ($response->successful()) {
                $data = $response->json();
                $rates = $data['usd'] ?? [];

                foreach ($rates as $code => $rate) {
                    Currency::where(
                        'code', strtoupper($code)
                    )->update([
                        'exchange_rate' => $rate,
                        'updated_at' => now()
                    ]);
                }

                $this->info('Currency exchange rate fetched successfully.');
            } else {
                $this->error('Failed to fetch currency exchange rate.');
            }
        } catch (\Exception $e) {
            $this->error('Error: ', $e);
        }
    }
}
