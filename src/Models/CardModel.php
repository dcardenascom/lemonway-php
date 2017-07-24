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
 * Class CardModel.
 */
class CardModel extends LemonwayObjectModel
{
    /** @var string */
    private $id;
    /** @var bool */
    private $is3ds;
    /** @var string */
    private $author;
    /** @var string */
    private $number;
    /** @var DateTime */
    private $expedition;
    /** @var string */
    private $type;

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
     * @return bool
     */
    public function is3ds(): bool
    {
        return $this->is3ds;
    }

    /**
     * @param bool $is3ds
     */
    public function set3ds(bool $is3ds)
    {
        $this->is3ds = $is3ds;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor(string $author)
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @param string $number
     */
    public function setNumber(string $number)
    {
        $this->number = $number;
    }

    /**
     * @return DateTime
     */
    public function getExpedition(): DateTime
    {
        return $this->expedition;
    }

    /**
     * @param DateTime $expedition
     */
    public function setExpedition(DateTime $expedition)
    {
        $this->expedition = $expedition;
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
     * @param stdClass $object
     *
     * @return bool
     */
    public function bindFromLemonway(stdClass $object): bool
    {
        $this->setId($object->ID);
        $this->set3ds($object->EXTRA->IS3DS);
        $this->setAuthor($object->EXTRA->AUTH);
        $this->setNumber($object->EXTRA->NUM);
        $this->setType($object->EXTRA->TYP);

        $date = explode('/', $object->EXTRA->EXP);
        $date = implode('-', array_reverse($date));

        $this->setExpedition(new DateTime($date));

        return true;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id'         => $this->getId(),
            'author'     => $this->getAuthor(),
            'expedition' => $this->getExpedition()->format(DateTime::ATOM),
            'number'     => $this->getNumber(),
            'type'       => $this->getType(),
            'is3ds'      => $this->is3ds(),
        ];
    }
}
