<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CommentRequestDto
{
    public function __construct(
        #[Assert\NotBlank]
        public string $user,

        #[Assert\NotBlank]
        public string $content
    )
    {
    }
}