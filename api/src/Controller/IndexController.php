<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FruitAggregator;
use App\Service\FruitDecomulator;

final class IndexController extends AbstractController
{
    #[Route('/sync', name: 'fruits_get_all', methods: ['GET'])]
    public function all(FruitAggregator $fruit)
    {
        dd($fruit->sync());
    }

    #[Route('/clear', name: 'fruits_clear_all', methods: ['GET'])]
    public function clear(FruitDecomulator $fruit)
    {
        dd($fruit->__invoke());
    }
}
