<?php

namespace App\Actions;

use App\Models\Repository;
use App\Resources\Github\Tag;
use App\Services\GithubService;
use Illuminate\Support\Facades\File;

class DownloadTags
{
    /**
     * @param \App\Services\GithubService $service
     */
    public function __construct(
        protected GithubService $service
    ) {}

    /**
     * @param \App\Models\Repository $repository
     *
     * @return void
     */
    public function handle(Repository $repository)
    {
        $tags = $this->service->getTags($repository);

        foreach ($tags as $tag) {
            $this->download($repository, $tag);
        }
    }

    /**
     * @param \App\Models\Repository $repository
     *
     * @return string
     */
    protected function path(Repository $repository): string
    {
        return config('gbackup.backup_path') . "{$repository->username}/{$repository->repo}/tags";
    }

    /**
     * @param \App\Models\Repository $repository
     * @param App\Resources\Github\Tag $tag
     *
     * @return void
     */
    protected function download(Repository $repository, Tag $tag)
    {
        $path = $this->path($repository);

        if (File::missing($path) ) {
            File::makeDirectory(path: $path, recursive: true, force: true);
        }

        $this->service->download("{$path}/{$tag->name}.zip", $tag->zipballUrl);
        $this->service->download("{$path}/{$tag->name}.tar", $tag->tarballUrl);
    }
}
