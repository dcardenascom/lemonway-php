<?php
/**
 * Created by David Cardenas
 * GitHub: https://github.com/davidcardenasguia
 * GitLab: https://gitlab.com/dcardenas
 * Site: http://dcardenas.com
 * LinkedIn: https://www.linkedin.com/in/davidcardenasguia/.
 */

namespace Lemonway\Models;

use Lemonway\Exceptions\ParameterNotFoundException;
use stdClass;

/**
 * Class WalletModel.
 */
class WalletModel extends LemonwayObjectModel
{
    const ACTION_GET_WALLET_DETAILS = 'GetWalletDetails';
    const ACTION_GET_KYC_STATUS = 'GetKycStatus';

    /** @var string */
    private $id = '';
    /** @var float */
    private $balance = 0.00;
    /** @var string */
    private $name = '';
    /** @var string */
    private $email = '';
    /** @var array */
    private $documents = [];
    /** @var array */
    private $ibanCodes = [];
    /** @var string */
    private $status = '';
    /** @var bool */
    private $blocked = false;
    /** @var array */
    private $sddMandates = [];
    /** @var string */
    private $lemonwayId = '';
    /** @var array */
    private $cards = [];
    /** @var string */
    private $firstName = '';
    /** @var string */
    private $lastName = '';
    /** @var string */
    private $companyName = '';
    /** @var string */
    private $companyDescription = '';
    /** @var string */
    private $companyWebsite = '';

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return float
     */
    public function getBalance(): float
    {
        return round($this->balance, 2);
    }

