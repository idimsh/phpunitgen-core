<?php

declare(strict_types=1);

namespace PhpUnitGen\Core\Models;

use PhpUnitGen\Core\Contracts\Renderers\Renderable;
use PhpUnitGen\Core\Contracts\Renderers\Renderer;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Tightenco\Collect\Support\Collection;

/**
 * Class TestClass.
 *
 * @package PhpUnitGen\Core
 * @author  Paul Thébaud <paul.thebaud29@gmail.com>
 * @author  Killian Hascoët <killianh@live.fr>
 * @license MIT
 */
class TestClass implements Renderable
{
    /**
     * @var ReflectionClass $reflectionClass The class for which this test class is created.
     */
    protected $reflectionClass;

    /**
     * @var string $name The complete name of the class (including namespace).
     */
    protected $name;

    /**
     * @var TestImport[]|Collection $imports The list of test imports.
     */
    protected $imports;

    /**
     * @var TestTrait[]|Collection $traits The list of test traits.
     */
    protected $traits;

    /**
     * @var TestProperty[]|Collection $properties The list of test properties.
     */
    protected $properties;

    /**
     * @var TestMethod[]|Collection $methods The list of test methods.
     */
    protected $methods;

    /**
     * TestClass constructor.
     *
     * @param ReflectionClass $reflectionClass
     * @param string          $name
     */
    public function __construct(ReflectionClass $reflectionClass, string $name)
    {
        $this->reflectionClass = $reflectionClass;
        $this->name            = $name;

        $this->imports    = new Collection();
        $this->traits     = new Collection();
        $this->properties = new Collection();
        $this->methods    = new Collection();
    }

    /**
     * {@inheritDoc}
     */
    public function accept(Renderer $renderer): void
    {
        $renderer->visitTestClass($this);
    }

    /**
     * @return ReflectionClass
     */
    public function getReflectionClass(): ReflectionClass
    {
        return $this->reflectionClass;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return TestImport[]|Collection
     */
    public function getImports(): Collection
    {
        return $this->imports;
    }

    /**
     * @param TestImport $import
     *
     * @return static
     */
    public function addImport(TestImport $import): self
    {
        $this->imports->add($import);

        return $this;
    }

    /**
     * @return TestTrait[]|Collection
     */
    public function getTraits(): Collection
    {
        return $this->traits;
    }

    /**
     * @param TestTrait $trait
     *
     * @return static
     */
    public function addTrait(TestTrait $trait): self
    {
        $this->traits->add($trait);

        return $this;
    }

    /**
     * @return TestProperty[]|Collection
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param TestProperty $property
     *
     * @return static
     */
    public function addProperty(TestProperty $property): self
    {
        $this->properties->add($property);

        return $this;
    }

    /**
     * @return TestMethod[]|Collection
     */
    public function getMethods(): Collection
    {
        return $this->methods;
    }

    /**
     * @param TestMethod $method
     *
     * @return static
     */
    public function addMethod(TestMethod $method): self
    {
        $this->methods->add($method);

        return $this;
    }
}
