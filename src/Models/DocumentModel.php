<?php
/**
 * Created by David Cardenas
 * GitHub: https://github.com/dcardenascom
 * GitLab: https://gitlab.com/dcardenas
 * Site: http://dcardenas.com
 * LinkedIn: https://www.linkedin.com/in/davidcardenasguia/.
 */

namespace Lemonway\Models;

use DateTime;
use Lemonway\Exceptions\ParameterNotFoundException;
use stdClass;

/**
 * Class DocumentModel.
 */
class DocumentModel extends LemonwayObjectModel
{
    const TYPE_ID = '0';
    const TYPE_ADDRESS = '1';
    const TYPE_IBAN = '2';
    const TYPE_EUROPEAN_PASSPORT = '3';
    const TYPE_NOT_EUROPEAN_PASSPORT = '4';
    const TYPE_RESIDENCE_PERMIT = '5';
    const TYPE_COMMERCE_NUMBER_REGISTRATION = '7';
    const TYPE_OTHER_11 = '11';
    const TYPE_OTHER_12 = '12';
    const TYPE_OTHER_13 = '13';
    const TYPE_OTHER_14 = '14';
    const TYPE_OTHER_15 = '15';
    const TYPE_OTHER_16 = '16';
    const TYPE_OTHER_17 = '17';
    const TYPE_OTHER_18 = '18';
    const TYPE_OTHER_19 = '19';
    const TYPE_OTHER_20 = '20';
    const TYPE_SDD_MANDATE = '21';

    const STATUS_ON_HOLD = '0';
    const STATUS_NOT_VERIFIED = '1';
    const STATUS_ACCEPTED = '2';
    const STATUS_NOT_ACCEPTED = '3';
    const STATUS_UNREADABLE = '4';
    const STATUS_EXPIRED = '5';
    const STATUS_WRONG_TYPE = '6';
    const STATUS_WRONG_NAME = '7';

    /** @var string */
    private $id;
    /** @var string */
    private $status;
    /** @var string */
    private $type;
    /** @var DateTime */
    private $validityDate;

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
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return DateTime
     */
    public function getValidityDate(): DateTime
    {
        return $this->validityDate;
    }

    /**
     * @param DateTime $validityDate
     */
    public function setValidityDate(DateTime $validityDate)
    {
        $this->validityDate = $validityDate;
    }

    /**
     * @param stdClass $object
     *
     * @return bool
     */
    public function bindFromLemonway(stdClass $object): bool
    {
        $this->setId($object->ID);
        $this->setStatus($object->S);
        $this->setType($object->TYPE);

        $date = explode('/', $object->VD);
        $date = implode('-', array_reverse($date));

        $this->setValidityDate(new DateTime($date));

        return true;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id'            => $this->getId(),
            'type'          => $this->getType(),
            'status'        => $this->getStatus(),
            'validity_date' => $this->getValidityDate()->format(DateTime::ATOM),
        ];
    }

    /**
     * @param string $backofficeUrl
     * @param int $walletLemonwayId
     * @param string $csrfToken
     * @return string
     * @throws ParameterNotFoundException
     */
    public function getTemporaryFileUrl(string $backofficeUrl, int $walletLemonwayId, string $csrfToken): string
    {
        if (!$this->id) {
            throw new ParameterNotFoundException('id');
        }

        return $backofficeUrl . '/scripts/showDocument.php' .
            '?user_id=' . $walletLemonwayId .
            '&doc_id=' . $this->getId() .
            '&csrf_token=' . $csrfToken;
    }
}
