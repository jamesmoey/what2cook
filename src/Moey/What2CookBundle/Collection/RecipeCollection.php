<?php
namespace Moey\What2CookBundle\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Moey\What2CookBundle\Model\Item;
use Moey\What2CookBundle\Model\Recipe;

class RecipeCollection extends ExtendedArrayCollection {
    public function add($value) {
        if (! $value instanceof Recipe) {
            throw new \Exception('This collection only allow Recipe');
        }
        return parent::add($value);
    }

    /**
     * Find recipes that match the ingredient $item
     * @param Item $item
     * @return RecipeCollection|Recipe[]
     */
    public function findRecipeWithMatchingIngredient(Item $item) {
        return $this->filter(function(Recipe $recipe) use ($item) {
            if (($ingredient = $recipe->findIngredient($item->getName())) !== null) {
                return ($ingredient->getAmount() <= $item->getAmount());
            }
            return false;
        });
    }
} 