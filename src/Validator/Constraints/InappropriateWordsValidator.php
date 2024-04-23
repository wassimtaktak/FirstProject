<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class InappropriateWordsValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        // You can implement your logic here to check for inappropriate words
        $inappropriateWords = ['word1', 'word2', 'word3']; // Replace with your list of inappropriate words

        foreach ($inappropriateWords as $word) {
            if (stripos($value, $word) !== false) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ string }}', $value)
                    ->addViolation();
                break; // Stop checking once an inappropriate word is found
            }
        }
    }
}