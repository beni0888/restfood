<?php

namespace Restfood\Entity;


interface ResourceRepositoryInterface
{
    /**
     * Persist an resource.
     *
     * @param ResourceInterface $resource
     * @return Resource
     */
    public function save(ResourceInterface $resource);

    /**
     * Remove the given resource
     *
     * @param ResourceInterface $resource
     * @return void
     */
    public function remove(ResourceInterface $resource);

    /**
     * Return the resource with the given uuid.
     *
     * @param string $identifier
     * @return ResourceInterface|null
     */
    public function findOneByIdentifier($identifier);

    /**
     * Return the resource with the given name.
     *
     * @param string $name
     * @return ResourceInterface|null
     */
    public function findOneByName($name);

    /**
     * Update the information of the given resource into the persistence layer.
     *
     * @param ResourceInterface $resource
     * @return void
     */
    public function update(ResourceInterface $resource);

    /**
     * Return a list of resources.
     *
     * @return mixed
     */
    public function findAll();
}