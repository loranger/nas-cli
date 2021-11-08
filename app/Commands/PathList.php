<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class PathList extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'path:list';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'List all sync-able paths';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $config = app('conf')->get();
        $rows = $config->map(function ($item, $label) {
            return [$label, $item->from, $item->to, implode(',', $item->ignore)];
        });

        $this->table(
            ['Nom', 'Source', 'Destination', 'Ignore'],
            $rows
        );
    }
}
