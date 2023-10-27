<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;

class CodeGenerator
{
    /**
     * CodeGenerator constructor.
     * @param Filesystem $filesystem
     * @param string $codePrefix
     * @param CodeCreator $codeCreator
     */
    public function __construct(
        private Filesystem $filesystem,
        private string $codePrefix,
        private CodeCreator $codeCreator)
    {
    }

    public function generate(): string
    {
        $code = $this->codeCreator->createCode($this->codePrefix);

        $this->filesystem->mkdir('codes');
        $this->filesystem->touch('codes/'.$code.'.txt');
        file_put_contents('codes/'.$code.'.txt', $code);

        return $code;
    }
}