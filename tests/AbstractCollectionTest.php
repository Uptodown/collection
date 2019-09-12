<?php

namespace Uptodown\Collection\Tests;

use PHPUnit\Framework\TestCase;

class AbstractCollectionTest extends TestCase
{
    /** @test */
    public function createNewCollection()
    {
        $collection = $this->getMockCollectionOneToTen();
        $this->assertNotEmpty($collection);
    }

    /** @test */
    public function createNewCollectionWithInvalidArrayThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $collection = new MockCollection([1, 2, 3]);
    }

    /** @test */
    public function addNewElement()
    {
        $collection = $this->getMockCollectionOneToTen();
        $newElement = new MockObject(23);
        $collection->add($newElement);
        $this->assertTrue($collection->has($newElement));
    }

    /** @test */
    public function addInvalidNewElementThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $collection = $this->getMockCollectionOneToTen();
        $newElement = new \stdClass();
        $collection->add($newElement);
    }

    /** @test */
    public function removeElement()
    {
        $collection = $this->getMockCollectionOneToTen();
        $element = new MockObject(5);
        $this->assertTrue($collection->has($element));
        $collection->remove($element);
        $this->assertFalse($collection->has($element));
    }

    /** @test */
    public function removeInvalidElementThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $collection = $this->getMockCollectionOneToTen();
        $newElement = new \stdClass();
        $collection->remove($newElement);
    }

    /** @test */
    public function collectionHasAnElement()
    {
        $collection = $this->getMockCollectionOneToTen();
        $object = new MockObject(1);
        $this->assertTrue($collection->has($object));
    }

    /** @test */
    public function getLastItem()
    {
        $collection = $this->getMockCollectionOneToTen();
        $collectionLastItem = $collection->end();
        $array = $this->getArrayOneToTen();
        $arrayLastItem = end($array);
        $this->assertEquals($collectionLastItem, $arrayLastItem);
    }

    /** @test */
    public function collectionDoesntHaveAnElement()
    {
        $collection = $this->getMockCollectionOneToTen();
        $object = new MockObject(81);
        $this->assertFalse($collection->has($object));
    }

    /** @test */
    public function addArray()
    {
        $collection = $this->getMockCollectionOneToTen();
        $array = $this->getArrayElevenToTwenty();
        $collection->addArray($array);
        $this->assertEquals($collection->toArray(), array_merge($this->getArrayOneToTen(), $array));
    }

    /** @test */
    public function addInvalidArrayThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $collection = $this->getMockCollectionOneToTen();
        $collection->addArray([1, 2, 3]);
    }

    /** @test */
    public function countCollection()
    {
        $collection = $this->getMockCollectionOneToTen();
        $this->assertEquals(count($collection), count($this->getArrayOneToTen()));
    }

    /** @test */
    public function toArrayIsArrayAndReturnsAllElementsAsArray()
    {
        $collection = $this->getMockCollectionOneToTen();
        $this->assertTrue(is_array($collection->toArray()));
        $this->assertEquals($collection->toArray(), $this->getArrayOneToTen());
    }

    /** @test */
    public function map()
    {
        $collection = $this->getMockCollectionOneToTen();
        $result = $collection->map(
            function (MockObject $object) {
                return $object->value * 2;
            }
        );
        $this->assertIsArray($result);
        $this->assertCount($collection->count(), $result);
        foreach ($collection->getIterator() as $index => $element) {
            $this->assertEquals($element->value * 2, $result[$index]);
        }
    }

    /** @test */
    public function mapWithEmptyCollection()
    {
        $collection = new MockCollection();
        $result = $collection->map(
            function (MockObject $object) {
                return $object->value * 2;
            }
        );
        $this->assertIsArray($result);
        $this->assertCount($collection->count(), $result);
    }

    /** @test */
    public function filter()
    {
        $collection = $this->getMockCollectionOneToTen();
        $previousCount = $collection->count();
        $collection->filter(
            function (MockObject $object) {
                return $object->value <= 5;
            }
        );
        $this->assertCount($previousCount/2, $collection);
        foreach ($collection->getIterator() as $element) {
            $this->assertLessThanOrEqual(5, $element->value);
        }
    }

    private function getMockCollectionOneToTen() // : MockCollection
    {
        return new MockCollection($this->getArrayOneToTen());
    }

    private function getArrayOneToTen() // : array
    {
        for ($value = 1;$value <= 10;$value++) {
            $objects[] = new MockObject($value);
        }
        return $objects;
    }

    private function getArrayElevenToTwenty() // : array
    {
        for ($value = 1;$value <= 10;$value++) {
            $objects[] = new MockObject($value);
        }
        return $objects;
    }
}
