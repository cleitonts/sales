<?php

namespace Panthir\UI\Controller\Product;

use Panthir\Application\Common\Handler\HandlerRunner;
use Panthir\Application\UseCase\Product\CategorySearchHandler;
use Panthir\Application\UseCase\Product\Normalizer\DTO\ProductSearchDTO;
use Panthir\Infrastructure\CommonBundle\Exception\HandlerException;
use Panthir\UI\Controller\APIController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/FO/category')]
class FOCategoryGet extends APIController
{
    /**
     * @throws HandlerException
     */
    #[Route(path: '/', name: 'app_fo_category_get_all', methods: 'GET')]
    public function getAll(
        CategorySearchHandler $categorySearchHandler,
        HandlerRunner $runner,
    ): JsonResponse {
        $products = $runner($categorySearchHandler, new ProductSearchDTO());

        return $this->response(items: $products);
    }
}
