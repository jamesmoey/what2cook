<?php
namespace Moey\What2CookBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Item {

    /**
     * @var string
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @var int
     * @Assert\NotBlank()
     * @Assert\Range( min = 0 )
     */
    protected $amount;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Choice(choices = {"of", "grams", "ml", "slices"})
     */
    protected $unitOfMeasurement;

    /**
     * @var \DateTime
     * @Assert\DateTime()
     * @Assert\NotNull()
     */
    protected $useByDate;

    /**
     * @param int $amount
     * @return Item
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
     * @return Item
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
     * @return Item
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

    /**
     * @param \DateTime $useByDate
     * @return Item
     */
    public function setUseByDate($useByDate)
    {
        $this->useByDate = $useByDate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUseByDate()
    {
        return $this->useByDate;
    }
} 