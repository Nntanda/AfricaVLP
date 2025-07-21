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
    'action' => 'validateEmail',
    isset($user->token) ? $user->token : ''
];
?>

<?= __('Hi,'). $user->first_name ?>
<?= __('Welcome to the African Union Volunteering Linkage Platform.') ?>

<?= __('Click the link / button to verify your email.') ?>

<a href="<?= $this->Url->build($activationUrl) ?>"><?= __('Verify Email') ?></a>

<?= __('Or follow the url below') ?>
<?= $this->Url->build($activationUrl) ?>

<?= __('You will receive monthly newsletters on volunteers, and volunteering events across the continent.') ?>

<?= __('Regards') ?>,
AU Team
