<?php
namespace SKCMS\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;
/**
 * Description of TranslationRepository
 *
 * @author Jona
 */
class TranslationRepository extends EntityRepository
{
    
    
    public function findObjectByTranslation($field,$value,$className,$locale)
    {

        $qb = $this->createQueryBuilder('e')
                ->where('e.field=:field')
                ->setParameter('field', $field)
                ->andWhere('e.content=:value')
                ->setParameter('value', $value)
                ->setParameter('field', $field)
                ->andWhere('e.objectClass=:className')
                ->setParameter('className', substr($className, 0,1)== '\\' ? substr($className,1) : $className)
                ->andWhere('e.locale=:locale')
                ->setParameter('locale', $locale)
            ;
        
        return $qb->getQuery()->getOneOrNullResult();
    }
    
    public function findAllTranslations()
    {
        $qb = $this->createQueryBuilder('e');
        
        return $qb->getQuery()->getResult();
    }
    
    public function findObjectBySlug($slug,$locale)
    {
        
        $qb = $this->createQueryBuilder('e')
                ->where('e.field= :slug')
                ->setParameter('slug', 'slug')
                ->andWhere('e.content=:value')
                ->setParameter('value', $slug)
                ->andWhere('e.locale=:locale')
                ->setParameter('locale', $locale)
            ;
        

         return $qb->getQuery()->getOneOrNullResult();
    }
    
    public function findTranslation($content,$locale)
    {
        $qb = $this->createQueryBuilder('e')
               ->andWhere('e.content=:value')
                ->setParameter('value', $content)
                ->andWhere('e.locale=:locale')
                ->setParameter('locale', $locale)
            ;
        return $qb->getQuery()->getOneOrNullResult();
    }
    
    
}
