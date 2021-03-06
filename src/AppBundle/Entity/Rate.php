<?php

namespace AppBundle\Entity;

use DateTime;
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
     * @var Currency
     *
     * @ORM\ManyToOne(targetEntity="Currency", inversedBy="rate")
     * @ORM\JoinColumn(name="currency_id", referencedColumnName="id")
     */
    private $currency;

    /**
     * @var DateTime
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
    public function getId() : int
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
    public function setCurrency(Currency $currency) : Rate
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currencyId
     *
     * @return Currency
     */
    public function getCurrency() : Currency
    {
        return $this->currency;
    }

    /**
     * Set createdAt
     *
     * @param DateTime $createdAt
     *
     * @return Rate
     */
    public function setCreatedAt($createdAt) : Rate
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return DateTime
     */
    public function getCreatedAt() : DateTime
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
    public function setValue($value) : Rate
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return float
     */
    public function getValue() : float
    {
        return (float)$this->value;
    }

    /**
     * Returns currency rate taking nominal into account
     *
     * @return float
     */
    public function getRate() : float
    {
        return $this->getValue() / $this->getCurrency()->getNominal();
    }
}
