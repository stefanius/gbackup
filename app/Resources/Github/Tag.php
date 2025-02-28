<?php

namespace App\Resources\Github;

class Tag
{
    public readonly string $name;

    public readonly string $zipballUrl;

    public readonly string $tarballUrl;

    public readonly object $data;

    public function __construct(object $data)
    {
        $this->name = $data->name;
        $this->zipballUrl = $data->zipball_url;
        $this->tarballUrl = $data->tarball_url;
        $this->data = $data;
    }
}
