<?php

namespace App\Controller;

use App\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\FruitRepository;
use Symfony\Component\Routing\Annotation\Route;

final class IndexController extends AbstractController
{
    #[Route('/', name: 'fruits', methods: ['GET'])]
    public function all(Request $request, FruitRepository $fruits): JsonResponse
    {
        $page = (int) $request->get('page', 1);
        $size = (int) $request->get('size', Paginator::PAGE_SIZE) ?: Paginator::PAGE_SIZE;
        $orderBy = $request->get('order_by', 'name');
        $search = $request->get('search');
        $direction = $request->get('direction', 'ASC');

        /** @var \ArrayIterator $result **/
        $result = $fruits->all($page, $size, $orderBy, $direction, $search)->toArray();

        // @INFO: Remove irrelevant property when responding
        unset($result['queryBuilder']);

        return new JsonResponse($result);
    }

    #[Route('/fruit', name: 'fruit_add', methods: ['POST', 'PUT'])]
    public function fruitNew(Request $request)
    {
        // dd($request);
    }

    #[Route('/fruit/{id}', name: 'fruit', methods: ['GET', 'POST', 'PUT'])]
    public function fruit(Request $request)
    {
        // dd($request);
    }
}
