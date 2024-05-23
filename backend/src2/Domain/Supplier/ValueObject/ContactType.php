<?php

namespace Panthir\Domain\Supplier\ValueObject;

enum ContactType: string
{
    case PROFESSIONAL = 'Professional';
    case DELIVERY = 'Delivery';
    case BILLING = 'Billing';
}
