<?php

namespace App\Console\Commands;

use App\Actions\DownloadDefaultBranch;
use App\Models\Repository;
use Illuminate\Console\Command;

class BackupDefaultBranch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:default-branch';

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
        $repositories = Repository::all();

        $action = app(DownloadDefaultBranch::class);

        $repositories->each(fn (Repository $repository) => $action->handle($repository));
    }
}
