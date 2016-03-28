<?php

namespace Restfood\Entity;

use Doctrine\ORM\EntityRepository;

class DoctrineResourceRepository extends EntityRepository implements ResourceRepositoryInterface
{
    /** @var ResourceInterface */
    private $resource;

    /**
     * Persist an resource.
     *
     * @param ResourceInterface $resource
     * @return Resource
     */
    public function save(ResourceInterface $resource)
    {
        $this->_em->persist($resource);
        $this->_em->flush($resource);
        return $resource;
    }

    /**
     * Remove the given resource
     *
     * @param ResourceInterface $resource
     * @return void
     */
    public function remove(ResourceInterface $resource)
    {
        $this->_em->remove($resource);
        $this->_em->flush($resource);
    }

    /**
     * Return the resource with the given uuid.
     *
     * @param string $identifier
     * @return ResourceInterface|null
     */
    public function findOneByIdentifier($identifier)
    {
        if (is_null($this->resource) || $this->resource->obtainIdentifier() !== $identifier) {
            $entityName = $this->_entityName;
            $identifierFieldName = $entityName::obtainIdentifierFieldName();
            $this->resource = $this->findOneBy(array($identifierFieldName => $identifier));
        }
        return $this->resource;
    }

    /**
     * Return the resource with the given name.
     *
     * @param string $name
     * @return ResourceInterface|null
     */
    public function findOneByName($name)
    {
        return $this->findOneBy(['name' => $name]);
    }

    /**
     * Update the information of the given resource into the persistence layer.
     *
     * @param ResourceInterface $resource
     * @return void
     */
    public function update(ResourceInterface $resource)
    {
        $this->_em->flush($resource);
    }

    /**
     * Return a list of resources.
     *
     * @return mixed
     */
    public function findAll()
    {
        return parent::findAll();
    }
}