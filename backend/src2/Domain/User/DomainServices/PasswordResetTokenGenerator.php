<?php

namespace Panthir\Domain\User\DomainServices;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;

class PasswordResetTokenGenerator
{
    public function __construct(
        protected JWTEncoderInterface $JWTEncoder
    ) {
    }

    /**
     * @throws JWTEncodeFailureException
     */
    public function __invoke(): string
    {
        $current_time = time();
        $future_time = $current_time + (2 * 60 * 60);
        $expires_at = date('Y-m-d H:i:s', $future_time);

        return base64_encode(
            $this->JWTEncoder->encode(['expires_at' => $expires_at, md5(uniqid(rand(), true))])
        );
    }
}
