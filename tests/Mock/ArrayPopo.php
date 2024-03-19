<?php

declare(strict_types=1);

namespace Tests\Mock;

use SheryanEu\LaravelPopo\BasePopo;

class ArrayPopo extends BasePopo
{
    /**
     * @var string
     */
    public string $firstName;

    /**
     * @param string $firstName
     */
    public function __construct(string $firstName)
    {
        $this->firstName = $firstName;
    }
}
