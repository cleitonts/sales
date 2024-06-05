<?php

declare(strict_types=1);

namespace App\Shared\Domain\Model;

use Doctrine\Common\Collections\Selectable;
use Doctrine\Persistence\ObjectRepository;

interface RepositoryInterface extends ObjectRepository, Selectable
{

}
