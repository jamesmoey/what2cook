<?php
namespace Moey\What2CookBundle\Tests\Collection;

use Moey\What2CookBundle\Collection\ItemCollection;
use Moey\What2CookBundle\Model\Item;
use Moey\What2CookBundle\Model\Recipe;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class ItemCollectionTest extends TestCase {
    public function testExcludeExpire() {
        $collection = new ItemCollection();
        $collection->add(
            (new Item())->setName("mixed salad")->setUseByDate(new \DateTime('2013-12-26'))
        );
        $collection->add(
            (new Item())->setName("peanut butter")->setUseByDate(new \DateTime('2014-12-26'))
        );
        $this->assertEquals(1, $collection->excludeExpired()->count());
        $this->assertEquals('peanut butter', $collection->excludeExpired()->first()->getName());
    }

    public function testCanOnlyAddItem() {
        $this->setExpectedException('\Exception');
        $collection = new ItemCollection();
        $collection->add(new Recipe());
    }
} 