<?php
namespace Moey\What2CookBundle\Tests\Model;

use Moey\What2CookBundle\Model\Ingredient;
use Moey\What2CookBundle\Model\Recipe;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\Validator;

class RecipeTest extends WebTestCase {

    /** @var Validator */
    protected $validator;

    protected function setUp()
    {
        $this->validator = self::createClient()->getContainer()->get("validator");
    }

    public function testRecipeMustHaveIngredient() {
        $recipe = new Recipe();
        $recipe->setName("banana burger");
        $error = $this->validator->validate($recipe);
        $this->assertEquals(1, $error->count());
        $recipe->addIngredient(
            (new Ingredient())->setName("banana")->setAmount(1)->setUnitOfMeasurement("of")
        );
        $error = $this->validator->validate($recipe);
        $this->assertEquals(0, $error->count());
    }

    public function testRecipeMustHaveValidIngredient() {
        $recipe = new Recipe();
        $recipe
            ->setName("banana burger")
            ->addIngredient(
                (new Ingredient())->setName("banana")->setAmount(1)->setUnitOfMeasurement("piece")
            )
        ;
        $error = $this->validator->validate($recipe, null, true, true);
        $this->assertEquals(1, $error->count());
    }

    public function testFindIngredient() {
        $recipe = new Recipe();
        $recipe->setName("banana burger");
        $error = $this->validator->validate($recipe);
        $this->assertEquals(1, $error->count());
        $recipe->addIngredient(
            (new Ingredient())->setName("banana")->setAmount(1)->setUnitOfMeasurement("of")
        );
        $this->assertNotNull($recipe->findIngredient("banana"));
        $this->assertNull($recipe->findIngredient("bananas"));
    }
}
 