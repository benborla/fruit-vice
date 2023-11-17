<?php

namespace App\Entity\Api;

/**
 * A class used to create an Object Model from
 * a serialized response data
 */
class FruitApiEntity
{
    protected array $items = [];
    protected int $total = 0;

    /**
     * @var array $items
     *
     * @return self
     */
    public function setItems(array $items): self
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @var int $total
     *
     * @return self
     */
    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * A helper function to return the entire properties as an array
     *
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }

    /**
     * Returns the first item in the array
     *
     * @return array
     */
    public function first(): array
    {
        return current($this->toArray()['items']);
    }

    /**
     * Returns the last item in the array
     *
     * @return array
     */
    public function last(): array
    {
        return end($this->toArray()['items']);
    }
}
