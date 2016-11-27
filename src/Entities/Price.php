<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="prices")
 *
 * @JMS\ExclusionPolicy("all")
 */
class Price
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     *
     * @JMS\Expose()
     */
    protected $id;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     *
     * @JMS\Expose()
     */
    protected $value;

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

}