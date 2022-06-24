<?php

declare(strict_types=1);

namespace PhpStan\Reproducer\Issue6650\One;

class Foo
{
    public function a(): void
    {
        $foo = new class () {
            public function b()
            {
            }
        };
    }
}
