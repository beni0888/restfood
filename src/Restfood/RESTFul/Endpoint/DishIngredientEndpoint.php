<?php

namespace Restfood\RESTFul\Endpoint;

use Restfood\Json\JsonDecoder;
use Restfood\Manager\DishIngredientManagerInterface;
use Restfood\Validation\ResourceValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class DishIngredientEndpoint
{
    private $jsonDecoder;
    private $dishIngredientManager;
    private $dishValidator;

    /**
     * DishIngredientEndpoint constructor.
     * @param JsonDecoder $jsonDecoder
     * @param DishIngredientManagerInterface $dishIngredientManager
     * @param ResourceValidatorInterface $dishValidator
     */
    public function __construct(
        JsonDecoder $jsonDecoder,
        DishIngredientManagerInterface $dishIngredientManager,
        ResourceValidatorInterface $dishValidator
    ) {
        $this->jsonDecoder = $jsonDecoder;
        $this->dishIngredientManager = $dishIngredientManager;
        $this->dishValidator = $dishValidator;
    }

    /**
     * Return the API response for the set dish ingredients request.
     *
     * @param string $dishIdentifier
     * @param string $jsonData
     * @return JsonResponse
     */
    public function setDishIngredients($dishIdentifier, $jsonData)
    {
        $ingredientIds = $this->jsonDecoder->decode($jsonData);
        $this->dishValidator->assertExists($dishIdentifier);
        $ingredients = $this->dishIngredientManager->setDishIngredients($dishIdentifier, $ingredientIds);
        $httpCode = 200;

        return new JsonResponse($ingredients, $httpCode);
    }

    /**
     * Return the API response for the list dish ingredients request.
     *
     * @param string $dishIdentifier
     * @return JsonResponse
     */
    public function listDishIngredients($dishIdentifier)
    {
        $this->dishValidator->assertExists($dishIdentifier);
        $ingredients = $this->dishIngredientManager->findDishIngredients($dishIdentifier);
        $httpCode = 200;

        return new JsonResponse($ingredients, $httpCode);
    }
}