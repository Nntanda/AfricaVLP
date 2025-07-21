<?php

use Cake\Core\Configure;

/**
 * GENERAL STATUSES
 */
define('STATUS_ACTIVE', 1);
define('STATUS_INACTIVE', 2);

$statuses = [
    STATUS_ACTIVE => 'Active',
    STATUS_INACTIVE => 'Inactive'
];

/**
 * ADMIN STATUSES
 */
define('ADMIN_STATUS_ACTIVE', 1);
define('ADMIN_STATUS_INACTIVE', 2);

$AdminStatuses = [
    ADMIN_STATUS_ACTIVE => 'Active',
    ADMIN_STATUS_INACTIVE => 'Inactive'
];

/**
 * NEWS STATUSES
 */
define('NEWS_STATUS_PUBLISHED', 1);
define('NEWS_STATUS_DRAFT', 2);
define('NEWS_STATUS_DEACTIVATED', 3);

$newsStatuses = [
    NEWS_STATUS_PUBLISHED => 'Publish',
    NEWS_STATUS_DRAFT => 'Draft',
    NEWS_STATUS_DEACTIVATED => 'Deactivate'
];

return [
    'STATUSES' => $statuses,
    'ADMIN_STATUSES' => $AdminStatuses,
    'NEWS_STATUSES' => $newsStatuses
];