    /**
     * @param float $balance
     */
    public function setBalance(float $balance)
    {
        $this->balance = round($balance, 2);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return array
     */
    public function getDocuments(): array
    {
        return $this->documents;
    }

    /**
     * @param array $documents
     */
    public function setDocuments(array $documents)
    {
        $this->documents = $documents;
    }

    /**
     * @return array
     */
    public function getIbanCodes(): array
    {
        return $this->ibanCodes;
    }

    /**
     * @param array $ibanCodes
     */
    public function setIbanCodes(array $ibanCodes)
    {
        $this->ibanCodes = $ibanCodes;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    /**
     * @return bool
     */
    public function isBlocked(): bool
    {
        return $this->blocked;
    }

    /**
     * @param bool $blocked
     */
    public function setBlocked(bool $blocked)
    {
        $this->blocked = $blocked;
    }

    /**
     * @return array
     */
    public function getSddMandates(): array
    {
        return $this->sddMandates;
    }

    /**
     * @param array $sddMandates
     */
    public function setSddMandates(array $sddMandates)
    {
        $this->sddMandates = $sddMandates;
    }

    /**
     * @return string
     */
    public function getLemonwayId(): string
    {
        return $this->lemonwayId;
    }

    /**
     * @param string $lemonwayId
     */
    public function setLemonwayId(string $lemonwayId)
    {
        $this->lemonwayId = $lemonwayId;
    }

    /**
     * @return array
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    /**
     * @param array $cards
     */
    public function setCards(array $cards)
    {
        $this->cards = $cards;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getCompanyName(): string
    {
        return $this->companyName;
    }

    /**
     * @param string $companyName
     */
    public function setCompanyName(string $companyName)
    {
        $this->companyName = $companyName;
    }

    /**
     * @return string
     */
    public function getCompanyDescription(): string
    {
        return $this->companyDescription;
    }

    /**
     * @param string $companyDescription
     */
    public function setCompanyDescription(string $companyDescription)
    {
        $this->companyDescription = $companyDescription;
    }

    /**
     * @return string
     */
    public function getCompanyWebsite(): string
    {
        return $this->companyWebsite;
    }

    /**
     * @param string $companyWebsite
     */
    public function setCompanyWebsite(string $companyWebsite)
    {
        $this->companyWebsite = $companyWebsite;
    }

    public function pullDetailsFromLemonway(ClientModel $clientModel): bool
    {
        if ('' === $this->getId()) {
            throw new ParameterNotFoundException('Please provide wallet id');
        }

        $parameters = ['wallet' => $this->getId()];

        $this->resetValues();
        $this->bindFromLemonway($clientModel->getResponseFromAPI(self::ACTION_GET_WALLET_DETAILS, $parameters));

        return true;
    }

    /**
     * @param stdClass $object
     *
     * @return bool
     */
    public function bindFromLemonway(stdClass $object): bool
    {
        $map = [
            'ID'                 => 'setId',
            'BAL'                => 'setBalance',
            'NAME'               => 'setName',
            'EMAIL'              => 'setEmail',
            'STATUS'             => 'setStatus',
            'BLOCKED'            => 'setBlocked',
            'LWID'               => 'setLemonwayId',
            'FirstName'          => 'setFirstName',
            'LastName'           => 'setLastName',
            'CompanyName'        => 'setCompanyName',
            'CompanyDescription' => 'setCompanyDescription',
            'CompanyWebsite'     => 'setCompanyWebsite',
        ];

        foreach ($map as $key => $method) {
            if (is_string($object->d->WALLET->{$key})) {
                eval('$this->'.$method.'($object->d->WALLET->'.$key.')'.';');
            }
        }

        if (is_array($object->d->WALLET->DOCS)) {
            foreach ($object->d->WALLET->DOCS as $documentObject) {
                $documentModel = new DocumentModel();
                $documentModel->bindFromLemonway($documentObject);
                $this->documents[] = $documentModel;
            }
        }

        if (is_array($object->d->WALLET->IBANS)) {
            foreach ($object->d->WALLET->IBANS as $ibanObject) {
                $ibanModel = new IbanModel();
                $ibanModel->bindFromLemonway($ibanObject);
                $this->ibanCodes[] = $ibanModel;
            }
        }

        if (is_array($object->d->WALLET->SDDMANDATES)) {
            foreach ($object->d->WALLET->SDDMANDATES as $mandateObject) {
                $sddMandateModel = new SddMandateModel();
                $sddMandateModel->bindFromLemonway($mandateObject);
                $this->sddMandates[] = $sddMandateModel;
            }
        }

        if (is_array($object->d->WALLET->CARDS)) {
            foreach ($object->d->WALLET->CARDS as $cardObject) {
                $cardModel = new CardModel();
                $cardModel->bindFromLemonway($cardObject);
                $this->cards[] = $cardModel;
            }
        }

        return true;
    }

    /**
     * @return bool
     */
    private function resetValues(): bool
    {
        $this->setId('');
        $this->setBalance(0.00);
        $this->setName('');
        $this->setEmail('');
        $this->setDocuments([]);
        $this->setIbanCodes([]);
        $this->setStatus('');
        $this->setBlocked(false);
        $this->setSddMandates([]);
        $this->setLemonwayId('');
        $this->setCards([]);
        $this->setFirstName('');
        $this->setLastName('');
        $this->setCompanyName('');
        $this->setCompanyDescription('');
        $this->setCompanyWebsite('');

        return true;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $arrayToReturn = [
            'id'                  => $this->getId(),
            'balance'             => $this->getBalance(),
            'name'                => $this->getName(),
            'email'               => $this->getEmail(),
            'status'              => $this->getStatus(),
            'is_blocked'          => $this->isBlocked(),
            'lemonway_id'         => $this->getLemonwayId(),
            'first_name'          => $this->getFirstName(),
            'last_name'           => $this->getLastName(),
            'company_name'        => $this->getCompanyName(),
            'company_description' => $this->getCompanyDescription(),
            'company_website'     => $this->getCompanyWebsite(),
        ];

        /** @var DocumentModel $document */
        foreach ($this->getDocuments() as $document) {
            $arrayToReturn['documents'][] = $document->toArray();
        }

        /** @var IbanModel $ibanCode */
        foreach ($this->getIbanCodes() as $ibanCode) {
            $arrayToReturn['iban_codes'][] = $ibanCode->toArray();
        }

        /** @var SddMandateModel $sddMandate */
        foreach ($this->getSddMandates() as $sddMandate) {
            $arrayToReturn['sdd_mandates'][] = $sddMandate->toArray();
        }

        /** @var CardModel $card */
        foreach ($this->getCards() as $card) {
            $arrayToReturn['cards'][] = $card->toArray();
        }

        return $arrayToReturn;
    }
}
