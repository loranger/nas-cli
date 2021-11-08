<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class PathAdd extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'path:add';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Add a new sync-able path';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $source = $this->ask('Quel est le chemin à synchroniser ?', getcwd());
        $destination = $this->ask('Vers quelle destination ?', 'user@server:/sub/path/');
        $ignore = $this->anticipate('Que faut-il ignorer ?', ['.DS_Store,*.db'], '.DS_Store,*.db');
        $label = $this->ask('Quel nom donner à cette synchronisation ?', ucfirst(basename(getcwd())));

        if (app('conf')->add($label, $source, $destination, explode(',', $ignore) )) {
            $this->info(sprintf('%s ajouté', $label));
        }
    }

}
