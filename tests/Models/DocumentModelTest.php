<?php
/**
 * Created by David Cardenas
 * GitHub: https://github.com/dcardenascom
 * GitLab: https://gitlab.com/dcardenas
 * Site: http://dcardenas.com
 * LinkedIn: https://www.linkedin.com/in/davidcardenasguia/.
 */

namespace Lemonway\Tests\Models;

use Lemonway\Models\DocumentModel;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Class DocumentModelTest.
 */
class DocumentModelTest extends TestCase
{
    /** @var DocumentModel */
    private $documentModel;

    public function setUp()
    {
        $this->documentModel = new DocumentModel();
    }

    /**
     * @param array         $expectedArray
     * @param DocumentModel $documentModel
     * @dataProvider arrayDocumentModelProvider
     */
    public function testToArray(array $expectedArray, DocumentModel $documentModel)
    {
        $this->assertEquals($expectedArray, $documentModel->toArray());
    }

    /**
     * @param stdClass $objectFromLemonway
     * @dataProvider objectFromLemonwayProvider
     */
    public function testBindFromLemonway(stdClass $objectFromLemonway)
    {
        $this->documentModel->bindFromLemonway($objectFromLemonway);
        $this->assertEquals($objectFromLemonway->ID, $this->documentModel->getId());
        $this->assertEquals($objectFromLemonway->S, $this->documentModel->getStatus());
        $this->assertEquals($objectFromLemonway->TYPE, $this->documentModel->getType());
        $this->assertEquals($objectFromLemonway->VD, $this->documentModel->getValidityDate()->format('d/m/Y'));
    }

    /**
     * PROVIDERS.
     */

    /**
     * @return array
     */
    public function arrayDocumentModelProvider(): array
    {
        $documentModel = new DocumentModel();
        $documentModel->setType('type');
        $documentModel->setId('id');
        $documentModel->setStatus('status');
        $documentModel->setValidityDate(new \DateTime('2010-01-01'));

        $expectedArray = [
            'type'          => 'type',
            'id'            => 'id',
            'status'        => 'status',
            'validity_date' => '2010-01-01T00:00:00+00:00',
        ];

        return [
            [$expectedArray, $documentModel],
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
        $objectFromLemonway->TYPE = 'type';
        $objectFromLemonway->VD = '01/01/2010';

        return [
            [$objectFromLemonway],
        ];
    }
}
