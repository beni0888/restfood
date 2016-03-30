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
            $this->resource = $this->findOneBy(array($this->getIdentifierFieldName() => $identifier));
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

    /**
     * Return the list of resources with the given identifiers.
     *
     * @param array $identifiers
     * @return ResourceInterface[]
     */
    public function findByIdentifierList(array $identifiers)
    {
        $identifierFieldName = $this->getIdentifierFieldName();
        $alias = $this->getEntityAlias();

        return $this->createQueryBuilder($alias)
            ->where("{$alias}.{$identifierFieldName} IN (:identifiers)")
            ->setParameter('identifiers', $identifiers)
            ->getQuery()
            ->execute();
    }

    /**
     * Return an alias for the managed entity.
     *
     * @return string
     */
    protected final function getEntityAlias()
    {
        $fragments = explode('\\', $this->_entityName);
        $offset = 0;
        $length = 1;
        $lastFragmentIndex = count($fragments) - 1;
        $className = $fragments[$lastFragmentIndex];
        return substr($className, $offset, $length);
    }

    /**
     * Return the name of the field used as identifier in the managed entity.
     *
     * @return string
     */
    protected final function getIdentifierFieldName()
    {
        $entityName = $this->_entityName;
        return $entityName::obtainIdentifierFieldName();
    }
}