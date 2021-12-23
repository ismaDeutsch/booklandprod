<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Isbn extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'L\'ISBN "{{ value }}" n\'est pas valide.';

    /**
     * @return string
     */
    public function validatedBy()
    {
        return get_class($this).'Validator';
    }
}
