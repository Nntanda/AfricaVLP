<?php

use Cake\Core\Configure;

/**
 * GENERAL STATUSES
 */
define('STATUS_ACTIVE', 1);
define('STATUS_INACTIVE', 2);

$statuses = [
    STATUS_ACTIVE => __('Active'),
    STATUS_INACTIVE => __('Inactive')
];

/**
 * ADMIN STATUSES
 */
define('ADMIN_STATUS_ACTIVE', 1);
define('ADMIN_STATUS_INACTIVE', 2);

$AdminStatuses = [
    ADMIN_STATUS_ACTIVE => __('Active'),
    ADMIN_STATUS_INACTIVE => __('Inactive')
];

/**
 * NEWS STATUSES
 */
define('NEWS_STATUS_PUBLISHED', 1);
define('NEWS_STATUS_DRAFT', 2);
define('NEWS_STATUS_DEACTIVATED', 3);

$newsStatuses = [
    NEWS_STATUS_PUBLISHED => __('Publish'),
    NEWS_STATUS_DRAFT => __('Draft'),
    NEWS_STATUS_DEACTIVATED => __('Deactivate')
];

return [
    'STATUSES' => $statuses,
    'ADMIN_STATUSES' => $AdminStatuses,
    'NEWS_STATUSES' => $newsStatuses
];