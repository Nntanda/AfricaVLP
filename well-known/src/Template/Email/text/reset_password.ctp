<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
$activationUrl = [
    '_full' => true,
    'prefix' => false,
    'controller' => 'Users',
    'action' => 'resetPassword',
    isset($token) ? $token : ''
];
?>
<?= __('Hi,'). $first_name ?>

<?= __('Your password reset email.') ?>

<?= __('Click the link / button to reset your password.') ?>
<?= $this->Url->build($activationUrl); ?>

<?= __('Or follow the url below') ?>

<?= __('Regards') ?>,
AU Team
