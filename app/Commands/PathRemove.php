<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class PathRemove extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'path:remove';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Remove sync-able path';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $label = $this->choice(
            'Quelle synchro supprimer ?',
            app('conf')->getLabels()->toArray(),
            null,
            null,
            false
        );

        if (app('conf')->remove($label)) {
            $this->info(sprintf('%s supprimé', $label));
        }

    }
}
