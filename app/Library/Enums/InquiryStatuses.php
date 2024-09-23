<?php

namespace ProjectManagement\Enums;

class InquiryStatuses
{
    const IN_PROGRESS = 'in_progress';
    const COMPLETED = 'completed';
    const ON_HOLD = 'on_hold';
    const DECLINED = 'declined';

    /**
     * Get all the project statuses.
     *
     * @return array
     */
    public static function all()
    {
        return [
            self::IN_PROGRESS,
            self::COMPLETED,
            self::ON_HOLD,
            self::DECLINED,
        ];
    }
}
