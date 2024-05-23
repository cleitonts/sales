<?php

namespace Panthir\Application\Services\Notify;

class Notify implements NotifyInterface
{
    public const ERROR = 'error';
    public const WARNING = 'warning';
    public const INFO = 'info';
    public const SUCCESS = 'success';

    private array $messages = [];

    public function addMessage($type, $text)
    {
        $this->messages[] = [
            'type' => $type,
            'text' => $text,
        ];
    }

    /**
     * @param string $data
     */
    public function newReturn($data): string
    {
        $return = [
            'data' => $data,
            'notify' => $this->messages,
        ];

        return json_encode($return);
    }

    public function getTreeToSerialize($data): array
    {
        return [
            'data' => $data,
            'notify' => $this->messages,
        ];
    }
}
