<?php

namespace Restfood\RESTFul\Endpoint;

use Restfood\Manager\DishAllergenManager;
use Restfood\Validation\ResourceValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class DishAllergenEndpoint
{
    private $dishAllergenManager;
    private $allergenValidator;
    private $dishValidator;

    /**
     * DishAllergenEndpoint constructor.
     * @param DishAllergenManager $dishAllergenManager
     * @param ResourceValidatorInterface $allergenValidator
     * @param ResourceValidatorInterface $dishValidator
     */
    public function __construct(
        DishAllergenManager $dishAllergenManager,
        ResourceValidatorInterface $allergenValidator,
        ResourceValidatorInterface $dishValidator
    ) {
        $this->dishAllergenManager = $dishAllergenManager;
        $this->allergenValidator = $allergenValidator;
        $this->dishValidator = $dishValidator;
    }

    /**
     * Return the API response for the request of dishes with the given allergen.
     *
     * @param string $allergenIdentifier
     * @return JsonResponse
     */
    public function listDishesWithAllergen($allergenIdentifier)
    {
        $this->allergenValidator->assertExists($allergenIdentifier);
        $dishes = $this->dishAllergenManager->getDishesWithAllergen($allergenIdentifier);
        $httpCode = 200;

        return new JsonResponse($dishes, $httpCode);
    }

    /**
     * Return the API response for the request of allergens in the given dish.
     *
     * @param string $dishIdentifier
     * @return JsonResponse
     */
    public function listAllergensInDish($dishIdentifier)
    {
        $this->dishValidator->assertExists($dishIdentifier);
        $allergens = $this->dishAllergenManager->getAllergensInDish($dishIdentifier);
        $httpCode = 200;

        return new JsonResponse($allergens, $httpCode);
    }
}