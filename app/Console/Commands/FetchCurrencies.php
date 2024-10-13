<?php

namespace App\Console\Commands;

use App\Models\Currency;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchCurrencies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-currencies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Fetch all currencies from https://github.com/bitfactory-robin-martijn/common-currency
        $url = 'https://raw.githubusercontent.com/bitfactory-robin-martijn/common-currency/refs/heads/main/common-currency.json';

        try {
            $this->info('Fetching currencies...');
            $response = Http::get($url);

            if ($response->successful()) {
                $data = $response->json();
                $payload = [];

                foreach ($data as $code => $currency) {
                    $payload[] = [
                        'name' => $currency['name'],
                        'code' => $code,
                        'symbol' => $currency['symbol'],
                        'decimal_digits' => $currency['decimal_digits'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                Currency::insert($payload);

                $this->info('Currencies fetched successfully.');
            } else {
                $this->error('Failed to fetch currencies.');
            }
        } catch (\Exception $e) {
            $this->error('Error: ', $e);
        }
    }
}
