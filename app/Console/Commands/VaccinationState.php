<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Atymic\Twitter\Facade\Twitter;
use Illuminate\Support\Facades\Http;

class VaccinationState extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'VaccinationStateBot:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send new tweet about vaccination progress in Morocco everyday!.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('VaccineProgressbot_ma');
        $this->newLine();

        $response = Http::get(constants('VACCIN_OWID_API'));

        if (!$response->ok()) {
            $this->warn('⚠️ Failed to retrieve data!.');
            return 1;
        }

        $data = $response->json();

        for ($i = 0; $i <= count($data) - 1; $i++) {

            if ($data[$i]['country'] == constants('COUNTRY')) {
                $last = count($data[$i]['data']) - 1;

                $date = isset($data[$i]['data'][$last]['date']) ? $data[$i]['data'][$last]['date'] : null;
                $daily_vaccinations = isset($data[$i]['data'][$last]['daily_vaccinations']) ? $data[$i]['data'][$last]['daily_vaccinations'] : null;
                $total_vaccinations = isset($data[$i]['data'][$last]['total_vaccinations']) ? $data[$i]['data'][$last]['total_vaccinations'] : null;
                $people_vaccinated = isset($data[$i]['data'][$last]['people_vaccinated']) ? $data[$i]['data'][$last]['people_vaccinated'] : null;
                $people_fully_vaccinated = isset($data[$i]['data'][$last]['people_fully_vaccinated']) ? $data[$i]['data'][$last]['people_fully_vaccinated'] : null;

                if ($date) {
                    try {
                        $this->SendTweet('Testing in Prod');
                    } catch (\Exception $e) {
                        $this->warn('Exception : ' . ' ' . $e->getMessage());
                    }
                } else {
                    $this->warn("❌ Did not find date attribute !.");
                }
            }
        }
    }

    /**
     * Send my Tweet !.
     *
     * @param string $status
     */
    public function SendTweet($status)
    {
        return Twitter::postTweet(
            [
                'status' => $status,
                'response_format' => 'json'
            ]
        );
    }
}
