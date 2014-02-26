<?php
namespace Moey\What2CookBundle\Tests\Services;

use Moey\What2CookBundle\Services\RecipeBuilder;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class RecipeBuilderTest extends TestCase {
    public function testBuildRecipeFromJson() {
        $builder = new RecipeBuilder();
        $list = $builder->buildRecipe('[
            {
                "name": "grilled cheese on toast",
                "ingredients": [
                    { "item":"bread", "amount":"2", "unit":"slices"},
                    { "item":"cheese", "amount":"2", "unit":"slices"}
                ]
            }
            ,
            {
                "name": "salad sandwich",
                "ingredients": [
                    { "item":"bread", "amount":"2", "unit":"slices"},
                    { "item":"mixed salad", "amount":"100", "unit":"grams"}
                ]
            }
        ]');
        $this->assertEquals(2, $list->count());
        $this->assertEquals('grilled cheese on toast', $list[0]->getName());
        $this->assertEquals('salad sandwich', $list[1]->getName());
        $this->assertEquals(2, $list[0]->getIngredients()->count());
        $this->assertEquals(2, $list[1]->getIngredients()->count());
    }

    public function testBuildInvalidJson() {
        $this->setExpectedException('\Exception');
        $builder = new RecipeBuilder();
        $builder->buildRecipe('[
            {
                "name": "grilled cheese on toast",
                "ingredients": [
                    { "item":"bread", "amount":"2", "unit":"slices"},
                    { "item":"cheese", "amount":"2", "unit":"slices"}
                ]
            }
            ,
            {
                "name": "salad sandwich",
                "ingredients": [
                    { "item":"bread", "amount":"2", "unit":"slices"},
                    { "item":"mixed salad", "amount":"100", "unit":"grams"}
                ]
            }
        ');
    }

    public function testBuildEmptyRecipe() {
        $this->setExpectedException('\Exception');
        $builder = new RecipeBuilder();
        $builder->buildRecipe('[
            {
                "name": "grilled cheese on toast",
                "ingredients": [
                    { "item":"bread", "amount":"2", "unit":"slices"},
                    { "item":"cheese", "amount":"2", "unit":"slices"}
                ]
            }
            ,
            {
                "name": "salad sandwich",
                "ingredients": [
                ]
            }
        ]');
    }

    public function testBuildDuplicateIngredientRecipe() {
        $builder = new RecipeBuilder();
        $list = $builder->buildRecipe('[
            {
                "name": "grilled cheese on toast",
                "ingredients": [
                    { "item":"bread", "amount":"2", "unit":"slices"},
                    { "item":"cheese", "amount":"2", "unit":"slices"}
                ]
            }
            ,
            {
                "name": "salad sandwich",
                "ingredients": [
                    { "item":"bread", "amount":"2", "unit":"slices"},
                    { "item":"mixed salad", "amount":"100", "unit":"grams"},
                    { "item":"bread", "amount":"2", "unit":"slices"}
                ]
            }
        ]');
        $this->assertEquals(2, $list[1]->getIngredients()->count());

    }
}
 