<?php
/**
 * Created by David Cardenas
 * GitHub: https://github.com/dcardenascom
 * GitLab: https://gitlab.com/dcardenas
 * Site: http://dcardenas.com
 * LinkedIn: https://www.linkedin.com/in/davidcardenasguia/.
 */

namespace Lemonway\Tests\Models;

use DateTime;
use Lemonway\Exceptions\ApiException;
use Lemonway\Models\ClientModel;
use Lemonway\Models\CredentialsModel;
use Lemonway\Models\WalletModel;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Class ClientModelTest.
 */
class ClientModelTest extends TestCase
{
    const TEST_USER = 'society';
    const TEST_PASSWORD = '123456';
    const TEST_UNIX_DATE = 1373448225;
    const TEST_URL = 'https://sandbox-api.lemonway.fr/mb/demo/dev/directkitjson2/Service.asmx';

    /** @var ClientModel */
    private $client;
    /** @var CredentialsModel */
    private $credentials;

    public function setUp()
    {
        $this->credentials = new CredentialsModel(self::TEST_USER, self::TEST_PASSWORD);
        $this->client = new ClientModel($this->credentials, self::TEST_URL);
    }

    /**
     * POSITIVE TESTS.
     */
    public function testCheckConnection()
    {
        $this->assertTrue($this->client->checkConnection());
    }

    public function testGetResponseFromApi()
    {
        $currentDate = new DateTime();
        $this->assertInstanceOf(
            stdClass::class,
            $this->client->getResponseFromAPI(WalletModel::ACTION_GET_KYC_STATUS, ['updateDate' => $currentDate->getTimestamp()])
        );
    }

    public function testGenerateIp()
    {
        $this->assertNotFalse(filter_var($this->client->generateIp(), FILTER_VALIDATE_IP));
    }

    /**
     * @param string   $expectedError
     * @param stdClass $content
     * @dataProvider providerExpectedErrorAndContent
     */
    public function testGetErrorsFromContent(string $expectedError, stdClass $content)
    {
        $this->assertEquals($expectedError, $this->client->getErrorsFromContent($content));
    }

    /**
     * EXCEPTION TESTS.
     */
    public function testApiExceptionError500()
    {
        $this->expectException(ApiException::class);
        $this->client->getResponseFromAPI('wrongAction');
    }

    public function testApiExceptionErrorParameterValidation()
    {
        $currentDate = new DateTime();

        $this->expectException(ApiException::class);
        $credentials = new CredentialsModel('wrongUser', 'wrongPassword');
        $client = new ClientModel($credentials, self::TEST_URL);

        $client->getResponseFromAPI(WalletModel::ACTION_GET_KYC_STATUS, ['updateDate' => $currentDate->getTimestamp()]);
    }

    /**
     * PROVIDERS.
     */

    /**
     * @return array
     */
    public function providerExpectedErrorAndContent(): array
    {
        $content1 = ['d' => ['E' => ['Msg' => 'Error name']]];
        $content2 = ['d' => ['E' => ['Error' => 'Description']]];
        $content3 = ['d' => ['E' => ['Msg' => 'Error name', 'Error' => 'Description']]];

        return [
            [
                'Error name: ',
                json_decode(json_encode($content1)),
            ],
            [
                'Description',
                json_decode(json_encode($content2)),
            ],
            [
                'Error name: Description',
                json_decode(json_encode($content3)),
            ],
        ];
    }
}
