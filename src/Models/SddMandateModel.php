<?php
/**
 * Created by David Cardenas
 * GitHub: https://github.com/dcardenascom
 * GitLab: https://gitlab.com/dcardenas
 * Site: http://dcardenas.com
 * LinkedIn: https://www.linkedin.com/in/davidcardenasguia/.
 */

namespace Lemonway\Models;

use stdClass;

/**
 * Class SddMandateModel.
 */
class SddMandateModel extends LemonwayObjectModel
{
    const STATUS_NOT_VALIDATED = '0';
    const STATUS_ENABLED_6_WORKING_DAYS = '5';
    const STATUS_ENABLED_3_WORKING_DAYS = '6';
    const STATUS_DISABLED = '8';
    const STATUS_REJECTED = '9';

    /** @var string */
    private $id;
    /** @var string */
    private $status;
    /** @var string */
    private $ibanCode;
    /** @var string */
    private $swiftCode;

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
    public function getIbanCode(): string
    {
        return $this->ibanCode;
    }

    /**
     * @param string $ibanCode
     */
    public function setIbanCode(string $ibanCode)
    {
        $this->ibanCode = $ibanCode;
    }

    /**
     * @return string
     */
    public function getSwiftCode(): string
    {
        return $this->swiftCode;
    }

    /**
     * @param string $swiftCode
     */
    public function setSwiftCode(string $swiftCode)
    {
        $this->swiftCode = $swiftCode;
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
        $this->setIbanCode($object->DATA);
        $this->setSwiftCode($object->SWIFT);

        return true;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'swift_code' => $this->getSwiftCode(),
            'iban_code'  => $this->getIbanCode(),
            'id'         => $this->getId(),
            'status'     => $this->getStatus(),
        ];
    }
}
