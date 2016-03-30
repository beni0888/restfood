<?php

namespace Restfood\RESTFul\Endpoint;

class DishEndpoint extends AbstractResourceEndpoint
{
    protected function getShowResourceUrl($identifier)
    {
        $this->urlGenerator->showDish($identifier);
    }
}