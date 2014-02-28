<?php
namespace Moey\What2CookBundle\Tests\Model;

use Moey\What2CookBundle\Model\Ingredient;
use Moey\What2CookBundle\Model\Item;
use Moey\What2CookBundle\Model\Recipe;
use Moey\What2CookBundle\Model\RecipeChooser;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class RecipeChooserTest extends TestCase {
    public function testFoundAllIngredient() {
        $chooser = new RecipeChooser(
            (new Recipe())
                ->addIngredient((new Ingredient())->setName("banana")->setAmount(2))
                ->addIngredient((new Ingredient())->setName("bread")->setAmount(2))
        );
        $chooser->selectedItem((new Item())->setName("banana")->setAmount(3));
        $this->assertFalse($chooser->foundAllIngredient());
        $chooser->selectedItem((new Item())->setName("bread")->setAmount(2));
        $this->assertTrue($chooser->foundAllIngredient());
    }
} 