<?php

namespace Restfood\RESTFul\Endpoint;

use Restfood\Json\JsonDecoder;
use Restfood\Manager\IngredientAllergenManagerInterface;
use Restfood\Validation\ResourceValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class IngredientAllergenEndpoint
{
    private $jsonDecoder;
    private $ingredientAllergenManager;
    private $ingredientValidator;

    /**
     * IngredientAllergenEndpoint constructor.
     * @param $jsonDecoder
     * @param $ingredientAllergenManager
     * @param $ingredientValidator
     */
    public function __construct(
        JsonDecoder $jsonDecoder,
        IngredientAllergenManagerInterface $ingredientAllergenManager,
        ResourceValidatorInterface $ingredientValidator
    ) {
        $this->jsonDecoder = $jsonDecoder;
        $this->ingredientAllergenManager = $ingredientAllergenManager;
        $this->ingredientValidator = $ingredientValidator;
    }

    /**
     * Return the API response for the set ingredient allergens request.
     *
     * @param string $ingredientId
     * @param string $allergensJsonData
     * @return JsonResponse
     */
    public function setIngredientAllergens($ingredientId, $allergensJsonData)
    {
        $allergenIds = $this->jsonDecoder->decode($allergensJsonData);
        $this->ingredientValidator->assertExists($ingredientId);
        $allergens = $this->ingredientAllergenManager->setIngredientAllergens($ingredientId, $allergenIds);
        $httpCode = 200;

        return new JsonResponse($allergens, $httpCode);
    }

    /**
     * Return the API response for the list ingredient allergens request.
     *
     * @param string $ingredientId
     * @return JsonResponse
     */
    public function listIngredientAllergens($ingredientId)
    {
        $this->ingredientValidator->assertExists($ingredientId);
        $allergens = $this->ingredientAllergenManager->findIngredientAllergens($ingredientId);
        $httpCode = 200;

        return new JsonResponse($allergens, $httpCode);
    }


}