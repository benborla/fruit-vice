<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Fruit;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // create 20 products! Bam!
        for ($i = 1; $i <= 20; $i++) {
            $fruit = new Fruit();
            $fruit->setName("Fruit $i")
                ->setGenus($this->getRandomGenus())
                ->setFamily($this->getRandomFamily())
                ->setFruitOrder($this->getFruitOrder())
                ->setCarbohydrates(mt_rand(10.0, 100.99))
                ->setProtein(mt_rand(10.0, 100.99))
                ->setFat(mt_rand(10.0, 100.99))
                ->setSugar(mt_rand(10.0, 100.99))
                ->setCalories(mt_rand(10.0, 100.99))
                ->setSource(Fruit::SOURCE_FROM_APP);

            $manager->persist($fruit);
        }

        $manager->flush();
    }

    /**
     * Returns a random Genus data
     *
     * @return string
     */
    private function getRandomGenus(): string
    {
        $genus = [
            'Diospyros',
            'Fragaria',
            'Musa',
            'Solanum',
            'Pyrus',
            'Durio',
            'Rubus',
            'Vaccinium',
            'Apteryx',
            'Litchi',
            'Ananas',
            'Ficus',
            'Passiflora'
        ];

        return $this->randomizer($genus);
    }

    /**
     * Returns a random Family data
     *
     * @return string
     */
    private function getRandomFamily(): string
    {
        $family = [
            'Ebenaceae',
            'Rosaceae',
            'Musaceae',
            'Solanaceae',
            'Rosaceae',
            'Malvaceae',
            'Rosaceae',
            'Ericaceae',
            'Sapindaceae',
            'Bromeliaceae',
        ];

        return $this->randomizer($family);
    }

    /**
     * Returns a random Fruit order data
     *
     * @return string
     */
    private function getFruitOrder(): string
    {
        $fruitOrder = [
            'Rosales',
            'Zingiberales',
            'Solanales',
            'Malvales',
            'Ericales',
            'Struthioniformes',
            'Sapindales',
        ];

        return $this->randomizer($fruitOrder);
    }

    /**
     * Reusable function to get a random value from an array
     *
     * @param array $reference The array it refers to 
     * @return string
     */
    private function randomizer(array $reference): string
    {
        return $reference[mt_rand(0, count($reference) - 1)] ?? '';
    }
}
