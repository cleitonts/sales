<?php

namespace Panthir\Domain\Supplier\ValueObject;

enum AddressType: string
{
    case PROFESSIONAL = 'Professional';
    case DELIVERY = 'Delivery';
    case BILLING = 'Billing';
}
