<?php

declare(strict_types=1);

namespace App\Core\Domain\Model\Task;

use App\Core\Domain\Model\User\User;
use App\Shared\Domain\Exception\BusinessLogicViolationException;
use App\Shared\Domain\Model\Aggregate;
use App\Shared\Domain\Service\Assert\Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 *
 * @ORM\Table(name="task",
 *     indexes={@ORM\Index(name="task_status_idx", columns={"status"})}
 * )
 */
class Task extends Aggregate
{
    /**
     * @ORM\Id()
     *
     * @ORM\GeneratedValue()
     *
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    private int $id;

    /** @ORM\Column(type="string", length=100, nullable=false) */
    private string $title;

    /** @ORM\Column(type="string", length=255, nullable=false) */
    private string $description;

    /** @ORM\Column(type="string", enumType=StatusEnum::class) */
    private StatusEnum $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Core\Domain\Model\User\User")
     *
     * @ORM\JoinColumn(onDelete="cascade", nullable=false)
     */
    private User $user;

    /** @ORM\Column(type="datetime_immutable", nullable=true)  */
    private \DateTimeImmutable $executionDay;

    /** @ORM\Column(type="datetime_immutable", nullable=true)  */
    private \DateTimeImmutable $createdAt;

    public function __construct(string $title, \DateTimeImmutable $executionDay, User $user, string $description = '')
    {
        $this->setTitle($title);
        $this->setExecutionDay($executionDay);
        $this->setUser($user);
        $this->setDescription($description);
        $this->setStatus(StatusEnum::NEW);
        $this->setCreatedAt(new \DateTimeImmutable());

        $this->raise(new TaskCreatedEvent($this));
    }

    public function changeTitle(string $title): void
    {
        $this->setTitle($title);
    }

    public function changeDescription(string $description): void
    {
        $this->setDescription($description);
    }

    public function changeExecutionDay(\DateTimeImmutable $assignedDay): void
    {
        $this->setExecutionDay($assignedDay);
    }

    public function done(): void
    {
        if (StatusEnum::DONE == $this->status) {
            return;
        }

        if (StatusEnum::DECLINED == $this->status) {
            throw new BusinessLogicViolationException('Declined task can\'t be done');
        }

        $this->setStatus(StatusEnum::DONE);
        $this->raise(new TaskDoneEvent($this));
    }

    public function decline(): void
    {
        if (StatusEnum::DECLINED == $this->status) {
            return;
        }

        if (StatusEnum::DONE == $this->status) {
            throw new BusinessLogicViolationException('Done task can\'t be declined');
        }

        $this->setStatus(StatusEnum::DECLINED);
        $this->raise(new TaskDeclinedEvent($this));
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getExecutionDay(): \DateTimeImmutable
    {
        return $this->executionDay;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStatus(): StatusEnum
    {
        return $this->status;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    private function setTitle(string $title): void
    {
        Assert::minLength($title, 5, 'Title should contain at least %2$s characters. Got: %s');
        Assert::maxLength($title, 100, 'Title should contain at most %2$s characters. Got: %s');
        $this->title = $title;
    }

    private function setDescription(string $description): void
    {
        Assert::maxLength($description, 100, 'Description should contain at most %2$s characters. Got: %s');
        $this->description = $description;
    }

    private function setUser(User $user): void
    {
        $this->user = $user;
    }

    private function setStatus(StatusEnum $status): void
    {
        $this->status = $status;
    }

    private function setExecutionDay(\DateTimeImmutable $executionDay): void
    {
        $executionDayNormalized = $executionDay->setTime(0, 0);
        $now = (new \DateTimeImmutable())->setTime(0, 0);

        Assert::greaterThanEq($executionDayNormalized, $now, 'Execution day should be not in past');

        $this->executionDay = $executionDayNormalized;
    }

    private function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
