<?php
namespace Application\Service;

use Doctrine\ORM\EntityManager;

class CategoriaService {

    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function save($entity)
    {
        if(!is_object($entity))
            throw new \InvalidArgumentException('A entidade não é um objeto.');

        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }

    public function update($entity)
    {
        if(!is_object($entity))
            throw new \InvalidArgumentException('A entidade não é um objeto.');

        $this->em->merge($entity);
        $this->em->flush();

        return $entity;
    }

    public function remove($entity)
    {
        if(!is_object($entity))
            throw new \InvalidArgumentException('A entidade não é um objeto.');

        if ($entity) {
            $this->em->remove($entity);
            $this->em->flush();

            return $entity;
        }
    }

}