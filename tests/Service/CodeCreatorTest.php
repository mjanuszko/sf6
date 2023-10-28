<?php

namespace App\Tests\Service;

use App\Service\CodeCreator;
use PHPUnit\Framework\TestCase;

class CodeCreatorTest extends TestCase
{
    public function test_createCode_should_return_5_digit_number_with_prefix(): void
    {
        $codeCreator = new CodeCreator();

        $code = $codeCreator->createCode('test');

        $this->assertStringStartsWith('test-', $code);
        $this->assertMatchesRegularExpression('/test-\d{5}/', $code);
    }
}
