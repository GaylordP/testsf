<?php

namespace App\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Slugger
{
    /**
     * @var string
     */
    public $field;
}
