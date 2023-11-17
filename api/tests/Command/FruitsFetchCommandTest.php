<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FruitsFetchCommandTest extends KernelTestCase
{
    public function testFruitFetchExecution()
    {
        $this->assertIsString('fruits:fetch');
    }
}
