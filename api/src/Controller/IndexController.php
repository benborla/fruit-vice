<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\FruitRepository;
use Symfony\Component\Routing\Annotation\Route;

final class IndexController extends AbstractController
{
    #[Route('/', name: 'fruits_get_all', methods: ['GET'])]
    public function all(Request $request, FruitRepository $fruits): JsonResponse
    {
        $page = (int) $request->get('page');
        $orderBy = $request->get('order_by', 'name');
        $search = $request->get('search');
        $direction = $request->get('direction', 'ASC');

        /** @var \ArrayIterator $result **/
        $result = $fruits->all($page, $orderBy, $direction, $search)
            ->getResults();

        return new JsonResponse($result->getArrayCopy());
    }
}
