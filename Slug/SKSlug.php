<?php

namespace SKCMS\CoreBundle\Slug;


/**
 * @Annotation
 */
class SKSlug 
{
    private $field;
    
    public function __construct($options)
    {
        if (isset($options['value'])) {
            $options['propertyName'] = $options['value'];
            unset($options['value']);
        }

        foreach ($options as $key => $value) {
            if (!property_exists($this, $key)) {
                throw new \InvalidArgumentException(sprintf('Property "%s" does not exist', $key));
            }

            $this->$key = $value;
        }
    }
    
    public function getField()
    {
        return $this->field;
    }
    
    
}
