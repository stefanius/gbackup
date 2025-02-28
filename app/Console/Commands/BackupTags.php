<?php

namespace App\Console\Commands;

use App\Actions\DownloadTags;
use App\Models\Repository;
use Illuminate\Console\Command;

class BackupTags extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:tags';

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

        $action = app(DownloadTags::class);

        $repositories->each(fn (Repository $repository) => $action->handle($repository));
    }
}
