<?php

namespace Tests\Application\Supplier;

use Panthir\Domain\Supplier\ValueObject\AddressType;
use Panthir\Domain\Supplier\ValueObject\ContactType;
use Tests\Application\CustomApplicationAuthCase;

class PostTest extends CustomApplicationAuthCase
{
    public function testCreateCustomerSuccess(): void
    {
        static::$client->request('POST', '/api/supplier/', [
            'name' => $this->faker->firstName(),
            'nickName' => $this->faker->company(),
            'document' => $this->faker->taxpayerIdentificationNumber(),
            'addresses' => [
                [
                    'name' => $this->faker->domainName(),
                    'country' => $this->faker->country(),
                    'district' => (method_exists($this->faker, 'state')) ? $this->faker->state() : $this->faker->city(),
                    'city' => $this->faker->city(),
                    'address' => $this->faker->streetAddress(),
                    'number' => $this->faker->randomNumber(4),
                    'zip' => $this->faker->postcode(),
                    'type' => AddressType::PROFESSIONAL->value,
                    'addressComplement' => $this->faker->domainName(),
                ],
            ],
            'contacts' => [
                [
                    'name' => $this->faker->domainName(),
                    'email' => $this->faker->email(),
                    'phone' => $this->faker->phoneNumber(),
                    'type' => ContactType::PROFESSIONAL->value,
                ],
            ],
            'secondaryDocument' => $this->faker->randomNumber(8),
            'additionalInformation' => $this->faker->realText(400),
        ]);

        $content = json_decode(static::$client->getResponse()->getContent(), true);
        $this->assertEmpty(array_diff(array_keys($content['data']), ['name', 'nickName', 'id', 'document']));
        $this->assertResponseStatusCodeSame(200);
    }
}
