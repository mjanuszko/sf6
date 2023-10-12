<?php

namespace App\DTO;

class UpdateArticleRequestDto
{
    public function __construct(
        public string $title,
        public string $content)
    {
    }
}