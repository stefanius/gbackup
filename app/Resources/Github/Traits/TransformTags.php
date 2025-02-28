<?php

namespace App\Resources\Github\Traits;

use App\Resources\Github\Tag;
use App\Resources\Github\Transformer;
use Illuminate\Support\Arr;

trait TransformTags {
    public static function transformTags(array $rows)
    {
        $tags = [];

        foreach ($rows as $row) {
            array_push($tags, Transformer::transformTag($row));
        }

        return $tags;
    }

    public static function transformTag(object $data)
    {
        return new Tag($data);
    }
}
