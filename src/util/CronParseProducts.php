<?php

namespace Bonweb\Laraffiliate\Commands;

use Illuminate\Console\Command;

class CronParseProducts extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cron:parse-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Runs the products parser";

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {

        $previous = \ImportParserStats::orderBy('id', 'desc')->first();
        $stats = new \ImportParserStats();

        if (!$previous) {
            //  If no stats found get a feed to process
            $feeds = \Feed::enabled()->get();
        }
        else {
            //  if previous stats found, find next feed to process
            $feeds = \Feed::enabled()->where('id', '>', $previous->feed_id)->get();
            if ($feeds->count() == 0) {
                $feeds = \Feed::enabled()->get();
            }
        }

        foreach ($feeds as $feed) {
            if ($feed->merchant->status == 'A') {
                $stats->merchant_id = $feed->merchant->id;
                $stats->feed_id = $feed->id;
                $stats->start_time = time();
                break;
            }
        }

        if ($stats->merchant_id && $stats->feed_id) {
            $stats->save();
        }

        global $command;
        $command = $this;
        \App::make('LaraffiliateFeedController')->parseFeed($stats->feed_id, true);

        $stats->end_time = time();
        $stats->save();
    }

}