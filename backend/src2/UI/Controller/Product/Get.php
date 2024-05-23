<?php

namespace Panthir\UI\Controller\Product;

use Panthir\Application\Common\Handler\HandlerRunner;
use Panthir\Application\UseCase\Product\Normalizer\DTO\ProductSearchDTO;
use Panthir\Application\UseCase\Product\ProductGetBrandsHandler;
use Panthir\Application\UseCase\Product\ProductSearchHandler;
use Panthir\UI\Controller\APIController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

#[Route(path: '/api/product')]
class Get extends APIController
{
    #[Route(path: '/', name: 'app_product_get_all', methods: 'GET')]
    public function getAll(
        Request $request,
        ProductSearchHandler $productSearchHandler,
        HandlerRunner $runner,
    ): JsonResponse {
        $serializer = new Serializer(normalizers: [new ObjectNormalizer()]);

        /** @var ProductSearchDTO $user */
        $searchDTO = $serializer->denormalize(
            data: $request->query->all(),
            type: ProductSearchDTO::class
        );

        $products = $runner($productSearchHandler, $searchDTO);

        return $this->response(items: $products);
    }

    #[Route(path: '/{id}', name: 'app_product_get', methods: 'GET')]
    public function get(
        ProductSearchHandler $productSearchHandler,
        HandlerRunner $runner,
        string $id
    ): JsonResponse {
        $products = $runner($productSearchHandler, new ProductSearchDTO(id: $id));

        return $this->response(items: $products);
    }

    #[Route(path: '/brands', name: 'app_product_brand_get_all', methods: 'GET')]
    public function getBrands(
        ProductGetBrandsHandler $productGetBrandsHandler,
        HandlerRunner $runner,
    ): JsonResponse {
        $products = $runner($productGetBrandsHandler, new ProductSearchDTO());

        return $this->response(items: $products);
    }
}
