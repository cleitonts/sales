<?php

namespace Panthir\UI\DataFixtures;

use Bezhanov\Faker\Provider\Commerce;
use Bezhanov\Faker\Provider\Device;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Panthir\Application\Common\Handler\HandlerRunner;
use Panthir\Application\UseCase\Product\BrandCreateHandler;
use Panthir\Application\UseCase\Product\Normalizer\DTO\ProductCreateDTO;
use Panthir\Application\UseCase\Product\ProductCreateHandler;
use Panthir\Domain\Product\Model\Category;

class ProductFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct(
        private readonly HandlerRunner $handlerRunner,
        private readonly ProductCreateHandler $productCreateHandler,
        private readonly BrandCreateHandler $brandCreateHandler,
    ) {
    }

    public static function getGroups(): array
    {
        return ['products'];
    }

    public function load(ObjectManager $manager): void
    {
        $categories = $manager->getRepository(Category::class)->findBy(['isLastLevel' => true]);

        $brands = [];
        for ($b = 0; $b < 20; ++$b) {
            $brandData = Factory::create();
            $brandData->addProvider(new Device($brandData));
            $brands[] = $this->handlerRunner->__invoke($this->brandCreateHandler, ['name' => $brandData->deviceManufacturer()], false);
        }

        $i = 0;
        foreach ($categories as $c) {
            if ($i % 2) {
                continue;
            }

            for ($p = 0; $p < 20; ++$p) {
                $prodData = Factory::create();
                $prodData->addProvider(new Commerce($prodData));
                $prodData->addProvider(new Device($prodData));

                $productDTO = (new ProductCreateDTO(
                    name: $prodData->productName(),
                    categoryId: $c->getId(),
                    value: $prodData->randomFloat(2, 1, 1000),
                    brand: $brands[$p])
                );

                $this->handlerRunner->__invoke($this->productCreateHandler, $productDTO);
            }
        }
    }
}
