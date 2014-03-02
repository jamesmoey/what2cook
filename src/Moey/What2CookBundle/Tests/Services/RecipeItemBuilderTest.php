<?php
namespace Moey\What2CookBundle\Tests\Services;

use Moey\What2CookBundle\Services\RecipeItemBuilder;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RecipeItemBuilderTest extends WebTestCase {

    protected $validator;

    /** @var RecipeItemBuilder */
    protected $builder;

    protected function setUp()
    {
        $this->validator = self::createClient()->getContainer()->get("validator");
        $this->builder = new RecipeItemBuilder($this->validator);
    }


    public function testBuildRecipeFromJson() {
        $list = $this->builder->buildRecipe('[
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
        $this->setExpectedException('Moey\What2CookBundle\Exception\InvalidJsonException');
        $this->builder->buildRecipe('[
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
        $this->setExpectedException('Moey\What2CookBundle\Exception\InvalidRecipeException');
        $this->builder->buildRecipe('[
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
        $list = $this->builder->buildRecipe('[
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

    public function testBuildItemFromCsv() {
        $list = $this->builder->buildItem("bread,10,slices,25/12/2014
cheese,10,slices,25/12/2014
butter,250,grams,25/12/2014
peanut butter,250,grams,2/12/2014
mixed salad,150,grams,26/12/2013");
        $this->assertEquals(5, $list->count());
    }

    public function testBuildItemFromCsvMissingColumn() {
        $this->setExpectedException('\Exception');
        $list = $this->builder->buildItem("bread,10,25/12/2014");
    }

    public function testBuildItemFromCsvInvalidUnit() {
        $this->setExpectedException('\Exception');
        $list = $this->builder->buildItem("bread,10,slice,25/12/2014");
    }
}
 