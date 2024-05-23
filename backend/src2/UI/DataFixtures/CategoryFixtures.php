<?php

namespace Panthir\UI\DataFixtures;

use Bezhanov\Faker\Provider\Commerce;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Panthir\Application\Common\Handler\HandlerRunner;
use Panthir\Application\UseCase\Product\CategoryCreateHandler;
use Panthir\Application\UseCase\Product\Normalizer\DTO\CategoryCreateDTO;

class CategoryFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct(
        private readonly HandlerRunner $handlerRunner,
        private readonly CategoryCreateHandler $categoryCreateHandler
    ) {
    }

    public static function getGroups(): array
    {
        return ['products'];
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 2; ++$i) {
            $rootData = Factory::create();
            $rootData->addProvider(new Commerce($rootData));

            $rootCategoryDTO = (new CategoryCreateDTO(
                name: $rootData->department(4),
                isLastLevel: false
            ));

            $data = $this->handlerRunner->__invoke($this->categoryCreateHandler, $rootCategoryDTO);

            for ($ii = 0; $ii < 5; ++$ii) {
                $subData = Factory::create();
                $subData->addProvider(new Commerce($subData));

                $subCategoryDTO = (new CategoryCreateDTO(
                    name: $subData->department(1),
                    isLastLevel: false,
                    parentId: $data['id']
                ));

                $data2 = $this->handlerRunner->__invoke($this->categoryCreateHandler, $subCategoryDTO);

                // some empty categories
                for ($iii = 0; $iii < 10; ++$iii) {
                    $subData2 = Factory::create();
                    $subData2->addProvider(new Commerce($subData));

                    $subCategoryDTO2 = (new CategoryCreateDTO(
                        name: $subData2->category(),
                        isLastLevel: true,
                        parentId: $data2['id']
                    ));
                    $this->handlerRunner->__invoke($this->categoryCreateHandler, $subCategoryDTO2);
                }

                for ($iii = 0; $iii < 10; ++$iii) {
                    $subData2 = Factory::create();
                    $subData2->addProvider(new Commerce($subData));

                    $subCategoryDTO2 = (new CategoryCreateDTO(
                        name: $subData2->category(),
                        isLastLevel: true,
                        parentId: $data2['id']
                    ));
                    $this->handlerRunner->__invoke($this->categoryCreateHandler, $subCategoryDTO2);
                }
            }
        }
    }
}
