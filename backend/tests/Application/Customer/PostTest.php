<?php

namespace tests\Application\Customer;

use Panthir\Domain\Customer\ValueObject\AddressType;
use Panthir\Domain\Customer\ValueObject\ContactType;
use Tests\Application\CustomApplicationAuthCase;

class PostTest extends CustomApplicationAuthCase
{
    public function testCreateCustomerSuccess(): void
    {
        static::$client->request('POST', '/api/customer/', [
            'name' => $this->faker->firstName(),
            'surname' => $this->faker->lastName(),
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
            'birthDate' => $this->faker->date(),
            'secondaryDocument' => $this->faker->randomNumber(8),
            'additionalInformation' => $this->faker->realText(400),
        ]);

        $content = json_decode(static::$client->getResponse()->getContent(), true);
        $this->assertEmpty(array_diff(array_keys($content['data']), ['name', 'surname', 'id', 'document']));
        $this->assertResponseStatusCodeSame(200);
    }
}
