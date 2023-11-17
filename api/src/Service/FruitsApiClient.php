<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Api\FruitApiEntity;

/**
 * This is an API HTTP Wrapper that handles the basic API call
 */
class FruitsApiClient
{
    public function __construct(
        private string $apiEndpoint,
        private HttpClientInterface $client,
        private FruitApiEntity $entity,
    ) {
    }

    /**
     * Calls the API endpoint and converts the response into an array
     *
     * @var ?int $id (Optional) Provide a fruit ID
     *
     * @return null|\App\Entity\Api\FruitApiEntity
     */
    public function get(?int $id = 0): null|FruitApiEntity
    {
        $endpoint = $id ?
            $this->apiEndpoint . "/$id" :
            $this->apiEndpoint . '/all';

        $response = $this->client->request('GET', $endpoint);

        // @INFO: Return blank if HTTP status code is not equal to 200
        if ($response->getStatusCode() !== Response::HTTP_OK) {
            return null;
        }

        // @INFO: Since if there's an ID provided, it will store into a single
        // dimensional array, we have to convert it to two-dimensional array
        // to get an accurate item count.
        if ($id) {
            return $this->entity
                ->setItems([$response->toArray()])
                ->setTotal(1);
        }

        return $this->entity
            ->setItems($data = $response->toArray())
            ->setTotal(count($data));
    }
}
