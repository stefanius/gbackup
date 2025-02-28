<?php

namespace App\Console\Commands;

use App\Actions\DownloadDefaultBranch;
use App\Actions\DownloadTags;
use App\Models\Repository;
use App\Resources\Github\Tag;
use App\Services\GithubService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

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

        $repositories->each(fn(Repository $repository) => $action->handle($repository));
    }
}
