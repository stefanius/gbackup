<?php

namespace App\Services;

use App\Models\Repository;
use App\Resources\Github\Transformer;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Process\Process;

class GithubService
{
    protected function query(Repository $repository, $action)
    {
        $cli = [
            'gh api',
            '-H "Accept: application/vnd.github+json"',
            '-H "X-GitHub-Api-Version: 2022-11-28"',
            "/repos/{$repository->username}/{$repository->repo}/{$action}",
        ];

        return implode(' ', $cli);
    }

    protected function request(Repository $repository, $action)
    {
        $query = $this->query($repository, $action);

        $process = Process::fromShellCommandline($query);
        $process->run();

        return json_decode($process->getOutput());
    }

    public function getTags(Repository $repository)
    {
        $response = $this->request($repository, 'tags');

        return Transformer::transformTags($response);
    }

    /**
     * @return void
     */
    public function download(string $target, string $source)
    {
        Http::when(File::missing($target), fn () => Http::sink($target)->get($source));
    }
}
