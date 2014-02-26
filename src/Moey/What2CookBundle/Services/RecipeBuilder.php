<?php
namespace Moey\What2CookBundle\Services;

use Doctrine\Common\Collections\ArrayCollection;
use Moey\What2CookBundle\Model\Ingredient;
use Moey\What2CookBundle\Model\Recipe;

class RecipeBuilder {

    /**
     * Build Recipe from JSON string
     *
     * @param string $jsonString
     * @throws \Exception
     * @return ArrayCollection|Recipe[]
     */
    public function buildRecipe($jsonString) {
        $list = new ArrayCollection();
        $recipes = json_decode($jsonString, true);
        if ($recipes === null) {
            throw new \Exception('Recipe in Json can not be decoded');
        }
        foreach ($recipes as $recipe) {
            $r = new Recipe();
            $r->setName($recipe['name']);
            foreach ($recipe['ingredients'] as $ingredient) {
                if (($i = $r->findIngredient($ingredient['item'])) !== null) {
                    $i->setAmount($i->getAmount()+$ingredient['amount']);
                } else {
                    $i = new Ingredient();
                    $i->setName($ingredient['item'])
                        ->setAmount($ingredient['amount'])
                        ->setUnitOfMeasurement($ingredient['unit'])
                    ;
                    $r->addIngredient($i);
                }
            }
            if ($r->getIngredients()->count() === 0) {
                throw new \Exception('Recipe does not have any ingredient');
            }
            $list->add($r);
        }
        return $list;
    }
} 