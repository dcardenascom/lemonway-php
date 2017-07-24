<?php
/**
 * Created by David Cardenas
 * GitHub: https://github.com/davidcardenasguia
 * GitLab: https://gitlab.com/dcardenas
 * Site: http://dcardenas.com
 * LinkedIn: https://www.linkedin.com/in/davidcardenasguia/.
 */

namespace Lemonway\Models;

use DateTime;
use stdClass;

/**
 * Class DocumentModel.
 */
class DocumentModel extends LemonwayObjectModel
{
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
}
