<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class BanWord extends Constraint
{

    public function __construct(
        public string $message = 'This contains a banned word "{{ banWord }}".',
        public array $banWords = ['spam', 'argent', 'jeux'],
        ?array $groups = null,
        mixed $payload = null,
    )
    {
        parent::__construct(
            options: null,
            groups: $groups,
            payload: $payload,
        );
    }
}
