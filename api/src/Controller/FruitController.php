<?php

namespace App\Controller;

use App\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\FruitRepository;
use App\Repository\FavoriteRepository;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Fruit;
use App\Entity\Favorite;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;

final class FruitController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    /**
     * Retrieve all fruits based on request parameter
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Repository\FruitRepository $fruits
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
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

        return $this->json($result);
    }

    /**
     * Creates an instance of Fruit
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Validator\Validator\ValidatorInterface $validator
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    #[Route('/fruit', name: 'fruit_add', methods: ['POST', 'PUT'])]
    public function fruitNew(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $fruit = (new Fruit())
            ->setCreatedAt(new \DateTime('now'))
            ->setUpdatedAt(new \DateTime('now'));

        $fruit = $this->serializedForm($request, $fruit);

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

        return $this->json($fruit->toArray());
    }

    /**
     * Get or patch a single Fruit instance based on Fruit ID provided
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Repository\FruitRepository $fruits
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    #[Route('/fruit/{id}', name: 'fruit', methods: ['GET', 'POST', 'PATCH', 'DELETE'])]
    public function fruit(Request $request, FruitRepository $fruits)
    {
        $id = (int) $request->attributes->get('id');
        $fruit = $fruits->find($id);

        // @INFO: Throw a 404 if a resource is not found
        if (!$fruit instanceof Fruit) {
            return $this->json([], Response::HTTP_NOT_FOUND);
        }

        // @INFO: Return a resource
        if ($request->getMethod() === Request::METHOD_GET) {
            // @INFO: Throw a 404 if a resource is not found
            if (!$fruit instanceof Fruit) {
                return $this->json([], Response::HTTP_NOT_FOUND);
            }

            return $this->json($fruit->toArray());
        }

        // @INFO: Update and return a resource
        if (
            in_array(
                $request->getMethod(),
                [Request::METHOD_POST, Request::METHOD_PATCH]
            )
        ) {
            $fruit = $this->serializedForm($request, $fruit);
            $fruit->setUpdatedAt(new \DateTime('now'));
            $this->em->flush();

            return $this->json($fruit->toArray());
        }

        // @INFO: Delete a resource
        if ($request->getMethod() === Request::METHOD_DELETE) {
            $this->em->remove($fruit);
            $this->em->flush();

            return $this->json($fruit->toArray());
        }
    }

    /**
     * Add fruit to favorites
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Repository\FruitRepository $fruits
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    #[Route('/favorite/{id}', name: 'favorite.add', methods: ['POST'])]
    public function addToFavorite(
        Request $request,
        FruitRepository $fruits,
        FavoriteRepository $favorites,
    ) {
        // @INFO: Check first if we've reached the maximum number of favorited fruits
        if ($favorites->count([]) >= Favorite::MAX_FAVORITE) {
            return $this->json([
                'errors' => [
                    'favorites' => ['Maximum favorites reached.']
                ]
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $id = (int) $request->attributes->get('id');
        $fruit = $fruits->find($id);

        // @INFO: Throw a 404 if a resource is not found
        // @INFO: Ensure that request method is POST
        if (
            !$fruit instanceof Fruit ||
            $request->getMethod() !== Request::METHOD_POST
        ) {
            return $this->json([], Response::HTTP_NOT_FOUND);
        }

        $favorite = $favorites->findOneBy(['fruit' => $id]);


        if (!$favorite instanceof Favorite) {
            $favorite = (new Favorite())
                ->setFruit($fruit)
                ->setDateAdded(new \DateTime('now'));

            $this->em->persist($favorite);
            $this->em->flush();
        }

        return $this->json([
            'fruit' => $favorite->getFruit()->toArray(),
            'date_added' => $favorite->getId(),
        ]);
    }

    /**
     * Remove fruit to favorites
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Repository\FruitRepository $fruits
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    #[Route('/favorite/{id}', name: 'favorite.delete', methods: ['DELETE'])]
    public function removeToFavorites(
        Request $request,
        FavoriteRepository $favorites,
    ) {
        $id = (int) $request->attributes->get('id');
        $favorite = $favorites->findOneBy(['fruit' => $id]);

        // @INFO: Return 404 if no fruit found or the method type is not DELETE
        if (
            !$favorite instanceof Favorite ||
            $request->getMethod() !== Request::METHOD_DELETE
        ) {
            return $this->json([], Response::HTTP_NOT_FOUND);
        }

        $this->em->remove($favorite);
        $this->em->flush();

        return $this->json($favorite->toArray(), Response::HTTP_OK);
    }


    /**
     * Hydrate the Fruit instance with request data
     *
     * @var \Symfony\Component\HttpFoundation\Request $request
     * @var \App\Entity $fruit
     *
     * @return \App\Entity\Fruit
     */
    private function serializedForm(Request $request, Fruit $fruit): Fruit
    {
        $data = json_decode($request->getContent(), true);

        return $fruit->setName($data['name'] ?? $fruit->getName() ?: '')
            ->setGenus($data['genus'] ?? $fruit->getGenus() ?: '')
            ->setFamily($data['family'] ?? $fruit->getFamily() ?: '')
            ->setFruitOrder($data['fruitOrder'] ?? $fruit->getFruitOrder() ?: '')
            ->setCarbohydrates($data['carbohydrates'] ?? $fruit->getCarbohydrates() ?: 0)
            ->setFat($data['fat'] ?? $fruit->getFat() ?: 0)
            ->setProtein($data['protein'] ?? $fruit->getProtein() ?: 0)
            ->setSugar($data['sugar'] ?? $fruit->getSugar() ?: 0)
            ->setCalories($data['calories'] ?? $fruit->getCalories() ?: 0)
            ->setSource(Fruit::SOURCE_FROM_APP);
    }
}
