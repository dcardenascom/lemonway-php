<?php
/**
 * Created by David Cardenas
 * GitHub: https://github.com/dcardenascom
 * GitLab: https://gitlab.com/dcardenas
 * Site: http://dcardenas.com
 * LinkedIn: https://www.linkedin.com/in/davidcardenasguia/.
 */

namespace Lemonway\Tests\Models;

use Lemonway\Exceptions\ApiException;
use Lemonway\Exceptions\ParameterNotFoundException;
use Lemonway\Models\CardModel;
use Lemonway\Models\ClientModel;
use Lemonway\Models\CredentialsModel;
use Lemonway\Models\DocumentModel;
use Lemonway\Models\IbanModel;
use Lemonway\Models\SddMandateModel;
use Lemonway\Models\WalletModel;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Class WalletModelTest.
 */
class WalletModelTest extends TestCase
{
    const TEST_USER = 'society';
    const TEST_WALLET = 'sc';
    const TEST_EMAIL = 'society@lemonway.fr';
    const TEST_PASSWORD = '123456';
    const TEST_UNIX_DATE = 1373448225;
    const TEST_URL = 'https://sandbox-api.lemonway.fr/mb/demo/dev/directkitjson2/Service.asmx';

    /** @var ClientModel */
    private $client;
    /** @var CredentialsModel */
    private $credentials;
    /** @var WalletModel */
    private $wallet;

    public function setUp()
    {
        $this->credentials = new CredentialsModel(self::TEST_USER, self::TEST_PASSWORD);
        $this->client = new ClientModel($this->credentials, self::TEST_URL);
        $this->wallet = new WalletModel();
    }

    /**
     * @param string      $id
     * @param WalletModel $expectedWallet
     * @dataProvider idAndEmailProvider
     */
    public function testPullDetailsFromLemonway(string $id, WalletModel $expectedWallet)
    {
        $this->wallet->setId($id);
        $this->wallet->setEmail('');
        $this->wallet->pullDetailsFromLemonway($this->client);
        $this->wallet->setBalance(0.00);
        $this->assertEquals($expectedWallet, $this->wallet);
        $this->assertEquals($expectedWallet->getDocuments(), $this->wallet->getDocuments());
        $this->assertEquals($expectedWallet->getEmail(), $this->wallet->getEmail());
        $this->assertEquals($expectedWallet->getName(), $this->wallet->getName());
        $this->assertEquals($expectedWallet->getBalance(), $this->wallet->getBalance());
        $this->assertEquals($expectedWallet->getId(), $this->wallet->getId());
        $this->assertEquals($expectedWallet->getCards(), $this->wallet->getCards());
        $this->assertEquals($expectedWallet->getCompanyDescription(), $this->wallet->getCompanyDescription());
        $this->assertEquals($expectedWallet->getCompanyName(), $this->wallet->getCompanyName());
        $this->assertEquals($expectedWallet->getCompanyWebsite(), $this->wallet->getCompanyWebsite());
        $this->assertEquals($expectedWallet->getFirstName(), $this->wallet->getFirstName());
        $this->assertEquals($expectedWallet->getIbanCodes(), $this->wallet->getIbanCodes());
        $this->assertEquals($expectedWallet->getLastName(), $this->wallet->getLastName());
        $this->assertEquals($expectedWallet->getLemonwayId(), $this->wallet->getLemonwayId());
        $this->assertEquals($expectedWallet->getSddMandates(), $this->wallet->getSddMandates());
        $this->assertEquals($expectedWallet->getStatus(), $this->wallet->getStatus());
        $this->assertEquals($expectedWallet->isBlocked(), $this->wallet->isBlocked());
    }

    /**
     * @param array       $expectedArray
     * @param WalletModel $walletModel
     * @dataProvider arrayWalletModelProvider
     */
    public function testToArray(array $expectedArray, WalletModel $walletModel)
    {
        $this->assertEquals($expectedArray, $walletModel->toArray());
    }

