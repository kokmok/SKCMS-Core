<?php

namespace SKCMS\CoreBundle\Repository;
use SKCMS\CoreBundle\Repository\TranslatableRepository;

class SKEntityRepository extends TranslatableRepository
{
    protected $allowDraft;
    
    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->allowDraft = false;
    }
    
    public function allowDraftEntities()
    {
        $this->allowDraft = true;
    }
    
    protected function getTranslatedQuery(\Doctrine\ORM\QueryBuilder $qb, $locale = null)
    {
        $qb = $this->draftizeQueryBuilder($qb);
        return parent::getTranslatedQuery($qb, $locale);
    }
    
    
    public function draftizeQueryBuilder(\Doctrine\ORM\QueryBuilder $qb)
    {
        
        $parts = $qb->getDQLParts();
        $froms = $parts['from'];
        foreach ($froms as $fromExpr)
        {
            $entityClass = $fromExpr->getFrom();
            $entityAlias = $fromExpr->getAlias();
            
            if (method_exists('\\'.$entityClass, 'getDraft') && !$this->allowDraft)
            {
                $qb->andWhere($entityAlias.'.draft=:draftAllowed')
                    ->setParameter('draftAllowed', false);
            }
            
        }
                
        return $qb;
    }
    
    

    public function findForAdmin($id,$locale = null)
    {
        $this->allowDraftEntities();
        return $this->find($id,$locale);
    }
    public function findFull($id,$locale = null)
    {
        return $this->find($id,$locale);
    }

    public function count(){
        $qb = $this->createQueryBuilder('p');
        $qb->select('COUNT (p.id)');

        return $this->getScalarResult($qb);

    }
    public function find($id,$locale = null)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->where('p.id=:id')
                ->setParameter('id', $id);
        
        $results = $this->getResult($qb,$locale);

        return count($results)?$results[0]:null;
    }
    
    public function findLastSortableIndex($field)
    {
        $qb = $this->createQueryBuilder('p');
        
        $qb->select('p.'.$field)
            ->orderBy('p.'.$field,'DESC')
            ->setMaxResults(1)
                ;
        
        $result = $qb->getQuery()->getResult();
        
        if (!count($result))
        {
            return 0;
        }
        else
        {
            return $qb->getQuery()->getSingleScalarResult();
        }
        
//        die($qb->getQuery()->getSingleScalarResult());
//        return $qb->getQuery()->getArrayResult();
    }
//    public function findBySlug($slug)
//    {
//        $dql = "SELECT trans.foreign_key FROM {$this->getEntityName()} trans";
//            $dql .= ' WHERE trans.object_class = :class';
//            $dql .= ' AND trans.field = :field';
//            $dql .= ' AND trans.content = :value';
//            $q = $this->getEntityManager()->createQuery($dql);
//            $q->setParameters(['class','slug'=>'slug','value'=>$slug]);
//            $q->setMaxResults(1);
//            $result = $q->getArrayResult();
//            $id = count($result) ? $result[0]['foreignKey'] : null;
//
//            if ($id) {
//                $entity = $this->_em->find($class, $id);
//            }
//        
//    }
    
    public function findAll($locale = null)
    {
//        die();
        $qb = $this->createQueryBuilder('p');
        return $this->getResult($qb,$locale);
    }
    
    public function findById(array $criteria, $orderBy = null,$locale = null)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->where($qb->expr()->in('p.id',$criteria));
        
        if ($orderBy  !== null)
        {
            foreach ($orderBy as $orderKey => $order)
            {
                $qb->orderBy('p.'.$orderKey, $order);
            }
        }
        

        return $this->getResult($qb,$locale);
    }
    
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null,$locale = null)
    {
        return $this->getResult($this->createfindByQueryBuilder($criteria,$orderBy,$limit,$offset),$locale);
    }
    
    public function findOneBy(array $criteria, array $orderBy = null, $offset = null,$locale = null)
    {
        return $this->getSingleResult($this->createfindByQueryBuilder($criteria,$orderBy,null,$offset),$locale);
    }
    public function findOneOrNullBy(array $criteria, array $orderBy = null, $offset = null,$locale = null)
    {
        return $this->getOneOrNullResult($this->createfindByQueryBuilder($criteria,$orderBy,null,$offset),$locale);
    }
    
    private function createfindByQueryBuilder(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $qb = $this->createQueryBuilder('p');
        
        $i = 0;
        foreach($criteria as $whereKey => $whereValue)
        {
            $qb
                ->andWhere('p.'.$whereKey.' = :whereKey'.$i)
                ->setParameter('whereKey'.$i, $whereValue)
                ;
            $i++;
        }
        
        if ($orderBy  !== null)
        {
            if (array_key_exists('RANDOM', $orderBy))
            {
                $qb //->join('RANDOM()', 'rnd')
                    ->addSelect('RAND() as rnd')
//                        ->innerJoin('rnd', 'rnd')
                    ->orderBy('rnd');
            }
            else 
            {
                foreach ($orderBy as $orderKey => $order)
                {
                    $qb->orderBy('p.'.$orderKey, $order);
                }
            }
            
        }
        if ($limit !== null && $limit > 0)
        {
            $qb->setMaxResults($limit);
        }
        if ($offset!== null)
        {
            $qb->setFirstResult($offset);
        }
        
        return $qb;
    }
    
    
    public function findPrevEntity($property,$position)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->where('p.'.$property.'< :position')
            ->setParameter('position', $position)
            ->orderBy('p.'.$property,'DESC')
            ->setMaxResults(1)
            ;
//        //dump($qb->getQuery()->getSQL());
//        //dump($qb->getQuery()->getParameters());
        return $this->getOneOrNullResult($qb);
    }
    
    public function findNextEntity($property,$position)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->where('p.'.$property.'> :position')
            ->setParameter('position', $position)
            ->orderBy('p.'.$property,'ASC')
            ->setMaxResults(1)
            ;
        
        
        return $this->getOneOrNullResult($qb);
    }
}
