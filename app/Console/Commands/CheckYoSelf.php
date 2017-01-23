<?php

namespace App\Console\Commands;

use App\HotWalker\TimeGenerator;
use Illuminate\Console\Command;

class CheckYoSelf extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:yoself';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {


        $bombs_path    = app_path() . '/HotWalker/Data/bombs.json';
        $outcomes_path = app_path() . '/HotWalker/Data/outcomes.json';

        $outcome_json  = file_get_contents($outcomes_path);
        $outcome_array = json_decode($outcome_json, true);
        $punishments   = $outcome_array['punishments'];
        $rewards       = $outcome_array['rewards'];

        $numberOfPunishments = 2;
        $numberOfRewards     = 2;

        $random_punishments = $this->getRandomOutcomes($punishments, $numberOfPunishments);
        $random_rewards     = $this->getRandomOutcomes($rewards, $numberOfRewards);

        $existing_bomb_contents = json_decode(@file_get_contents($bombs_path), true);
        $bomb_contents = [];
        $max_bombs_reached_for_week = false;
        $last_bomb_time = null;
        $bomb_contents_count = count($existing_bomb_contents);

        if(!is_null($existing_bomb_contents)) {
            $bomb_contents = $existing_bomb_contents;
            $last_bomg_time_string = array_get($bomb_contents, ($bomb_contents_count - 1) . '.time');
            $last_bomb_time = new \DateTime($last_bomg_time_string);
        }

        if($bomb_contents_count >= 2) {
            $max_bombs_reached_for_week = true;
            $bomb_contents = [];
        }

        $current_time  = TimeGenerator::generate($last_bomb_time, $max_bombs_reached_for_week);

        $bomb_contents[] = [
            "time"        => $current_time->format('Y-m-d H:i:s'),
            "punishments" => $random_punishments,
            "rewards"     => $random_rewards,
            "blown"       => false,
        ];


        file_put_contents($bombs_path, json_encode($bomb_contents, JSON_PRETTY_PRINT));
    }

    /**
     * @param $outcomes
     * @param $numberOfOutcomes
     *
     * @return array
     */
    public function getRandomOutcomes($outcomes, $numberOfOutcomes)
    {
        $random_outcomes = [];
        $random_keys     = array_rand($outcomes, $numberOfOutcomes);

        if ($numberOfOutcomes == 1) {
            return [$outcomes[$random_keys]];
        }

        for ($i = 0; $i < $numberOfOutcomes; $i++) {
            $random_outcomes[] = $outcomes[$random_keys[$i]];
        }

        return $random_outcomes;
    }
}