    /**
     * @param stdClass $objectFromLemonway
     * @dataProvider objectFromLemonwayProvider
     */
    public function testBindFromLemonway(stdClass $objectFromLemonway)
    {
        $this->wallet->bindFromLemonway($objectFromLemonway);
        $this->assertEquals($objectFromLemonway->d->WALLET->ID, $this->wallet->getId());
        $this->assertEquals($objectFromLemonway->d->WALLET->BAL, $this->wallet->getBalance());
        $this->assertEquals($objectFromLemonway->d->WALLET->NAME, $this->wallet->getName());
        $this->assertEquals($objectFromLemonway->d->WALLET->EMAIL, $this->wallet->getEmail());
        $this->assertEquals($objectFromLemonway->d->WALLET->STATUS, $this->wallet->getStatus());
        $this->assertEquals($objectFromLemonway->d->WALLET->BLOCKED, $this->wallet->isBlocked());
        $this->assertEquals($objectFromLemonway->d->WALLET->LWID, $this->wallet->getLemonwayId());
        $this->assertEquals($objectFromLemonway->d->WALLET->FirstName, $this->wallet->getFirstName());
        $this->assertEquals($objectFromLemonway->d->WALLET->LastName, $this->wallet->getLastName());
        $this->assertEquals($objectFromLemonway->d->WALLET->CompanyName, $this->wallet->getCompanyName());
        $this->assertEquals($objectFromLemonway->d->WALLET->CompanyDescription, $this->wallet->getCompanyDescription());
        $this->assertEquals($objectFromLemonway->d->WALLET->CompanyWebsite, $this->wallet->getCompanyWebsite());
    }

    /**
     * EXCEPTIONS.
     */
    public function testParameterNotFoundException()
    {
        $this->expectException(ParameterNotFoundException::class);
        $this->wallet->pullDetailsFromLemonway($this->client);
    }

    public function testApiException()
    {
        $client = new ClientModel($this->credentials, self::TEST_URL.'wrongUrl');
        $this->expectException(ApiException::class);
        $client->getResponseFromAPI(WalletModel::ACTION_GET_WALLET_DETAILS);
        $client->getResponseFromAPI(WalletModel::ACTION_UPDATE_WALLET_DETAILS);
    }

    /**
     * PROVIDERS.
     */

    /**
     * @return array
     */
    public function idAndEmailProvider(): array
    {
        $expectedWallet = new WalletModel();
        $expectedWallet->setId('sc');
        $expectedWallet->setBalance(0.00);
        $expectedWallet->setName('Novare Construction');
        $expectedWallet->setEmail('society@lemonway.fr');
        $expectedWallet->setStatus('-1');
        $expectedWallet->setFirstName('Prenom');
        $expectedWallet->setLastName('Nom');
        $expectedWallet->setCompanyName('Novare Construction');

        return [
            [self::TEST_WALLET, $expectedWallet],
        ];
    }

    /**
     * @return array
     */
    public function arrayWalletModelProvider(): array
    {
        $cardModel = new CardModel();
        $cardModel->setAuthor('author');
        $cardModel->setExpedition(new \DateTime('2010-01-01'));
        $cardModel->setId('id');
        $cardModel->set3ds(false);
        $cardModel->setNumber('1234567890');
        $cardModel->setType('type');

        $documentModel = new DocumentModel();
        $documentModel->setType('type');
        $documentModel->setId('id');
        $documentModel->setStatus('status');
        $documentModel->setValidityDate(new \DateTime('2010-01-01'));

        $ibanModel = new IbanModel();
        $ibanModel->setStatus('status');
        $ibanModel->setId('id');
        $ibanModel->setHolder('holder');
        $ibanModel->setIbanCode('ibanCode');
        $ibanModel->setSwiftCode('swiftCode');

        $sddMandateModel = new sddMandateModel();
        $sddMandateModel->setSwiftCode('swiftCode');
        $sddMandateModel->setIbanCode('ibanCode');
        $sddMandateModel->setId('id');
        $sddMandateModel->setStatus('status');

        $walletModel = new WalletModel();
        $walletModel->setId('id');
        $walletModel->setBalance(10.00);
        $walletModel->setName('name');
        $walletModel->setEmail('email');
        $walletModel->setStatus('status');
        $walletModel->setBlocked(false);
        $walletModel->setLemonwayId('lemonwayId');
        $walletModel->setFirstName('firstName');
        $walletModel->setLastName('lastName');
        $walletModel->setCompanyName('companyName');
        $walletModel->setCompanyDescription('companyDescription');
        $walletModel->setCompanyWebsite('companyWebsite');
        $walletModel->setCards([$cardModel]);
        $walletModel->setDocuments([$documentModel]);
        $walletModel->setIbanCodes([$ibanModel]);
        $walletModel->setSddMandates([$sddMandateModel]);

        $expectedArray = [
            'id'                  => 'id',
            'balance'             => 10.00,
            'name'                => 'name',
            'email'               => 'email',
            'status'              => 'status',
            'is_blocked'          => false,
            'lemonway_id'         => 'lemonwayId',
            'first_name'          => 'firstName',
            'last_name'           => 'lastName',
            'company_name'        => 'companyName',
            'company_description' => 'companyDescription',
            'company_website'     => 'companyWebsite',
            'cards'               => [$cardModel->toArray()],
            'documents'           => [$documentModel->toArray()],
            'iban_codes'          => [$ibanModel->toArray()],
            'sdd_mandates'        => [$sddMandateModel->toArray()],
        ];

        return [
            [$expectedArray, $walletModel],
        ];
    }

