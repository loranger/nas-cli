<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class PathEdit extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'path:edit';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Edit sync-able path';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $label = $this->choice(
            'Quelle synchro modifier ?',
            app('conf')->getLabels()->toArray(),
            null,
            null,
            false
        );

        $sync = app('conf')->get($label);

        $source = $this->ask('Quel est le chemin à synchroniser ?', $sync->from);
        $destination = $this->ask('Vers quelle destination ?', $sync->to);
        $ignore = $this->anticipate('Que faut-il ignorer ?', $sync->ignore, implode(', ', $sync->ignore));
        $label = $this->ask('Quel nom donner à cette synchronisation ?', $label);

        if (app('conf')->add($label, $source, $destination, explode(',', $ignore) )) {
            $this->info(sprintf('%s modifié', $label));
        }
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
