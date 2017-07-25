<?php
/**
 * Created by David Cardenas
 * GitHub: https://github.com/dcardenascom
 * GitLab: https://gitlab.com/dcardenas
 * Site: http://dcardenas.com
 * LinkedIn: https://www.linkedin.com/in/davidcardenasguia/.
 */

namespace Lemonway\Tests\Models;

use Lemonway\Models\SddMandateModel;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Class SddMandateModelTest.
 */
class SddMandateModelTest extends TestCase
{
    /** @var SddMandateModel */
    private $sddMandateModel;

    public function setUp()
    {
        $this->sddMandateModel = new SddMandateModel();
    }

    /**
     * @param array           $expectedArray
     * @param SddMandateModel $sddMandateModel
     * @dataProvider arraySddMandateModel
     */
    public function testToArray(array $expectedArray, SddMandateModel $sddMandateModel)
    {
        $this->assertEquals($expectedArray, $sddMandateModel->toArray());
    }

    /**
     * @param stdClass $objectFromLemonway
     * @dataProvider objectFromLemonwayProvider
     */
    public function testBindFromLemonway(stdClass $objectFromLemonway)
    {
        $this->sddMandateModel->bindFromLemonway($objectFromLemonway);
        $this->assertEquals($objectFromLemonway->ID, $this->sddMandateModel->getId());
        $this->assertEquals($objectFromLemonway->S, $this->sddMandateModel->getStatus());
        $this->assertEquals($objectFromLemonway->DATA, $this->sddMandateModel->getIbanCode());
        $this->assertEquals($objectFromLemonway->SWIFT, $this->sddMandateModel->getSwiftCode());
    }

    /**
     * PROVIDERS.
     */

    /**
     * @return array
     */
    public function arraySddMandateModel(): array
    {
        $sddMandateModel = new sddMandateModel();
        $sddMandateModel->setSwiftCode('swiftCode');
        $sddMandateModel->setIbanCode('ibanCode');
        $sddMandateModel->setId('id');
        $sddMandateModel->setStatus('status');

        $expectedArray = [
            'swift_code' => 'swiftCode',
            'iban_code'  => 'ibanCode',
            'id'         => 'id',
            'status'     => 'status',
        ];

        return [
            [$expectedArray, $sddMandateModel],
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

        return [
            [$objectFromLemonway],
        ];
    }
}
