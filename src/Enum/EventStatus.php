<?php

namespace App\Enum;

enum EventStatus: string
{
    /**
     * The event has been created and is scheduled for a future date.
     */
    case Scheduled = 'scheduled';

    /**
     * The event is open for participants to register.
     */
    case RegistrationOpen = 'registration_open';

    /**
     * The registration period has ended.
     */
    case RegistrationClosed = 'registration_closed';

    /**
     * The event is in a check-in phase, where participants are confirming their presence.
     */
    case CheckIn = 'check_in';

    /**
     * The event is currently taking place.
     */
    case Ongoing = 'ongoing';

    /**
     * The event has finished, and results are being calculated or confirmed.
     */
    case WaitingForResults = 'waiting_for_results';

    /**
     * The event has finished successfully.
     */
    case Completed = 'completed';

    /**
     * The event has been delayed and will happen at a later date.
     */
    case Postponed = 'postponed';

    /**
     * The event was canceled before it could take place.
     */
    case Cancelled = 'cancelled';

    /**
     * The event is past and has been archived for historical purposes.
     */
    case Archived = 'archived';
}
