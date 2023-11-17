<?php

namespace App\Service;

use App\Service\FruitsApiClient;
use App\Entity\Api\FruitApiEntity;
use App\Repository\FruitRepository;
use App\Entity\Fruit;
use Doctrine\ORM\EntityManagerInterface;

class FruitAggregator
{
    public function __construct(
        private FruitsApiClient $api,
        private EntityManagerInterface $em,
        private FruitRepository $fruitRepository,
    ) {
    }

    /**
     * This will fetch data from the API endpoint and will do an upsert in the
     * database `fruits` table
     *
     * @return self
     */
    public function sync(): self
    {
        $fruitsFromApi = $this->api->get();

        // @INFO: Throw an exception if the data is invalid
        if (!$fruitsFromApi instanceof FruitApiEntity) {
            throw new \Exception('Invalid response from the API endpoint');
        }

        // @INFO: Do nothing if nothing is returned from the API call
        if (0 === $fruitsFromApi->getItems()) {
            return $this;
        }

        // @INFO: Check if the fetched data from API already exist in the database table
        foreach ($fruitsFromApi->getItems() as $item) {
            $isNew = false;
            // @INFO: Check if data already exist
            $fruit = $this->fruitRepository->findByField('name', $item['name']);

            // @INFO: Create new instance for new data if `fruit` is not existing
            if (!$fruit) {
                $fruit = new Fruit();
                $isNew = true;
            }

            $fruit->setName($item['name'] ?? '')
                ->setFamily($item['family'] ?? '')
                ->setFruitOrder($item['order'] ?? '')
                ->setGenus($item['genus'] ?? '')
                ->setCalories($item['nutritions']['calories'] ?? 0.0)
                ->setFat($item['nutritions']['fat'] ?? 0.0)
                ->setSugar($item['nutritions']['sugar'] ?? 0.0)
                ->setCarbohydrates($item['nutritions']['carbohydrates'] ?? 0.0)
                ->setProtein($item['nutritions']['protein'] ?? 0.0)
                ->setSource(Fruit::SOURCE_FETCHED_API);

            // @INFO: Persist non-existent data
            if ($isNew) {
                $this->em->persist($fruit);
            }

            $this->em->flush();
        }

        return $this;
    }
}
