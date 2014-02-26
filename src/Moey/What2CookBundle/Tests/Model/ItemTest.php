<?php
namespace Moey\What2CookBundle\Tests\Model;

use Moey\What2CookBundle\Model\Item;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\Validator;

class ItemTest extends WebTestCase {

    /** @var Validator */
    protected $validator;

    protected function setUp()
    {
        $this->validator = self::createClient()->getContainer()->get("validator");
    }

    public function testItemValidationSuccess() {
        $item = new Item();
        $item->setName("banana")
            ->setAmount(1)
            ->setUnitOfMeasurement("of")
            ->setUseByDate(new \DateTime('+5 days'));
        $error = $this->validator->validate($item);
        $this->assertEquals(0, $error->count());
    }

    public function testItemValidateUsedByDate() {
        $item = new Item();
        $item->setName("banana")
            ->setAmount(1)
            ->setUnitOfMeasurement("of");
        $error = $this->validator->validate($item);
        $this->assertEquals(1, $error->count());
    }

    public function testItemValidateUnitOfMeasurement() {
        $item = new Item();
        $item->setName("banana")
            ->setAmount(1)
            ->setUnitOfMeasurement("piece")
            ->setUseByDate(new \DateTime('+5 days'));
        $error = $this->validator->validate($item);
        $this->assertEquals(1, $error->count());
    }

    public function testItemValidateAmount() {
        $item = new Item();
        $item->setName("banana")
            ->setAmount(-1)
            ->setUnitOfMeasurement("of")
            ->setUseByDate(new \DateTime('+5 days'));
        $error = $this->validator->validate($item);
        $this->assertEquals(1, $error->count());
    }
}
 