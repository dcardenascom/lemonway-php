<?php
/**
 * Created by David Cardenas
 * GitHub: https://github.com/davidcardenasguia
 * GitLab: https://gitlab.com/dcardenas
 * Site: http://dcardenas.com
 * LinkedIn: https://www.linkedin.com/in/davidcardenasguia/.
 */

namespace Lemonway\Tests\Models;

use Lemonway\Models\CardModel;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Class CardModelTest.
 */
class CardModelTest extends TestCase
{
    /** @var CardModel */
    private $cardModel;

    public function setUp()
    {
        $this->cardModel = new CardModel();
    }

    /**
     * @param array     $expectedArray
     * @param CardModel $cardModel
     * @dataProvider arrayCardProvider
     */
    public function testToArray(array $expectedArray, CardModel $cardModel)
    {
        $this->assertEquals($expectedArray, $cardModel->toArray());
    }

    /**
     * @param stdClass $objectFromLemonway
     * @dataProvider objectFromLemonwayProvider
     */
    public function testBindFromLemonway(stdClass $objectFromLemonway)
    {
        $this->cardModel->bindFromLemonway($objectFromLemonway);
        $this->assertEquals($objectFromLemonway->ID, $this->cardModel->getId());
        $this->assertEquals($objectFromLemonway->EXTRA->IS3DS, $this->cardModel->is3ds());
        $this->assertEquals($objectFromLemonway->EXTRA->AUTH, $this->cardModel->getAuthor());
        $this->assertEquals($objectFromLemonway->EXTRA->NUM, $this->cardModel->getNumber());
        $this->assertEquals($objectFromLemonway->EXTRA->TYP, $this->cardModel->getType());
        $this->assertEquals($objectFromLemonway->EXTRA->EXP, $this->cardModel->getExpedition()->format('d/m/Y'));
    }

    /**
     * PROVIDERS.
     */

    /**
     * @return array
     */
    public function arrayCardProvider(): array
    {
        $cardModel = new CardModel();
        $cardModel->setAuthor('author');
        $cardModel->setExpedition(new \DateTime('2010-01-01'));
        $cardModel->setId('id');
        $cardModel->set3ds(false);
        $cardModel->setNumber('1234567890');
        $cardModel->setType('type');

        $expectedArray = [
            'author'     => 'author',
            'expedition' => '2010-01-01T00:00:00+00:00',
            'id'         => 'id',
            'is3ds'      => false,
            'number'     => '1234567890',
            'type'       => 'type',
        ];

        return [
            [$expectedArray, $cardModel],
        ];
    }

    /**
     * @return array
     */
    public function objectFromLemonwayProvider(): array
    {
        $objectFromLemonway = new stdClass();
        $objectFromLemonway->ID = 'id';
        $objectFromLemonway->EXTRA = new stdClass();
        $objectFromLemonway->EXTRA->IS3DS = false;
        $objectFromLemonway->EXTRA->AUTH = 'auth';
        $objectFromLemonway->EXTRA->NUM = 'num';
        $objectFromLemonway->EXTRA->TYP = 'typ';
        $objectFromLemonway->EXTRA->EXP = '01/01/2010';

        return [
            [$objectFromLemonway],
        ];
    }
}
