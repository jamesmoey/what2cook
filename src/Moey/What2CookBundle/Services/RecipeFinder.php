<?php
namespace Moey\What2CookBundle\Services;

use Moey\What2CookBundle\Collection\ExtendedArrayCollection;
use Moey\What2CookBundle\Collection\ItemCollection;
use Moey\What2CookBundle\Collection\RecipeCollection;
use Moey\What2CookBundle\Model\Item;
use Moey\What2CookBundle\Model\Recipe;
use Moey\What2CookBundle\Model\RecipeChooser;

class RecipeFinder {

    /** @var RecipeCollection|Recipe[] */
    protected $recipes;

    /** @var ItemCollection|Item[] */
    protected $items;

    /** @var array */
    protected $choosenRecipes;

    public function __construct(RecipeCollection $recipeCollection, ItemCollection $itemCollection) {
        $this->recipes = $recipeCollection;
        $this->items = $itemCollection;
        $this->choosenRecipes = [];
    }

    /**
     * Find best matched recipe
     * @return Recipe|null
     */
    public function findRecipe() {
        /** @var Item[] $iterator */
        $iterator = $this->items->excludeExpired()->getSortedIteratorOn('useByDate');
        foreach ($iterator as $item) {
            $selectedRecipe = $this->recipes->findRecipeWithMatchingIngredient($item);
            $recipe = $this->selectRecipe($selectedRecipe,  $item);
            if ($recipe !== null) {
                return $recipe;
            }
        }
        return null;
    }

    /**
     * Select recipe with item. If found a matched recipe, return it. Otherwise null
     *
     * @param RecipeCollection $recipeCollection
     * @param Item             $item
     * @return Recipe|null
     */
    protected function selectRecipe(RecipeCollection $recipeCollection, Item $item) {
        $chosenRecipe = null;
        $recipeCollection->forAll(function ($key,
                Recipe $recipe) use (&$chosenRecipe, $item) {
            $recipeId = spl_object_hash($recipe);
            if (isset($this->choosenRecipes[$recipeId])) {
                $recipeChooser = $this->choosenRecipes[$recipeId];
            } else {
                $recipeChooser = new RecipeChooser($recipe);
                $this->choosenRecipes[$recipeId] = $recipeChooser;
            }
            $recipeChooser->selectedItem($item);
            if ($recipeChooser->foundAllIngredient()) {
                $chosenRecipe = $recipe;
                return false;
            }
            return true;
        });
        return $chosenRecipe;
    }
}