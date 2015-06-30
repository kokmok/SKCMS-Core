<?php
namespace SKCMS\CoreBundle\Entity\Translation;


use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractTranslation;

/**
 * @ORM\Table(name="ext_translations_entity", indexes={
 *      @ORM\Index(name="entity_translation_idx", columns={"locale", "object_class", "field", "foreign_key"})
 * })
 * @ORM\Entity(repositoryClass="SKCMS\CoreBundle\Repository\TranslationRepository")
 */
//@ORM\Entity(repositoryClass="Gedmo\Translatable\Entity\Repository\TranslationRepository")
class EntityTranslation extends AbstractTranslation
{
    //put your code here
}
