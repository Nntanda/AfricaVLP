<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\View;

use Cake\View\View;

/**
 * Application View
 *
 * Your application's default view class
 *
 * @link https://book.cakephp.org/3.0/en/views.html#the-app-view
 */
class AppView extends View
{
    private $paginator_template = [
        'nextActive' => '<li class="page-item next"><a class="page-link" rel="next" href="{{url}}">{{text}}</a></li>',
        'nextDisabled' => '<li class="page-item next disabled"><a class="page-link" href="" onclick="return false;">{{text}}</a></li>',
        'prevActive' => '<li class="page-item prev"><a class="page-link" rel="prev" href="{{url}}">{{text}}</a></li>',
        'prevDisabled' => '<li class="page-item prev disabled"><a class="page-link" href="" onclick="return false;">{{text}}</a></li>',
        'counterRange' => '{{start}} - {{end}} of {{count}}',
        'counterPages' => '{{page}} of {{pages}}',
        'first' => '<li class="page-item first"><a class="page-link" href="{{url}}">{{text}}</a></li>',
        'last' => '<li class="page-item last"><a class="page-link" href="{{url}}">{{text}}</a></li>',
        'number' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>',
        'current' => '<li class="page-item active"><a class="page-link" href="">{{text}}</a></li>',
        'ellipsis' => '<li class="page-item ellipsis">&hellip;</li>',
        'sort' => '<a href="{{url}}">{{text}}</a>',
        'sortAsc' => '<a class="asc" href="{{url}}">{{text}}</a>',
        'sortDesc' => '<a class="desc" href="{{url}}">{{text}}</a>',
        'sortAscLocked' => '<a class="asc locked" href="{{url}}">{{text}}</a>',
        'sortDescLocked' => '<a class="desc locked" href="{{url}}">{{text}}</a>'
    ];

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading helpers.
     *
     * e.g. `$this->loadHelper('Html');`
     *
     * @return void
     */
    public function initialize()
    {
        $this->Paginator->setTemplates($this->paginator_template);
    }

    public function getStatusLabel($status = null, $scope = "general")
    {
        $statusLabel = '';
        switch ($scope) {
            case 'news':
                $statusLabel = $this->_getNewsStatusLabel($status);
                break;
            
            case 'admin':
                $statusLabel = $this->_getAdminStatusLabel($status);
                break;
            
            default:
                $statusLabel = $this->_getGeneralStatusLabel($status);
                break;
        }

        return $statusLabel;
    }

    function _getNewsStatusLabel($status = null)
    {
        $label = '';
        switch ($status) {
            case NEWS_STATUS_PUBLISHED:
                $label = __('Published');
                break;

            case NEWS_STATUS_DRAFT:
                $label = __('Draft');
                break;
            
            case NEWS_STATUS_DEACTIVATED:
                $label = __('Inactive');
                break;
            
            default:
                $label = '';
                break;
        }
        return $label;
    }

    function _getAdminStatusLabel($status = null)
    {
        $label = '';
        switch ($status) {
            case ADMIN_STATUS_ACTIVE:
                $label = __('Active');
                break;

            case ADMIN_STATUS_INACTIVE:
                $label = __('Inactive');
                break;
            
            default:
                $label = '';
                break;
        }
        return $label;
    }

    function _getGeneralStatusLabel($status = null)
    {
        $label = '';
        switch ($status) {
            case STATUS_ACTIVE:
                $label = __('Active');
                break;

            case STATUS_INACTIVE:
                $label = __('Inactive');
                break;
            
            default:
                $label = '';
                break;
        }
        return $label;
    }
}
