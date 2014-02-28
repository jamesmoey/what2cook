<?php
namespace Moey\What2CookBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

class Recipe {
    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @var ArrayCollection|Ingredient[]
     *
     * @Assert\Valid()
     * @Assert\Count( min = "1" )
     */
    protected $ingredients;


    public function __construct() {
        $this->ingredients = new ArrayCollection();
    }

    /**
     * Add ingredient to this recipe
     *
     * @param Ingredient $ingredient
     * @return Recipe
     */
    public function addIngredient(Ingredient $ingredient)
    {
        if (!$this->ingredients->contains($ingredient)) {
            if (($i = $this->findIngredient($ingredient->getName())) !== null) {
                $i->setAmount($i->getAmount() + $ingredient->getAmount());
            } else {
                $this->ingredients->add($ingredient);
                $ingredient->setRecipe($this);
            }
        }
        return $this;
    }

    /**
     * Remove ingredient from the recipe
     *
     * @param Ingredient $ingredient
     * @return $this
     */
    public function removeIngredient(Ingredient $ingredient) {
        if ($this->ingredients->contains($ingredient)) {
            $this->ingredients->removeElement($ingredient);
            $ingredient->setRecipe(null);
        }
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection|\Moey\What2CookBundle\Model\Ingredient[]
     */
    public function getIngredients()
    {
        return $this->ingredients;
    }

    /**
     * Find the ingredient that match the name
     *
     * @param $name
     * @return Ingredient|null
     */
    public function findIngredient($name) {
        $filterList = $this->ingredients->filter(function (Ingredient $ingredient) use ($name) {
            if ($ingredient->getName() == $name) return true;
            return false;
        });
        if ($filterList->count() === 0) {
            return null;
        }
        return $filterList->first();
    }

    /**
     * @param string $name
     * @return Recipe
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}