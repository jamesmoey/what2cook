<?php
namespace Moey\What2CookBundle\Model;

use Moey\What2CookBundle\Collection\ItemCollection;

class RecipeChooser {

    /** @var Recipe */
    protected $recipe;

    /** @var \Moey\What2CookBundle\Collection\ItemCollection */
    protected $items;

    public function __construct(Recipe $recipe) {
        $this->recipe = $recipe;
        $this->items = new ItemCollection();
    }

    /**
     * Select item for this recipe
     * @param Item $item
     * @return $this
     */
    public function selectedItem(Item $item) {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
        }
        return $this;
    }

    /**
     * Check that all ingredient in this recipe is found.
     *
     * @return bool
     */
    public function foundAllIngredient() {
        return $this->recipe->getIngredients()->forAll(function($key, Ingredient $ingredient) {
            if ($this->items->findItemByName($ingredient->getName()) === null) {
                return false;
            } else {
                return true;
            }
        });
    }
} 