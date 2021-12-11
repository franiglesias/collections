<?php

namespace Test\Collections;

use Fi\Collections\Collection;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    public function testItInitiTes(): void
    {
        $this->assertInstanceOf(Collection::class, $this->getCollection());
    }

    private function getCollection(): Collection
    {
        return Collection::ofType(get_class($this));
    }

    public function testItContTnsZeroItemsOnCreation(): void
    {
        $sut = $this->getCollection();
        $this->assertEquals(0, $sut->count());
    }

    public function testItCanTpendOneElement(): void
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $this->assertEquals(1, $sut->count());
    }

    public function testItCanTpendTwoElements(): void
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $sut->append($this);
        $this->assertEquals(2, $sut->count());
    }

    public function testItCanTitializeCollectionWithAType(): void
    {
        $sut = Collection::ofType(CollectionTest::class);
        $this->assertInstanceOf(Collection::class, $sut);
    }

    public function testItDoesTotStoreObjectsOfAIncorrectType(): void
    {
        $sut = $this->getCollection();
        $this->expectException(\UnexpectedValueException::class);
        $sut->append(new class
        {
        });
    }

    public function testItCanStorePrimitiveType(): void
    {
        $sut = $sut = Collection::ofType('string');
        $sut->append('me');
        $this->assertEquals(1, $sut->count());
    }

    public function testItCanToreSubclasessOfTheType(): void
    {
        $sut = $this->getCollection();
        $sut->append(new class extends CollectionTest
        {
        });
        $this->assertEquals(1, $sut->count());
    }

    public function testEachDoesNothingOnEmptyCollection(): void
    {
        $sut = $this->getCollection();
        $log = '';
        $sut->each(function () use (&$log) {
            $log .= '*';
        });
        $this->assertEquals('', $log);
    }

    public function testEachCanIterateOneElement(): void
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $log = '';
        $sut->each(function () use (&$log) {
            $log .= '*';
        });
        $this->assertEquals('*', $log);
    }

    public function testEachCanIterateTwoElements(): void
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $sut->append($this);
        $log = '';
        $sut->each(function () use (&$log) {
            $log .= '*';
        });
        $this->assertEquals('**', $log);
    }

    public function testEachElementIsPassedToFunction(): void
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $sut->append($this);
        $log = '';
        $sut->each(function (CollectionTest $element) use (&$log) {
            $log .= '*';
        });
        $this->assertEquals('**', $log);
    }

    public function testEachMethodAllowsPipeline(): void
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $log = '';
        $result = $sut->each(function (CollectionTest $element) use (&$log) {
            $log .= '*';
        });
        $this->assertInstanceOf(Collection::class, $result);
    }

    public function testEachMethodOnEmptyCollectionAllowsPipeline(): void
    {
        $sut = $this->getCollection();
        $log = '';
        $result = $sut->each(function (CollectionTest $element) use (&$log) {
            $log .= '*';
        });
        $this->assertInstanceOf(Collection::class, $result);
    }

    public function testMapMethodOnEmptyCollectionAllowsPipeline(): void
    {
        $sut = $this->getCollection();
        $result = $sut->map(function (CollectionTest $element) {
            return $element;
        });
        $this->assertInstanceOf(Collection::class, $result);
    }

    public function testMapMethodOnEmptyCollectionReturnsEmptyCollection(): void
    {
        $sut = $this->getCollection();
        $result = $sut->map(function (CollectionTest $element) {
            return $element;
        });
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEquals(0, $result->count());
    }

    public function testMapMethodReturnsAnotherCollection(): void
    {
        $sut = $this->getCollection();
        $result = $sut->map(function (CollectionTest $element) {
            return $element;
        });
        $this->assertNotSame($sut, $result);
    }

    public function testMapCanMapOneElement(): void
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $result = $sut->map(function (CollectionTest $element) {
            return new self();
        });
        $this->assertEquals(self::class, $result->getType());
        $this->assertEquals(1, $result->count());
    }

    public function testMapCanMapTwoElements(): void
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $sut->append($this);
        $result = $sut->map(function (CollectionTest $element) {
            return new self();
        });
        $this->assertEquals(self::class, $result->getType());
        $this->assertEquals(2, $result->count());
    }

    public function testFilterReturnsACollection(): void
    {
        $sut = $this->getCollection();
        $result = $sut->filter(function (CollectionTest $element) {
            return false;
        });
        $this->assertInstanceOf(Collection::class, $result);
    }

    public function testFilterReturnsACollectionThatIsNotTheSame(): void
    {
        $sut = $this->getCollection();
        $result = $sut->filter(function (CollectionTest $element) {
            return false;
        });
        $this->assertNotSame($sut, $result);
    }

    public function testFilterReturnsACollectionWithTheSameTypeOfObjects(): void
    {
        $sut = $this->getCollection();
        $result = $sut->filter(function (CollectionTest $element) {
            return false;
        });
        $this->assertEquals(CollectionTest::class, $result->getType());
    }

    public function testFilterIncludeElementIfFilterFunctionReturnsTrue(): void
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $result = $sut->filter(function (CollectionTest $element) {
            return true;
        });
        $this->assertEquals(1, $result->count());
    }

    public function testFilterDoesNotIncludeElementIfFilterFunctionReturnsFalse(): void
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $result = $sut->filter(function (CollectionTest $element) {
            return false;
        });
        $this->assertEquals(0, $result->count());
    }

    public function testFilterIteratesAllElementsInCollection(): void
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $sut->append(clone $this);
        $result = $sut->filter(function (CollectionTest $element) {
            return true;
        });
        $this->assertEquals($sut, $result);
    }

    public function testGetByThrowsExceptionOnEmptyCollection(): void
    {
        $sut = $this->getCollection();
        $this->expectException(\UnderflowException::class);
        $sut->getBy(function (CollectionTest $element) {
            return true;
        });
    }

    public function testGetByThrowsExceptionIfElementIsNotFound(): void
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $this->expectException(\OutOfBoundsException::class);
        $sut->getBy(function (CollectionTest $element) {
            return false;
        });
    }

    public function testGetByReturnsElementIfFound(): void
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $result = $sut->getBy(function (CollectionTest $element) {
            return true;
        });
        $this->assertSame($this, $result);
    }

    public function testGetBySelectsTheRightElement(): void
    {
        $sut = $this->getCollection();
        $target = clone $this;
        $target->target = true;
        $sut->append($this);
        $sut->append($target);
        $result = $sut->getBy(function (CollectionTest $element) {
            return $element->isTarget();
        });
        $this->assertSame($target, $result);
    }

    public function testReduceReturnsInitialValueForEmptyCollection(): void
    {
        $sut = $this->getCollection();
        $result = $sut->reduce(function (CollectionTest $element, $acumulator) {
            return $acumulator + 1;
        }, 0);
        $this->assertEquals(0, $result);
    }

    public function testReduceInitialCanBeAnyType(): void
    {
        $sut = $this->getCollection();
        $result = $sut->reduce(function (CollectionTest $element, $acumulator) {
            return $acumulator + 1;
        }, "");
        $this->assertEquals("", $result);
    }

    public function testReduceAppliesReduceFunctionToOneElement(): void
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $result = $sut->reduce(function (CollectionTest $element, $acumulator) {
            return $acumulator + 1;
        }, 0);
        $this->assertEquals(1, $result);
    }

    public function testReduceAppliesReduceFunctionToSeveralElements(): void
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $sut->append($this);
        $result = $sut->reduce(function (CollectionTest $element, $acumulator) {
            return $acumulator + 1;
        }, 0);
        $this->assertEquals(2, $result);
    }

    public function testCollectArrayUsesFirstElementToInstanceCollection(): void
    {
        $sut = Collection::collect([
            $this
        ]);
        $this->assertEquals(CollectionTest::class, $sut->getType());
    }

    public function testCollectEmptyArrayFailsWithException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Collection::collect([]);
    }

    public function testCollectArrayWithOneElementPopulatesCollection(): void
    {
        $sut = Collection::collect([
            $this
        ]);
        $this->assertEquals(1, $sut->count());
    }

    public function testCollectArrayWithSeveralElementsPopulatesCollection(): void
    {
        $sut = Collection::collect([
            $this,
            $this
        ]);
        $this->assertEquals(2, $sut->count());
    }

    public function testInvalidTypeInArrayThrowsException(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        Collection::collect([
            $this,
            new \stdClass()
        ]);
    }

    public function testEmptyCollectionMapsToEmptyArray(): void
    {
        $sut = $this->getCollection();
        $this->assertEquals([], $sut->toArray());
    }

    public function testCollectionCanBeReturnedAsArray(): void
    {
        $sample = [$this];
        $sut = Collection::collect($sample);
        $this->assertEquals($sample, $sut->toArray());
    }

    public function testCollectionCanBeMappedToArray(): void
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $this->assertEquals(['mapped'], $sut->toArray(function (CollectionTest $element) {
            return 'mapped';
        }));
    }

    public function testCollectionWithTwoElementsCanBeMappedToArray(): void
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $sut->append($this);
        $this->assertEquals(['mapped', 'mapped'], $sut->toArray(function (CollectionTest $element) {
            return 'mapped';
        }));
    }

    public function testCollectionGetType(): void
    {
        $sut = Collection::ofType(CollectionTest::class);
        $this->assertEquals(CollectionTest::class, $sut->getType());
    }

    public function testCollectionIsEmpty(): void
    {
        $sut = $this->getCollection();
        $this->assertTrue($sut->isEmpty());
    }

    public function testCollectionIsNotEmpty(): void
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $this->assertFalse($sut->isEmpty());
    }

    public function isTarget(): bool
    {
        return isset($this->target);
    }
}
