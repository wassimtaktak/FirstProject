<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class InappropriateWords extends Constraint
{
    public $message = 'The message contains inappropriate words.';
}
