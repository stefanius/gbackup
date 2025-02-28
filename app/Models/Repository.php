<?php

namespace App\Models;

use App\Exceptions\InvalidUrlException;
use Illuminate\Database\Eloquent\Model;

class Repository extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'url',
        'provider',
        'protocol',
        'username',
        'repo',
        'default_branch',
        'number_of_backups',
    ];

    /**
     * Create a repository from url.
     */
    public static function fromUrl(string $url, $defaultBranch = 'main'): self
    {
        $parts = explode('/', $url);

        if ($parts[0] === 'https:') {
            $data = [
                'url' => $url,
                'protocol' => 'https',
                'provider' => 'Github',
                'username' => $parts[3],
                'repo' => str_replace('.git', '', $parts[4]),
                'default_branch' => $defaultBranch,
                'number_of_backups' => 12,
            ];
        } elseif (str_contains($parts[0], 'git@github.com')) {
            $data = [
                'url' => $url,
                'protocol' => 'ssh',
                'provider' => 'Github',
                'username' => explode(':', $parts[0])[1],
                'repo' => str_replace('.git', '', $parts[1]),
                'default_branch' => $defaultBranch,
                'number_of_backups' => 12,
            ];
        } else {
            throw new InvalidUrlException;
        }

        return Repository::create($data);
    }

    public function defaultBranchDownloadUrl(): string
    {
        return "https://github.com/{$this->username}/{$this->repo}/zipball/{$this->default_branch}";
    }

    public function ssh()
    {
        return "git@github.com:{$this->username}/{$this->repo}.git";
    }

    public function https()
    {
        return "https://github.com/{$this->username}/{$this->repo}";
    }
}
