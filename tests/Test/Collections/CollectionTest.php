<?php

namespace Test\Collections;

use Fi\Collections\Collection;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    public function test_It_Initializes()
    {
        $this->assertInstanceOf(Collection::class, $this->getCollection());
    }

    private function getCollection() : Collection
    {
        return Collection::of(get_class($this));
    }

    public function test_It_contains_zero_items_on_creation()
    {
        $sut = $this->getCollection();
        $this->assertEquals(0, $sut->count());
    }

    public function test_It_can_append_one_element()
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $this->assertEquals(1, $sut->count());
    }

    public function test_It_can_append_two_elements()
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $sut->append($this);
        $this->assertEquals(2, $sut->count());
    }

    public function test_It_can_initialize_collection_with_a_type()
    {
        $sut = Collection::of(CollectionTest::class);
        $this->assertInstanceOf(Collection::class, $sut);
    }

    public function test_It_does_not_store_objects_of_a_incorrect_type()
    {
        $sut = $this->getCollection();
        $this->expectException(\UnexpectedValueException::class);
        $sut->append(new class
        {
        });
    }

    public function test_It_can_store_subclasess_of_the_type()
    {
        $sut = $this->getCollection();
        $sut->append(new class extends CollectionTest
        {
        });
        $this->assertEquals(1, $sut->count());
    }

    public function test_Each_does_nothing_on_empty_collection()
    {
        $sut = $this->getCollection();
        $log = '';
        $sut->each(function () use (&$log) {
            $log .= '*';
        });
        $this->assertEquals('', $log);
    }

    public function test_Each_can_iterate_one_element()
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $log = '';
        $sut->each(function () use (&$log) {
            $log .= '*';
        });
        $this->assertEquals('*', $log);
    }

    public function test_Each_can_iterate_two_elements()
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

    public function test_Each_element_is_passed_to_function()
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

    public function test_Each_method_allows_pipeline()
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $log = '';
        $result = $sut->each(function (CollectionTest $element) use (&$log) {
            $log .= '*';
        });
        $this->assertInstanceOf(Collection::class, $result);
    }

    public function test_Each_method_on_empty_Collection_allows_pipeline()
    {
        $sut = $this->getCollection();
        $log = '';
        $result = $sut->each(function (CollectionTest $element) use (&$log) {
            $log .= '*';
        });
        $this->assertInstanceOf(Collection::class, $result);
    }

    public function test_Map_method_on_empty_Collection_allows_pipeline()
    {
        $sut = $this->getCollection();
        $result = $sut->map(function (CollectionTest $element) {
            return $element;
        });
        $this->assertInstanceOf(Collection::class, $result);
    }

    public function test_Map_method_on_empty_Collection_returns_empty_collection()
    {
        $sut = $this->getCollection();
        $result = $sut->map(function (CollectionTest $element) {
            return $element;
        });
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEquals(0, $result->count());
    }

    public function test_Map_method_returns_another_collection()
    {
        $sut = $this->getCollection();
        $result = $sut->map(function (CollectionTest $element) {
            return $element;
        });
        $this->assertNotSame($sut, $result);
    }

    public function test_Map_can_map_one_element()
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $result = $sut->map(function (CollectionTest $element) {
            return new self();
        });
        $this->assertEquals(self::class, $result->getType());
        $this->assertEquals(1, $result->count());
    }

    public function test_Map_can_map_two_elements()
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

    public function test_Filter_returns_a_Collection()
    {
        $sut = $this->getCollection();
        $result = $sut->filter(function (CollectionTest $element) {
            return false;
        });
        $this->assertInstanceOf(Collection::class, $result);
    }

    public function test_Filter_returns_a_Collection_that_is_not_the_same()
    {
        $sut = $this->getCollection();
        $result = $sut->filter(function (CollectionTest $element) {
            return false;
        });
        $this->assertNotSame($sut, $result);
    }

    public function test_Filter_returns_a_Collection_with_the_same_type_of_objects()
    {
        $sut = $this->getCollection();
        $result = $sut->filter(function (CollectionTest $element) {
            return false;
        });
        $this->assertEquals(CollectionTest::class, $result->getType());
    }

    public function test_Filter_include_element_if_filter_function_returns_true()
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $result = $sut->filter(function (CollectionTest $element) {
            return true;
        });
        $this->assertEquals(1, $result->count());
    }

    public function test_Filter_does_not_include_element_if_filter_function_returns_false()
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $result = $sut->filter(function (CollectionTest $element) {
            return false;
        });
        $this->assertEquals(0, $result->count());
    }

    public function test_Filter_iterates_all_elements_in_collection()
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $sut->append(clone $this);
        $result = $sut->filter(function (CollectionTest $element) {
            return true;
        });
        $this->assertEquals($sut, $result);
    }

    public function test_GetBy_throws_exception_on_empty_collection()
    {
        $sut = $this->getCollection();
        $this->expectException(\UnderflowException::class);
        $sut->getBy(function (CollectionTest $element) {
            return true;
        });
    }

    public function test_GetBy_throws_exception_if_element_is_not_found()
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $this->expectException(\OutOfBoundsException::class);
        $sut->getBy(function (CollectionTest $element) {
            return false;
        });
    }

    public function test_GetBy_returns_element_if_found()
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $result = $sut->getBy(function (CollectionTest $element) {
            return true;
        });
        $this->assertSame($this, $result);
    }

    public function test_GetBy_selects_the_right_element()
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

    public function test_Reduce_returns_initial_value_for_empty_collection()
    {
        $sut = $this->getCollection();
        $result = $sut->reduce(function (CollectionTest $element, $acumulator) {
           return $acumulator + 1;
        }, 0);
        $this->assertEquals(0, $result);
    }

    public function test_Reduce_initial_can_be_any_type()
    {
        $sut = $this->getCollection();
        $result = $sut->reduce(function (CollectionTest $element, $acumulator) {
            return $acumulator + 1;
        }, "");
        $this->assertEquals("", $result);
    }

    public function test_Reduce_applies_reduce_function_to_one_element()
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $result = $sut->reduce(function (CollectionTest $element, $acumulator) {
            return $acumulator + 1;
        }, 0);
        $this->assertEquals(1, $result);
    }

    public function test_Reduce_applies_reduce_function_to_several_elements()
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $sut->append($this);
        $result = $sut->reduce(function (CollectionTest $element, $acumulator) {
            return $acumulator + 1;
        }, 0);
        $this->assertEquals(2, $result);
    }

    public function test_Collect_array_uses_first_element_to_instance_collection()
    {
        $sut = Collection::collect([
            $this
        ]);
        $this->assertEquals(CollectionTest::class, $sut->getType());
    }

    public function test_Collect_empty_array_fails_with_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        Collection::collect([]);
    }

    public function test_Collect_array_with_one_element_populates_collection()
    {
        $sut = Collection::collect([
            $this
        ]);
        $this->assertEquals(1, $sut->count());
    }

    public function test_Collect_array_with_several_elements_populates_collection()
    {
        $sut = Collection::collect([
            $this,
            $this
        ]);
        $this->assertEquals(2, $sut->count());
    }

    public function test_Invalid_type_in_array_throws_exception()
    {
        $this->expectException(\UnexpectedValueException::class);
        Collection::collect([
            $this,
            new \stdClass()
        ]);
    }

    public function test_Empty_Collection_maps_to_empty_array()
    {
        $sut = $this->getCollection();
        $this->assertEquals([], $sut->toArray());
    }

    public function test_Collection_can_be_returned_as_array()
    {
        $sample = [$this];
        $sut = Collection::collect($sample);
        $this->assertEquals($sample, $sut->toArray());
    }

    public function test_Collection_can_be_mapped_to_array()
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $this->assertEquals(['mapped'], $sut->toArray(function(CollectionTest $element) {
            return 'mapped';
        }));
    }

    public function test_Collection_with_two_elements_can_be_mapped_to_array()
    {
        $sut = $this->getCollection();
        $sut->append($this);
        $sut->append($this);
        $this->assertEquals(['mapped', 'mapped'], $sut->toArray(function(CollectionTest $element) {
            return 'mapped';
        }));
    }

    public function test_Collection_getType()
    {
        $sut = Collection::of(CollectionTest::class);
        $this->assertEquals(CollectionTest::class, $sut->getType());
    }

    public function test_Collection_is_empty()
    {
        $sut = $this->getCollection();
        $this->assertTrue($sut->isEmpty());
    }

    public function test_Collection_is_not_empty()
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
