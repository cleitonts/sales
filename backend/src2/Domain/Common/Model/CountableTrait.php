<?php

namespace Panthir\Domain\Common\Model;

use Symfony\Component\Serializer\Annotation\Groups;

trait CountableTrait
{
    #[Groups(['countable'])]
    private int $totalItems = 0;

    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    public function setTotalItems(int $totalItems): self
    {
        $this->totalItems = $totalItems;

        return $this;
    }
}
