<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Process\Process;
use TitasGailius\Terminal\Terminal;

class Sync extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'sync';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Sync source to destination';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $label = $this->choice(
            'Que faut-il synchroniser ?',
            app('conf')->getLabels()->toArray(),
            null,
            null,
            false
        );

        $sync = app('conf')->get($label);

        $command = Terminal::timeout(0)->command($this->getCommand($sync, true));

        $result = $this->task("Analyse des fichiers à synchroniser", function () use ($command, &$response) {
            $response = $command->run();
            return $response->successful();
        }, 'vérification...');

        $lines = $response->lines();
        $last_stdout = end($lines);

        if (!$result) {
            return $this->error($last_stdout);
        }

        $lines = collect(explode(PHP_EOL, $last_stdout));
        $lines->shift();
        $lines->pop(4);

        if (!$lines->count()) {
            return $this->info('Rien à synchroniser');
        }

        $this->newLine();
        $this->info('Fichier(s) à synchroniser :');
        $this->newLine();
        $lines->each(function ($line) {
            $this->line($line);
        });

        if ($this->confirm('Tout synchroniser ?')) {
            $bar = $this->output->createProgressBar($lines->count());
            $bar->setFormat(' [%bar%] %percent:3s%%');
            $bar->start();
            $command = Terminal::timeout(0)->command($this->getCommand($sync));
            $response = $command->run(function ($type, $buffer) use ($bar) {
                $bar->advance();
            });
            $bar->finish();
        }
    }

    private function getCommand($sync, $dry = false)
    {
        $excludes = collect($sync->ignore)->map(function ($item) {
            return sprintf('--exclude "%s"', $item);
        });

        return sprintf(
            'rsync -ruv %s --iconv=utf-8-mac,utf-8 %s %s %s',
            $dry ? '--dry-run' : '',
            $sync->from,
            $sync->to,
            $excludes->implode(' ')
        );
    }

}
