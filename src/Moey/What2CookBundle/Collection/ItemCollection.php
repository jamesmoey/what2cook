<?php
namespace Moey\What2CookBundle\Collection;

use Moey\What2CookBundle\Model\Item;

class ItemCollection extends ExtendedArrayCollection {

    /**
     * Find Item with name
     * @param $name
     * @return Item|null
     */
    public function findItemByName($name) {
        foreach ($this as $item) {
            /** @var Item $item */
            if ($item->getName() === $name) {
                return $item;
            }
        }
        return null;
    }

    public function add($value) {
        if (! $value instanceof Item) {
            throw new \Exception('This collection only allow Item');
        }
        return parent::add($value);
    }

    /**
     * Exclude item that have expired
     * @return \Doctrine\Common\Collections\Collection|static
     */
    public function excludeExpired() {
        $today = new \DateTime();
        return $this->filter(function(Item $item) use ($today) {
            return $item->getUseByDate() > $today;
        });
    }
}