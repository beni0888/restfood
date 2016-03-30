<?php

namespace Restfood\RESTFul\Endpoint;

class IngredientEndpoint extends AbstractResourceEndpoint
{
    protected function getShowResourceUrl($identifier)
    {
        return $this->urlGenerator->showIngredient($identifier);
    }
}