<?php
namespace Moey\What2CookBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Ingredient {
    /**
     * @var string
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @var int
     * @Assert\NotBlank()
     * @Assert\Range( min = 1 )
     */
    protected $amount;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Choice(choices = {"of", "grams", "ml", "slices"})
     */
    protected $unitOfMeasurement;

    /**
     * @var Recipe
     */
    protected $recipe;

    /**
     * @param \Moey\What2CookBundle\Model\Recipe $recipe
     * @return Ingredient
     */
    public function setRecipe($recipe)
    {
        if ($recipe !== $this->recipe && $this->recipe !== null) {
            $this->recipe->removeIngredient($this);
        }
        $this->recipe = $recipe;
        $this->recipe->addIngredient($this);
        return $this;
    }

    /**
     * @return \Moey\What2CookBundle\Model\Recipe
     */
    public function getRecipe()
    {
        return $this->recipe;
    }

    /**
     * @param int $amount
     * @return Ingredient
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param string $name
     * @return Ingredient
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

    /**
     * @param string $unitOfMeasurement
     * @return Ingredient
     */
    public function setUnitOfMeasurement($unitOfMeasurement)
    {
        $this->unitOfMeasurement = $unitOfMeasurement;
        return $this;
    }

    /**
     * @return string
     */
    public function getUnitOfMeasurement()
    {
        return $this->unitOfMeasurement;
    }
} 