    /**
     * @return array
     */
    public function objectFromLemonwayProvider(): array
    {
        $cardFromLemonway = new stdClass();
        $cardFromLemonway->ID = 'id';
        $cardFromLemonway->EXTRA = new stdClass();
        $cardFromLemonway->EXTRA->IS3DS = false;
        $cardFromLemonway->EXTRA->AUTH = 'auth';
        $cardFromLemonway->EXTRA->NUM = 'num';
        $cardFromLemonway->EXTRA->TYP = 'typ';
        $cardFromLemonway->EXTRA->EXP = '01/01/2010';

        $documentFromLemonway = new stdClass();
        $documentFromLemonway->ID = 'id';
        $documentFromLemonway->S = 's';
        $documentFromLemonway->TYPE = 'type';
        $documentFromLemonway->VD = '01/01/2010';

        $ibanFromLemonway = new stdClass();
        $ibanFromLemonway->ID = 'id';
        $ibanFromLemonway->S = 's';
        $ibanFromLemonway->DATA = 'data';
        $ibanFromLemonway->SWIFT = 'swift';
        $ibanFromLemonway->HOLDER = 'holder';

        $sddMandateFromLemonway = new stdClass();
        $sddMandateFromLemonway->ID = 'id';
        $sddMandateFromLemonway->S = 's';
        $sddMandateFromLemonway->DATA = 'data';
        $sddMandateFromLemonway->SWIFT = 'swift';

        $walletFromLemonway = new stdClass();
        $walletFromLemonway->d = new stdClass();
        $walletFromLemonway->d->WALLET = new stdClass();
        $walletFromLemonway->d->WALLET->ID = 'id';
        $walletFromLemonway->d->WALLET->BAL = '0.58';
        $walletFromLemonway->d->WALLET->NAME = 'name';
        $walletFromLemonway->d->WALLET->EMAIL = 'email@email.com';
        $walletFromLemonway->d->WALLET->STATUS = 'statis';
        $walletFromLemonway->d->WALLET->BLOCKED = false;
        $walletFromLemonway->d->WALLET->LWID = 'lwid';
        $walletFromLemonway->d->WALLET->FirstName = 'first name';
        $walletFromLemonway->d->WALLET->LastName = 'last name';
        $walletFromLemonway->d->WALLET->CompanyName = 'company name';
        $walletFromLemonway->d->WALLET->CompanyDescription = 'company description';
        $walletFromLemonway->d->WALLET->CompanyWebsite = 'company website';
        $walletFromLemonway->d->WALLET->DOCS[] = $documentFromLemonway;
        $walletFromLemonway->d->WALLET->IBANS[] = $ibanFromLemonway;
        $walletFromLemonway->d->WALLET->SDDMANDATES[] = $sddMandateFromLemonway;
        $walletFromLemonway->d->WALLET->CARDS[] = $cardFromLemonway;

        return [
            [$walletFromLemonway],
        ];
    }
}
