<?php

declare(strict_types=1);

namespace Tests\PhpUnitGen\Core\Unit\Helpers;

use Mockery;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlockFactoryInterface;
use PhpUnitGen\Core\Helpers\Reflect;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflection\ReflectionMethod;
use Roave\BetterReflection\Reflection\ReflectionProperty;
use Tests\PhpUnitGen\Core\TestCase;

/**
 * Class ReflectTest.
 *
 * @covers \PhpUnitGen\Core\Helpers\Reflect
 */
class ReflectTest extends TestCase
{
    public function testMethods(): void
    {
        $reflectionClass = Mockery::mock(ReflectionClass::class);

        $reflectionClass->shouldReceive('getImmediateMethods')
            ->andReturn([]);

        $this->assertSame([], Reflect::methods($reflectionClass)->toArray());
    }

    public function testMethod(): void
    {
        $reflectionClass = Mockery::mock(ReflectionClass::class);

        $reflectionMethod1 = Mockery::mock(ReflectionMethod::class);
        $reflectionMethod1->shouldReceive('getShortName')
            ->andReturn('foo');
        $reflectionMethod2 = Mockery::mock(ReflectionMethod::class);
        $reflectionMethod2->shouldReceive('getShortName')
            ->andReturn('bar');

        $reflectionClass->shouldReceive('getImmediateMethods')
            ->andReturn([
                $reflectionMethod1,
                $reflectionMethod2,
            ]);

        $this->assertSame($reflectionMethod1, Reflect::method($reflectionClass, 'foo'));
        $this->assertSame($reflectionMethod2, Reflect::method($reflectionClass, 'bar'));
        $this->assertNull(Reflect::method($reflectionClass, 'baz'));
    }

    public function testProperties(): void
    {
        $reflectionClass = Mockery::mock(ReflectionClass::class);

        $reflectionClass->shouldReceive('getImmediateProperties')
            ->andReturn([]);

        $this->assertSame([], Reflect::properties($reflectionClass)->toArray());
    }

    public function testProperty(): void
    {
        $reflectionClass = Mockery::mock(ReflectionClass::class);

        $reflectionProperty1 = Mockery::mock(ReflectionProperty::class);
        $reflectionProperty1->shouldReceive('getName')
            ->andReturn('foo');
        $reflectionProperty2 = Mockery::mock(ReflectionProperty::class);
        $reflectionProperty2->shouldReceive('getName')
            ->andReturn('bar');

        $reflectionClass->shouldReceive('getImmediateProperties')
            ->andReturn([
                $reflectionProperty1,
                $reflectionProperty2,
            ]);

        $this->assertSame($reflectionProperty1, Reflect::property($reflectionClass, 'foo'));
        $this->assertSame($reflectionProperty2, Reflect::property($reflectionClass, 'bar'));
        $this->assertNull(Reflect::property($reflectionClass, 'baz'));
    }

    public function testParameters(): void
    {
        $reflectionMethod = Mockery::mock(ReflectionMethod::class);

        $reflectionMethod->shouldReceive('getParameters')
            ->andReturn([]);

        $this->assertSame([], Reflect::parameters($reflectionMethod)->toArray());
    }

    public function testDocBlockWhenDefaultFactoryAndEmptyDocComment(): void
    {
        $reflectionMethod = Mockery::mock(ReflectionMethod::class);
        $reflectionMethod->shouldReceive('getDocComment')
            ->withNoArgs()
            ->andReturn('');

        $this->assertNull(Reflect::docBlock($reflectionMethod));
    }

    public function testDocBlockWhenDefaultFactoryAndNotEmptyDocComment(): void
    {
        $reflectionMethod = Mockery::mock(ReflectionMethod::class);
        $reflectionMethod->shouldReceive('getDocComment')
            ->withNoArgs()
            ->andReturn('/** @author John Doe */');

        $this->assertInstanceOf(DocBlock::class, Reflect::docBlock($reflectionMethod));
    }

    public function testDocBlockWhenCustomFactory(): void
    {
        $reflectionMethod = Mockery::mock(ReflectionMethod::class);
        $reflectionMethod->shouldReceive('getDocComment')
            ->withNoArgs()
            ->andReturn('/** @author John Doe */');

        $docBlock = new DocBlock();

        $docBlockFactory = Mockery::mock(DocBlockFactoryInterface::class);
        $docBlockFactory->shouldReceive('create')
            ->with('/** @author John Doe */')
            ->andReturn($docBlock);

        Reflect::setDocBlockFactory($docBlockFactory);

        $this->assertSame($docBlock, Reflect::docBlock($reflectionMethod));

        Reflect::setDocBlockFactory(null);
    }

    public function testDocBlockTagsWhenEmptyDocComment(): void
    {
        $reflectionMethod = Mockery::mock(ReflectionMethod::class);
        $reflectionMethod->shouldReceive('getDocComment')
            ->withNoArgs()
            ->andReturn('');

        $tags = Reflect::docBlockTags($reflectionMethod);

        $this->assertTrue($tags->isEmpty());
    }

    public function testDocBlockTagsWhenNotEmptyDocComment(): void
    {
        $reflectionMethod = Mockery::mock(ReflectionMethod::class);
        $reflectionMethod->shouldReceive('getDocComment')
            ->withNoArgs()
            ->andReturn('/**
            * @author John Doe
            * @see https://example.com
            */');

        $tags = Reflect::docBlockTags($reflectionMethod);

        $this->assertFalse($tags->isEmpty());
        $this->assertCount(2, $tags);
        $this->assertSame('@author John Doe', $tags->get(0)->render());
        $this->assertSame('@see https://example.com', $tags->get(1)->render());
    }
}
