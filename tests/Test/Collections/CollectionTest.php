<?php

namespace Test\Collections;

use Fi\Collections\Collection;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    public function testItInitiTes()
    {
        $this->assertInstanceOf(Collection::class, $this->getCollection());
    }

    private function getCollection(): Collection
    {
        return Collection::ofType(get_class($this));
    }

    public function testItContTnsZeroItemsOnCreation()
    {
        $sut = $this->getCollection();
        $this->assertEquals(0, $sut->count());
    }

    public function testItCanTpendOneElement()
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $this->assertEquals(1, $sut->count());
    }

    public function testItCanTpendTwoElements()
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $sut->append($this);
        $this->assertEquals(2, $sut->count());
    }

    public function testItCanTitializeCollectionWithAType()
    {
        $sut = Collection::ofType(CollectionTest::class);
        $this->assertInstanceOf(Collection::class, $sut);
    }

    public function testItDoesTotStoreObjectsOfAIncorrectType()
    {
        $sut = $this->getCollection();
        $this->expectException(\UnexpectedValueException::class);
        $sut->append(new class
        {
        });
    }

    public function testItCanToreSubclasessOfTheType()
    {
        $sut = $this->getCollection();
        $sut->append(new class extends CollectionTest
        {
        });
        $this->assertEquals(1, $sut->count());
    }

    public function testEachDoesNothingOnEmptyCollection()
    {
        $sut = $this->getCollection();
        $log = '';
        $sut->each(function () use (&$log) {
            $log .= '*';
        });
        $this->assertEquals('', $log);
    }

    public function testEachCanIterateOneElement()
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $log = '';
        $sut->each(function () use (&$log) {
            $log .= '*';
        });
        $this->assertEquals('*', $log);
    }

    public function testEachCanIterateTwoElements()
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

    public function testEachElementIsPassedToFunction()
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

    public function testEachMethodAllowsPipeline()
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $log = '';
        $result = $sut->each(function (CollectionTest $element) use (&$log) {
            $log .= '*';
        });
        $this->assertInstanceOf(Collection::class, $result);
    }

    public function testEachMethodOnEmptyCollectionAllowsPipeline()
    {
        $sut = $this->getCollection();
        $log = '';
        $result = $sut->each(function (CollectionTest $element) use (&$log) {
            $log .= '*';
        });
        $this->assertInstanceOf(Collection::class, $result);
    }

    public function testMapMethodOnEmptyCollectionAllowsPipeline()
    {
        $sut = $this->getCollection();
        $result = $sut->map(function (CollectionTest $element) {
            return $element;
        });
        $this->assertInstanceOf(Collection::class, $result);
    }

    public function testMapMethodOnEmptyCollectionReturnsEmptyCollection()
    {
        $sut = $this->getCollection();
        $result = $sut->map(function (CollectionTest $element) {
            return $element;
        });
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEquals(0, $result->count());
    }

    public function testMapMethodReturnsAnotherCollection()
    {
        $sut = $this->getCollection();
        $result = $sut->map(function (CollectionTest $element) {
            return $element;
        });
        $this->assertNotSame($sut, $result);
    }

    public function testMapCanMapOneElement()
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $result = $sut->map(function (CollectionTest $element) {
            return new self();
        });
        $this->assertEquals(self::class, $result->getType());
        $this->assertEquals(1, $result->count());
    }

    public function testMapCanMapTwoElements()
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

    public function testFilterReturnsACollection()
    {
        $sut = $this->getCollection();
        $result = $sut->filter(function (CollectionTest $element) {
            return false;
        });
        $this->assertInstanceOf(Collection::class, $result);
    }

    public function testFilterReturnsACollectionThatIsNotTheSame()
    {
        $sut = $this->getCollection();
        $result = $sut->filter(function (CollectionTest $element) {
            return false;
        });
        $this->assertNotSame($sut, $result);
    }

    public function testFilterReturnsACollectionWithTheSameTypeOfObjects()
    {
        $sut = $this->getCollection();
        $result = $sut->filter(function (CollectionTest $element) {
            return false;
        });
        $this->assertEquals(CollectionTest::class, $result->getType());
    }

    public function testFilterIncludeElementIfFilterFunctionReturnsTrue()
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $result = $sut->filter(function (CollectionTest $element) {
            return true;
        });
        $this->assertEquals(1, $result->count());
    }

    public function testFilterDoesNotIncludeElementIfFilterFunctionReturnsFalse()
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $result = $sut->filter(function (CollectionTest $element) {
            return false;
        });
        $this->assertEquals(0, $result->count());
    }

    public function testFilterIteratesAllElementsInCollection()
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $sut->append(clone $this);
        $result = $sut->filter(function (CollectionTest $element) {
            return true;
        });
        $this->assertEquals($sut, $result);
    }

    public function testGetByThrowsExceptionOnEmptyCollection()
    {
        $sut = $this->getCollection();
        $this->expectException(\UnderflowException::class);
        $sut->getBy(function (CollectionTest $element) {
            return true;
        });
    }

    public function testGetByThrowsExceptionIfElementIsNotFound()
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $this->expectException(\OutOfBoundsException::class);
        $sut->getBy(function (CollectionTest $element) {
            return false;
        });
    }

    public function testGetByReturnsElementIfFound()
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $result = $sut->getBy(function (CollectionTest $element) {
            return true;
        });
        $this->assertSame($this, $result);
    }

    public function testGetBySelectsTheRightElement()
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

    public function testReduceReturnsInitialValueForEmptyCollection()
    {
        $sut = $this->getCollection();
        $result = $sut->reduce(function (CollectionTest $element, $acumulator) {
            return $acumulator + 1;
        }, 0);
        $this->assertEquals(0, $result);
    }

    public function testReduceInitialCanBeAnyType()
    {
        $sut = $this->getCollection();
        $result = $sut->reduce(function (CollectionTest $element, $acumulator) {
            return $acumulator + 1;
        }, "");
        $this->assertEquals("", $result);
    }

    public function testReduceAppliesReduceFunctionToOneElement()
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $result = $sut->reduce(function (CollectionTest $element, $acumulator) {
            return $acumulator + 1;
        }, 0);
        $this->assertEquals(1, $result);
    }

    public function testReduceAppliesReduceFunctionToSeveralElements()
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $sut->append($this);
        $result = $sut->reduce(function (CollectionTest $element, $acumulator) {
            return $acumulator + 1;
        }, 0);
        $this->assertEquals(2, $result);
    }

    public function testCollectArrayUsesFirstElementToInstanceCollection()
    {
        $sut = Collection::collect([
            $this
        ]);
        $this->assertEquals(CollectionTest::class, $sut->getType());
    }

    public function testCollectEmptyArrayFailsWithException()
    {
        $this->expectException(\InvalidArgumentException::class);
        Collection::collect([]);
    }

    public function testCollectArrayWithOneElementPopulatesCollection()
    {
        $sut = Collection::collect([
            $this
        ]);
        $this->assertEquals(1, $sut->count());
    }

    public function testCollectArrayWithSeveralElementsPopulatesCollection()
    {
        $sut = Collection::collect([
            $this,
            $this
        ]);
        $this->assertEquals(2, $sut->count());
    }

    public function testInvalidTypeInArrayThrowsException()
    {
        $this->expectException(\UnexpectedValueException::class);
        Collection::collect([
            $this,
            new \stdClass()
        ]);
    }

    public function testEmptyCollectionMapsToEmptyArray()
    {
        $sut = $this->getCollection();
        $this->assertEquals([], $sut->toArray());
    }

    public function testCollectionCanBeReturnedAsArray()
    {
        $sample = [$this];
        $sut = Collection::collect($sample);
        $this->assertEquals($sample, $sut->toArray());
    }

    public function testCollectionCanBeMappedToArray()
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $this->assertEquals(['mapped'], $sut->toArray(function (CollectionTest $element) {
            return 'mapped';
        }));
    }

    public function testCollectionWithTwoElementsCanBeMappedToArray()
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $sut->append($this);
        $this->assertEquals(['mapped', 'mapped'], $sut->toArray(function (CollectionTest $element) {
            return 'mapped';
        }));
    }

    public function testCollectionGetType()
    {
        $sut = Collection::ofType(CollectionTest::class);
        $this->assertEquals(CollectionTest::class, $sut->getType());
    }

    public function testCollectionIsEmpty()
    {
        $sut = $this->getCollection();
        $this->assertTrue($sut->isEmpty());
    }

    public function testCollectionIsNotEmpty()
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $this->assertFalse($sut->isEmpty());
    }

    public function isTarget()
    {
        return isset($this->target);
    }
}
