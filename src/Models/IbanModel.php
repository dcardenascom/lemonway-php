<?php
/**
 * Created by David Cardenas
 * GitHub: https://github.com/davidcardenasguia
 * GitLab: https://gitlab.com/dcardenas
 * Site: http://dcardenas.com
 * LinkedIn: https://www.linkedin.com/in/davidcardenasguia/.
 */

namespace Lemonway\Models;

use stdClass;

/**
 * Class IbanModel.
 */
class IbanModel extends LemonwayObjectModel
{
    /** @var string */
    private $id;
    /** @var string */
    private $status;
    /** @var string */
    private $ibanCode;
    /** @var string */
    private $swiftCode;
    /** @var string */
    private $holder;

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
     * @return string
     */
    public function getHolder(): string
    {
        return $this->holder;
    }

    /**
     * @param string $holder
     */
    public function setHolder(string $holder)
    {
        $this->holder = $holder;
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
        $this->setHolder($object->HOLDER);

        return true;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id'         => $this->getId(),
            'status'     => $this->getStatus(),
            'holder'     => $this->getHolder(),
            'swift_code' => $this->getSwiftCode(),
            'iban_code'  => $this->getIbanCode(),
        ];
    }
}
