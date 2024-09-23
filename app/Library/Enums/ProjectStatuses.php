<?php

namespace ProjectManagement\Enums;

class ProjectStatuses
{
    const INQUIRY = 'inquiry';
    const NOT_STARTED = 'not_started';
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
            self::INQUIRY,
            self::NOT_STARTED,
            self::IN_PROGRESS,
            self::COMPLETED,
            self::ON_HOLD,
            self::DECLINED,
        ];
    }
}
