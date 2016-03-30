<?php

namespace Restfood\RESTFul\Endpoint;

class AllergenEndpoint extends AbstractResourceEndpoint
{
    protected function getShowResourceUrl($identifier)
    {
        return $this->urlGenerator->showAllergen($identifier);
    }
}