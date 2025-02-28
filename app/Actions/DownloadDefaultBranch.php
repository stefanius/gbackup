<?php

namespace App\Actions;

use App\Models\Repository;
use App\Services\GithubService;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use SplFileInfo;

class DownloadDefaultBranch
{
    public function __construct(
        protected GithubService $service
    ) {}

    /**
     * @return void
     */
    public function handle(Repository $repository)
    {
        $this->download($repository);
        $this->cleanup($repository);
    }

    protected function path(Repository $repository): string
    {
        return config('gbackup.backup_path')."{$repository->username}/{$repository->repo}/{$repository->default_branch}";
    }

    /**
     * @param  App\Resources\Github\Tag  $tag
     * @return void
     */
    protected function download(Repository $repository)
    {
        $path = $this->path($repository);

        if (File::missing($path)) {
            File::makeDirectory(path: $path, recursive: true, force: true);
        }

        $datetime = Str::replace([':', ' '], ['-', '_'], Carbon::now()->toDateTimeString());
        $this->service->download("{$path}/{$repository->default_branch}-{$datetime}.zip", $repository->defaultBranchDownloadUrl());
    }

    protected function cleanup(Repository $repository)
    {
        collect(File::allFiles($this->path($repository)))->filter(function (SplFileInfo $file) use ($repository) {
            return Str::startsWith($file->getFilename(), $repository->default_branch);
        })->sortByDesc(fn (SplFileInfo $file) => $file->getFilename())
            ->slice($repository->number_of_backups)
            ->each(fn (SplFileInfo $file) => File::delete($file->getPathname()));
    }
}
