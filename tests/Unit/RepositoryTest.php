<?php

namespace Tests\Unit;

use App\Exceptions\InvalidUrlException;
use App\Models\Repository;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RepositoryTest extends TestCase
{
    #[Test]
    public function it_can_create_a_repository_from_url_from_https_protocol(): void
    {
        // Given
        $url = 'https://github.com/stefanius/gbackup.git';

        // When
        $repository = Repository::fromUrl($url);

        // Then
        $this->assertInstanceOf(Repository::class, $repository);
        $this->assertEquals($url, $repository->url);
        $this->assertEquals('https', $repository->protocol);
        $this->assertEquals('Github', $repository->provider);
        $this->assertEquals('stefanius', $repository->username);
        $this->assertEquals('gbackup', $repository->repo);

        $this->assertDatabaseHas('repositories', [
            'id' => $repository->id,
            'url' => $url,
            'protocol' => 'https',
            'provider' => 'Github',
            'username' => 'stefanius',
            'repo' => 'gbackup',
        ]);
    }

    #[Test]
    public function it_can_create_a_repository_from_url_from_ssh_protocol(): void
    {
        // Given
        $url = 'git@github.com:stefanius/gbackup.git';

        // When
        $repository = Repository::fromUrl($url);

        // Then
        $this->assertInstanceOf(Repository::class, $repository);
        $this->assertEquals($url, $repository->url);
        $this->assertEquals('ssh', $repository->protocol);
        $this->assertEquals('Github', $repository->provider);
        $this->assertEquals('stefanius', $repository->username);
        $this->assertEquals('gbackup', $repository->repo);

        $this->assertDatabaseHas('repositories', [
            'id' => $repository->id,
            'url' => $url,
            'protocol' => 'ssh',
            'provider' => 'Github',
            'username' => 'stefanius',
            'repo' => 'gbackup',
        ]);
    }

    #[Test]
    public function it_cannpt_create_a_repository_from_url_when_the_url_is_unsupported(): void
    {
        $this->expectException(InvalidUrlException::class);
        // Given
        $url = 'invalid';

        // When
        Repository::fromUrl($url);
    }
}
