<?php

namespace Tagged\FoosballBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * PlayerRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PlayerRepository extends EntityRepository
{
    public function findAllOrderedByScore()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p FROM TaggedFoosballBundle:Player p ORDER BY p.score DESC'
            )
            ->getResult();
    }
}
