<?php
namespace Moey\What2CookBundle\Tests\Services;

use Moey\What2CookBundle\Collection\ItemCollection;
use Moey\What2CookBundle\Collection\RecipeCollection;
use Moey\What2CookBundle\Model\Ingredient;
use Moey\What2CookBundle\Model\Item;
use Moey\What2CookBundle\Model\Recipe;
use Moey\What2CookBundle\Services\RecipeFinder;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class RecipeFinderTest extends TestCase {
    public function testFindRecipeBasedOnUseByDate() {
        $finder = new RecipeFinder(
            new RecipeCollection([
                (new Recipe())->setName("banana bread")
                    ->addIngredient((new Ingredient())->setAmount(2)->setName("banana"))
                    ->addIngredient((new Ingredient())->setAmount(1)->setName("bread")),
                (new Recipe())->setName("pear bread")
                    ->addIngredient((new Ingredient())->setAmount(2)->setName("pear"))
                    ->addIngredient((new Ingredient())->setAmount(1)->setName("bread")),
                (new Recipe())->setName("apple bread")
                    ->addIngredient((new Ingredient())->setAmount(2)->setName("pear"))
                    ->addIngredient((new Ingredient())->setAmount(1)->setName("bread")),
            ]),
            new ItemCollection([
                (new Item())->setName("apple")->setAmount(2)->setUseByDate(new \DateTime("-1 day")),
                (new Item())->setName("pear")->setAmount(2)->setUseByDate(new \DateTime("+1 day")),
                (new Item())->setName("banana")->setAmount(2)->setUseByDate(new \DateTime("+10 day")),
                (new Item())->setName("bread")->setAmount(2)->setUseByDate(new \DateTime("+10 day")),
            ])
        );
        $this->assertNotNull($finder->findRecipe());
        $this->assertEquals("pear bread", $finder->findRecipe()->getName());
    }

    public function testFindRecipeInvalidAmount() {
        $finder = new RecipeFinder(
            new RecipeCollection([
                (new Recipe())->setName("banana bread")
                    ->addIngredient((new Ingredient())->setAmount(2)->setName("banana"))
                    ->addIngredient((new Ingredient())->setAmount(1)->setName("bread")),
                (new Recipe())->setName("pear bread")
                    ->addIngredient((new Ingredient())->setAmount(2)->setName("pear"))
                    ->addIngredient((new Ingredient())->setAmount(1)->setName("bread")),
                (new Recipe())->setName("apple bread")
                    ->addIngredient((new Ingredient())->setAmount(2)->setName("pear"))
                    ->addIngredient((new Ingredient())->setAmount(1)->setName("bread")),
            ]),
            new ItemCollection([
                (new Item())->setName("apple")->setAmount(2)->setUseByDate(new \DateTime("-1 day")),
                (new Item())->setName("pear")->setAmount(1)->setUseByDate(new \DateTime("+1 day")),
                (new Item())->setName("banana")->setAmount(2)->setUseByDate(new \DateTime("+10 day")),
                (new Item())->setName("bread")->setAmount(2)->setUseByDate(new \DateTime("+10 day")),
            ])
        );
        $this->assertNotNull($finder->findRecipe());
        $this->assertEquals("banana bread", $finder->findRecipe()->getName());
    }

    public function testFindRecipeNoRecipe() {
        $finder = new RecipeFinder(
            new RecipeCollection([
                (new Recipe())->setName("banana bread")
                    ->addIngredient((new Ingredient())->setAmount(2)->setName("banana"))
                    ->addIngredient((new Ingredient())->setAmount(1)->setName("bread")),
                (new Recipe())->setName("pear bread")
                    ->addIngredient((new Ingredient())->setAmount(2)->setName("pear"))
                    ->addIngredient((new Ingredient())->setAmount(1)->setName("bread")),
                (new Recipe())->setName("apple bread")
                    ->addIngredient((new Ingredient())->setAmount(2)->setName("pear"))
                    ->addIngredient((new Ingredient())->setAmount(1)->setName("bread")),
            ]),
            new ItemCollection([
                (new Item())->setName("apple")->setAmount(2)->setUseByDate(new \DateTime("-1 day")),
                (new Item())->setName("pear")->setAmount(1)->setUseByDate(new \DateTime("+1 day")),
                (new Item())->setName("banana")->setAmount(1)->setUseByDate(new \DateTime("+10 day")),
                (new Item())->setName("bread")->setAmount(2)->setUseByDate(new \DateTime("+10 day")),
            ])
        );
        $this->assertNull($finder->findRecipe());
    }
}
 