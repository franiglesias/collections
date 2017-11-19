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
        $sut = $this->getCollection();
        $this->assertInstanceOf(Collection::class, $sut);
    }

    public function test_It_does_not_store_objects_of_a_incorrect_type()
    {
        $sut = $this->getCollection();
        $this->expectException(\UnexpectedValueException::class);
        $sut->append(new class {});
    }

    public function test_It_can_store_subclasess_of_the_type()
    {
        $sut = $this->getCollection();
        $sut->append(new class extends CollectionTest {});
        $this->assertEquals(1, $sut->count());
    }

    private function getCollection(): Collection
    {
        return Collection::of(get_class($this));
    }
}
