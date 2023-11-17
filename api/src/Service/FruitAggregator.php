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
        private EntityManagerInterface $em
    ) {
    }

    public function save()
    {
        $fruitsFromApi = $this->api->get();

        // @INFO: Throw an exception if the data is invalid
        if (! $fruitsFromApi instanceof FruitApiEntity) {
            throw new \Exception('Invalid response from the API endpoint');
        }

        // @INFO: Do nothing if nothing is returned from the API call
        if (0 === $fruitsFromApi->getItems()) {
            return;
        }

        // @INFO: Check if the fetched data from API already exist in the database table
    }
}
