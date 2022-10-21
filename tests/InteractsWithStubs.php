<?php

namespace Tests;

trait InteractsWithStubs
{
    public function getStub(string $stub): array
    {
        return json_decode(file_get_contents(base_path("tests/stubs/{$stub}.json")), true);
    }
}
