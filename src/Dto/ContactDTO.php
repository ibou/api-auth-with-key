<?php

declare(strict_types=1);

namespace App\Dto;
use Symfony\Component\Validator\Constraints as Assert;

class ContactDTO
{

    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 50)]
    public string $name = '';

    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email = '';

    #[Assert\NotBlank]
    #[Assert\Length(min: 10, max: 500)]
    public string $message = '';
}