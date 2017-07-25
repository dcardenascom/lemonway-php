<?php
/**
 * Created by David Cardenas
 * GitHub: https://github.com/dcardenascom
 * GitLab: https://gitlab.com/dcardenas
 * Site: http://dcardenas.com
 * LinkedIn: https://www.linkedin.com/in/davidcardenasguia/.
 */

namespace Lemonway\Tests\Models;

use Lemonway\Models\IbanModel;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Class IbanModelTest.
 */
class IbanModelTest extends TestCase
{
    /** @var IbanModel */
    private $ibanModel;

    public function setUp()
    {
        $this->ibanModel = new IbanModel();
    }

    /**
     * @param array     $expectedArray
     * @param IbanModel $ibanModel
     * @dataProvider arrayIbanModel
     */
    public function testToArray(array $expectedArray, IbanModel $ibanModel)
    {
        $this->assertEquals($expectedArray, $ibanModel->toArray());
    }

    /**
     * @param stdClass $objectFromLemonway
     * @dataProvider objectFromLemonwayProvider
     */
    public function testBindFromLemonway(stdClass $objectFromLemonway)
    {
        $this->ibanModel->bindFromLemonway($objectFromLemonway);
        $this->assertEquals($objectFromLemonway->ID, $this->ibanModel->getId());
        $this->assertEquals($objectFromLemonway->S, $this->ibanModel->getStatus());
        $this->assertEquals($objectFromLemonway->DATA, $this->ibanModel->getIbanCode());
        $this->assertEquals($objectFromLemonway->SWIFT, $this->ibanModel->getSwiftCode());
        $this->assertEquals($objectFromLemonway->HOLDER, $this->ibanModel->getHolder());
    }

    /**
     * PROVIDERS.
     */

    /**
     * @return array
     */
    public function arrayIbanModel(): array
    {
        $ibanModel = new IbanModel();
        $ibanModel->setStatus('status');
        $ibanModel->setId('id');
        $ibanModel->setHolder('holder');
        $ibanModel->setIbanCode('ibanCode');
        $ibanModel->setSwiftCode('swiftCode');

        $expectedArray = [
            'status'     => 'status',
            'id'         => 'id',
            'holder'     => 'holder',
            'iban_code'  => 'ibanCode',
            'swift_code' => 'swiftCode',
        ];

        return [
            [$expectedArray, $ibanModel],
        ];
    }

    /**
     * @return array
     */
    public function objectFromLemonwayProvider(): array
    {
        $objectFromLemonway = new stdClass();
        $objectFromLemonway->ID = 'id';
        $objectFromLemonway->S = 's';
        $objectFromLemonway->DATA = 'data';
        $objectFromLemonway->SWIFT = 'swift';
        $objectFromLemonway->HOLDER = 'holder';

        return [
            [$objectFromLemonway],
        ];
    }
}
