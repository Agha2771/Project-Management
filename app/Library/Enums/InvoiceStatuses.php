<?php

namespace ProjectManagement\Enums;

class InquiryStatuses
{
    const PENDING = 'pending';
    const PAID = 'paid';
    const CANCELLED = 'canceled';

    /**
     * Get all the project statuses.
     *
     * @return array
     */
    public static function all()
    {
        return [
            self::PENDING,
            self::PAID,
            self::CANCELLED
        ];
    }
}
