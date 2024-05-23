<?php

namespace Panthir\Application\UseCase\Financial\Normalizer\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class InstallmentDTO
{
    #[Assert\NotBlank]
    private float $value;

    #[Assert\NotBlank]
    private float $fees;

    #[Assert\NotBlank]
    private float $fine;

    #[Assert\NotBlank]
    private float $discount;

    #[Assert\NotBlank]
    private \DateTime $date;

    public function getValue(): float
    {
        return $this->value;
    }

    public function setValue(float $value): InstallmentDTO
    {
        $this->value = $value;

        return $this;
    }

    public function getFees(): float
    {
        return $this->fees;
    }

    public function setFees(float $fees): InstallmentDTO
    {
        $this->fees = $fees;

        return $this;
    }

    public function getFine(): float
    {
        return $this->fine;
    }

    public function setFine(float $fine): InstallmentDTO
    {
        $this->fine = $fine;

        return $this;
    }

    public function getDiscount(): float
    {
        return $this->discount;
    }

    public function setDiscount(float $discount): InstallmentDTO
    {
        $this->discount = $discount;

        return $this;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): InstallmentDTO
    {
        $this->date = $date;

        return $this;
    }
}
