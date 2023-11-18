<?php

namespace App\Controller;

use App\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\FruitRepository;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Fruit;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;

final class IndexController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

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
    public function fruitNew(Request $request, ValidatorInterface $validator)
    {
        $data = json_decode($request->getContent(), true);

        $fruit = new Fruit();
        $fruit->setName($data['name'] ?? '')
            ->setGenus($data['genus'] ?? '')
            ->setFamily($data['family'] ?? '')
            ->setFruitOrder($data['fruit_order'] ?? '')
            ->setCarbohydrates($data['carbohydrates'] ?? 0)
            ->setFat($data['fat'] ?? 0)
            ->setProtein($data['protein'] ?? 0)
            ->setSugar($data['sugar'] ?? 0)
            ->setCalories($data['calories'] ?? 0)
            ->setCreatedAt(new \DateTime('now'))
            ->setUpdatedAt(new \DateTime('now'))
            ->setSource(Fruit::SOURCE_FROM_APP);

        $violations = $validator->validate($fruit);

        // @INFO: Return an error upon validator errors
        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()][] = $violation->getMessage();
            }

            return $this->json(['errors' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $this->em->persist($fruit);
        $this->em->flush();

        return new JsonResponse($fruit->toArray());
    }

    #[Route('/fruit/{id}', name: 'fruit', methods: ['GET', 'POST', 'PUT'])]
    public function fruit(Request $request)
    {
        // dd($request);
    }
}
