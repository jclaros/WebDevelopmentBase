<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

/**
 * MovieRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends EntityRepository {

  public function findAllPaginated($limit, $page, array $sorting = array(), $q = false) {
    $fields = array_keys($this->getClassMetadata()->fieldMappings);
    $queryBuilder = $this->createQueryBuilder('p');
    
    if($q){
      $queryBuilder->andWhere("p.title LIKE :parameter");
      $queryBuilder->setParameter("parameter", "%".$q."%");
    }
    
    
    foreach ($fields as $field) {
      if (isset($sorting[$field])) {
        $direction = ($sorting[$field] === 'asc') ? 'asc' : 'desc';
        $queryBuilder->addOrderBy('p.' . $field, $direction);
      }
    }

    $pagerAdapter = new DoctrineORMAdapter($queryBuilder);
    $pager = new Pagerfanta($pagerAdapter);
    $pager->setCurrentPage($page);
    $pager->setMaxPerPage($limit);

    return $pager;
  }

}
