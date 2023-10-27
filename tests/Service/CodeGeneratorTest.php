<?php

namespace App\Tests\Service;

use App\Service\CodeCreator;
use App\Service\CodeGenerator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;

class CodeGeneratorTest extends KernelTestCase
{
    public function test_generate_should_return_5_digit_code_with_prefix(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $filesystem = $container->get(Filesystem::class);
        $codeCreator = $container->get(CodeCreator::class);
        $codeGenerator = new CodeGenerator($filesystem, 'test', $codeCreator);

        $code = $codeGenerator->generate();

        $this->assertIsString($code);
        $this->assertMatchesRegularExpression('/^test-[0-9]{5}$/', $code);
    }

    public function test_generate_should_create_file_containing_code(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $filesystem = $container->get(Filesystem::class);
        $codeCreator = $container->get(CodeCreator::class);
        $codeGenerator = new CodeGenerator($filesystem, 'test', $codeCreator);

        $code = $codeGenerator->generate();

        $this->assertFileExists('codes/'.$code.'.txt');
        $this->assertEquals($code, file_get_contents('codes/'.$code.'.txt'));
    }
}
