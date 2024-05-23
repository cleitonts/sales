<?php

namespace Panthir\Domain\Customer\ValueObject;

enum ContactType: string
{
    case HOUSE = 'House';
    case PROFESSIONAL = 'Professional';
    case DELIVERY = 'Delivery';
    case BILLING = 'Billing';
}
