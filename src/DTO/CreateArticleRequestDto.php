<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final class CreateArticleRequestDto
{
    public function __construct(
        #[Assert\NotBlank]
        public string $title,

        #[Assert\NotBlank]
        public string $content)
    {
    }
}