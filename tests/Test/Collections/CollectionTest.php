<?php

namespace Test\Collections;

use Fi\Collections\Collection;
use Fi\Collections\Collectionable;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{

    public function test_It_Initializes()
    {
        $this->assertInstanceOf(Collection::class, new Collection());
    }

    public function test_It_contains_zero_items_on_creation()
    {
        $sut = SutFactory::getCollection();
        $this->assertEquals(0, $sut->count());
    }

    public function test_It_can_append_one_element()
    {
        $sut = SutFactory::getCollection();
        $sut->append(new class {});
        $this->assertEquals(1, $sut->count());
    }

    public function test_It_can_append_two_elements()
    {
        $sut = SutFactory::getCollection();
        $sut->append(new class {});
        $sut->append(new class {});
        $this->assertEquals(2, $sut->count());
    }

    public function test_It_can_initialize_collection_with_a_type()
    {
        $sut = new Collection(Collectionable::class);
        $this->assertInstanceOf(Collection::class, $sut);
    }

    public function test_It_does_not_store_objects_of_a_incorrect_type()
    {
        $sut = SutFactory::getTypedCollection(Collectionable::class);
        $this->expectException(\OutOfBoundsException::class);
        $sut->append(new class {});
    }

    public function test_It_can_store_based_on_interface()
    {
        $sut = SutFactory::getTypedCollection(Collectionable::class);
        $sut->append(new class implements Collectionable {});
        $this->assertEquals(1, $sut->count());
    }

    public function test_It_can_store_subclasess_of_the_type()
    {
        $sut = SutFactory::getTypedCollection(Element::class);
        $sut->append(new class extends Element {});
        $this->assertEquals(1, $sut->count());
    }
}


class Element implements Collectionable
{

}

class SutFactory {
    static public function getCollection(): Collection
    {
        return new Collection();
    }

    static public function getTypedCollection(string $type): Collection
    {
        return Collection::of($type);
    }
}