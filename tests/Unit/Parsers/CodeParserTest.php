<?php

declare(strict_types=1);

namespace Tests\PhpUnitGen\Core\Unit\Parsers;

use Mockery\Mock;
use Tests\PhpUnitGen\Core\TestCase;
use PhpUnitGen\Core\Exceptions\InvalidArgumentException;
use PhpUnitGen\Core\Parsers\CodeParser;
use PhpUnitGen\Core\Parsers\Sources\StringSource;
use Roave\BetterReflection\BetterReflection;

/**
 * Class CodeParserTest.
 *
 * @covers \PhpUnitGen\Core\Parsers\CodeParser
 */
class CodeParserTest extends TestCase
{
    /**
     * @var Mock
     */
    protected $betterReflection;

    /**
     * @var CodeParser $codeParser
     */
    protected $codeParser;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->codeParser = new CodeParser(new BetterReflection());
    }

    public function testItThrowsAnExceptionWhenNoClassInCode(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'code must contains exactly one class/interface/trait, found 0'
        );

        $this->codeParser->parse(new StringSource('<?php'));
    }

    public function testItThrowsAnExceptionWhenTooMuchClassesInCode(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'code must contains exactly one class/interface/trait, found 2'
        );

        $this->codeParser->parse(new StringSource('<?php class Foo {} class Bar {}'));
    }

    public function testItReturnsReflectionClass(): void
    {
        $class = $this->codeParser->parse(new StringSource('<?php class Foo {}'));

        $this->assertSame('Foo', $class->getName());
    }
}
