<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * Rate
 *
 * @ORM\Table(name="rate", uniqueConstraints={@UniqueConstraint(name="cu_date", columns={"currency_id", "created_at"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RateRepository")
 */
class Rate
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="currency_id", type="integer")
     * @ORM\ManyToOne(targetEntity="Currency", inversedBy="rate")
     * @ORM\JoinColumn(name="currency_id", referencedColumnName="id")
     */
    private $currencyId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="date")
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="decimal", precision=8, scale=4)
     */
    private $value;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set currencyId
     *
     * @param  Currency $currency
     *
     * @return Rate
     */
    public function setCurrency(Currency $currency)
    {
        $this->currencyId = $currency->getId();

        return $this;
    }

    /**
     * Get currencyId
     *
     * @return int
     */
    public function getCurrencyId()
    {
        return $this->currencyId;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Rate
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return Rate
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return float
     */
    public function getValue()
    {
        return (float)$this->value;
    }
}
