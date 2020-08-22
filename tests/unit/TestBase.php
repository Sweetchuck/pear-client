<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Tests\Unit;

use Codeception\Test\Unit;
use Sweetchuck\PearClient\Test\UnitTester;

class TestBase extends Unit
{
    protected UnitTester $tester;
}
