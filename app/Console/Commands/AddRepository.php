<?php

namespace App\Console\Commands;

use App\Models\Repository;
use Illuminate\Console\Command;

class AddRepository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'repository:add';

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
        $url = $this->ask('GitHub repository (HTTPS or SSH remote)');
        $defaultBranch = $this->ask('Default branch (like "main" or "master"');

        $repository = Repository::fromUrl($url, $defaultBranch);

        $this->info('Repository created');

        $this->table(['repository', 'default', 'ssh', 'https'], [["{$repository->username}/{$repository->repo}", $repository->default_branch, $repository->ssh(), $repository->https()]]);
    }
}
