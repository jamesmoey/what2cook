<?php
namespace Moey\What2CookBundle\Services;

use Moey\What2CookBundle\Collection\ItemCollection;
use Moey\What2CookBundle\Collection\RecipeCollection;
use Moey\What2CookBundle\Exception\InvalidJsonException;
use Moey\What2CookBundle\Exception\InvalidRecipeException;
use Moey\What2CookBundle\Model\Ingredient;
use Moey\What2CookBundle\Model\Item;
use Moey\What2CookBundle\Model\Recipe;
use Symfony\Component\Validator\Validator;

class RecipeItemBuilder {

    /**
     * @var Validator
     */
    protected $validator;

    public function __construct($validator) {
        $this->validator = $validator;
    }

    /**
     * Build Recipe from JSON string
     *
     * @param string $jsonString
     * @throws \Exception
     * @return RecipeCollection|Recipe[]
     */
    public function buildRecipe($jsonString) {
        $list = new RecipeCollection();
        $recipes = json_decode($jsonString, true);
        if ($recipes === null || !is_array($recipes)) {
            throw new InvalidJsonException('Recipes in Json can not be decoded');
        }
        foreach ($recipes as $recipe) {
            $r = new Recipe();
            $r->setName($recipe['name']);
            if (isset($recipe['ingredients']) && is_array($recipe['ingredients'])) {
                foreach ($recipe['ingredients'] as $ingredient) {
                    $i = new Ingredient();
                    $i->setName($ingredient['item'])
                        ->setAmount($ingredient['amount'])
                        ->setUnitOfMeasurement($ingredient['unit'])
                    ;
                    $r->addIngredient($i);
                }
            }
            if ($r->getIngredients()->count() === 0) {
                throw new InvalidRecipeException($recipe['name'], ' does not contain any ingredient');
            }
            $error = $this->validator->validate($r, null, true, true);
            if ($error->count() > 0) {
                throw new InvalidRecipeException($recipe['name'], ' contain errors: ' . (string)$error);
            }
            $list->add($r);
        }
        return $list;
    }

    /**
     * Build Item from CSV string
     *
     * @param string $csvString
     * @throws \Exception
     * @return ItemCollection|Item[]
     */
    public function buildItem($csvString) {
        $csvString = str_replace("\r\n", "\n", $csvString);
        $csvString = str_replace("\r", "\n", $csvString);
        $list = explode("\n", $csvString);
        if (!is_array($list)) {
            throw new \Exception("No item in the csv");
        }
        $fridge = new ItemCollection();
        foreach ($list as $index=>$line) {
            $item = str_getcsv($line, ",");
            if (count($item) !== 4) {
                throw new \Exception('Item #'.$index.' has invalid number of column');
            }
            $i = new Item();
            $i->setName($item[0])
                ->setAmount($item[1])
                ->setUnitOfMeasurement($item[2])
                ->setUseByDate(\DateTime::createFromFormat("j/n/Y", $item[3]))
            ;
            $error = $this->validator->validate($i, null, true, true);
            if ($error->count() > 0) {
                throw new \Exception('Item #'.$index.' is not valid. Contain errors: ' . (string)$error);
            }
            $fridge->add($i);
        }
        return $fridge;
    }
}