<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsbnValidator extends ConstraintValidator
{


    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\Isbn */

        if (null === $value || '' === $value) {
            return;
        }

        //978-0-300-12223-7
        if (preg_match('/^(?=(?=(?:[0-9]+[-\ ]){4})[-\ 0-9]{17}$)97[89][-\ ]?[0-9]{1,5}[-\ ]?[0-9]+[-\ ]?[0-9]+[-\ ]?[0-9]$/',
            $value, $matches)) {
            $j = 0; $X = 0; $Y = 0; $cpt = 0;

            for ($i = strlen($value)-1; $i >= 0; $i--){
                if($value[$i] !== "-"){
                    $cpt++;
                    $j = ($cpt) % 2;
                    if ($j === 0){
                        $X += (int) $value[$i];
                    }
                    else{
                        $Y += (int)$value[$i];
                    }
                }
            }

            if(((3 * $X + $Y) % 10) !== 0)
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ value }}', $value)
                    ->addViolation();

        }else{
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }

}


