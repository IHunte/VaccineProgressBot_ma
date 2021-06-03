<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Data;
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
                    $formated_date = Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');

                    if (Data::where('id', 1)->exists() && Data::where("id", "=", 1)->where('value', '!=', $date)->first()) {

                        Data::where('id', 1)->update(['value' => $date]);

                        if ($daily_vaccinations && !$total_vaccinations && !$people_vaccinated && !$people_fully_vaccinated) {
                            $this->SendTweet(constants('EMOTE.SYRINGE') . ' ' . $daily_vaccinations . ' people were vaccinated on ' . $formated_date);

                            $this->warn("Tweet sent !.");
                        } elseif ($daily_vaccinations && $total_vaccinations && $people_vaccinated && $people_fully_vaccinated) {
                            $fv_percentage = $people_fully_vaccinated * 100 / constants('TARGET_POPULATION');

                            $this->SendTweet(constants('EMOTE.SYRINGE') . " update of $formated_date :" . "\r\n" . "- $daily_vaccinations people were vaccinated. " . "\r\n" . "- Total number of vaccinations $total_vaccinations. " . "\r\n" . "- " . constants('EMOTE.SYRINGE') . "$people_vaccinated people." . "\r\n" . "- " . constants('EMOTE.SYRINGE') . constants('EMOTE.SYRINGE')  . "$people_fully_vaccinated people." . "\r\n" . "\r\n" . "- Progression target population fully vaccinated :" . "\r\n" . ProgressBar($fv_percentage));
                        } else {
                            $this->warn("❌ Did not find any attributes !.");
                        }
                    } else {
                        $this->warn("🚫 Didn't find any new update !.");
                    }
                } else {
                    $this->warn("❌ Did not find {date} attribute !.");
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
