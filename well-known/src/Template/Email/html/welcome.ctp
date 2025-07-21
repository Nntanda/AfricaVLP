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
<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse !important; max-width: 600px; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
    <tbody>
        <tr>
        <td align="center" valign="top" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; font-family: Open Sans, Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 0;">
            <table cellspacing="0" cellpadding="0" border="0" width="100%" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse !important; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
            <tbody>
                <tr>
                <td align="center" bgcolor="#ffffff" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-radius: 0 0 10px 10px; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 25px;">
                    <div class="icon" style="-moz-osx-font-smoothing: grayscale; -webkit-font-smoothing: antialiased; border: 0; border-radius: 50%; font-size: normal; font-style: normal; font-variant: normal; font-weight: normal; height: 60px; line-height: normal; margin: 30px auto 15px; padding: 0; vertical-align: baseline; width: 60px;">
                        <img src="<?= $this->Url->image('email-confirm.png', ['fullBase' => true]) ?>" class="main-icon" alt="" style="-moz-osx-font-smoothing: grayscale; -ms-interpolation-mode: bicubic; -webkit-font-smoothing: antialiased; border: 0; font-size: normal; font-style: normal; font-variant: normal; font-weight: normal; height: auto; line-height: normal; margin: 0; max-height: 100%; max-width: 100%; outline: none; padding: 0; text-decoration: none; vertical-align: baseline; width: 100px;">
                    </div>
                    <table cellspacing="0" cellpadding="0" border="0" width="100%" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse !important; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                    <tbody>
                        <tr></tr>
                        <tr>
                            <td align="center" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; font-family: Open Sans, Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" class="main-txt">
                                <h2 style="-moz-osx-font-smoothing: grayscale; -webkit-font-smoothing: antialiased; border: 0; color: #404040; font: 500 22px/25px apple-system, BlinkMacSystemFont, Arial, 'Segoe UI', 'Helvetica Neue', sans-serif; margin: 0; padding: 15px 0 0; vertical-align: baseline;"
                                align="center" class="greetings"><?= __('Hi,'). $user->first_name ?></h2>
                                <h2 style="-moz-osx-font-smoothing: grayscale; -webkit-font-smoothing: antialiased; border: 0; color: #404040; font: 400 22px/25px apple-system, BlinkMacSystemFont, Arial, 'Segoe UI', 'Helvetica Neue', sans-serif; margin: 0; padding: 15px 0; vertical-align: baseline; line-height: 1.4;"
                                align="center"><?= __('Welcome to the African Union Volunteering Linkage Platform.') ?></h2>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 20px 0 15px;">
                                <table border="0" cellspacing="0" cellpadding="0" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse !important; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="75%" class="other-tb">
                                    <tbody>
                                    <tr>
                                        <td align="center" class="other-cnt">
                                            <p style="color:#999999; font-size:14px; line-height:1.4; margin-top:25px;" class="other-txt"><?= __('Click the link / button to complete registration.') ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-radius: 26px; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                            bgcolor="#1A5632">
                                            <a href="https://www.surveymonkey.com/r/TVFS9QP" target="_blank" class="btn" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; background: #1A5632; color: #ffffff; display: block; font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; padding: 14px 120px; text-decoration: none;" ><?= __('Complete Registration') ?></a>
                                        </td>
                                    </tr>
                                        <tr>
                                            <td align="center" class="other-cnt">
                                                <p style="color:#999999; font-size:14px; line-height:1.4; margin-top:25px;" class="other-txt"><?= __('Click the link / button to verify your email.') ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-radius: 26px; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                            bgcolor="#1A5632">
                                                <a href="<?= $this->Url->build($activationUrl) ?>" target="_blank" class="btn" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; background: #1A5632; color: #ffffff; display: block; font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; padding: 14px 120px; text-decoration: none;" ><?= __('Verify Email') ?></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" class="other-cnt">
                                                <p style="color:#999999; font-size:14px; line-height:1.4; margin-top:25px;" class="other-txt"><?= __('You will receive monthly newsletters on volunteers, and volunteering events across the continent.') ?></p>
                                                <p style="color:#999999; font-size:14px; line-height:1.6; margin-top:15px;" class="other-txt"><?= __('Regards') ?>,<br>AU Team</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                    </table>
                </td>
                </tr>
            </tbody>
            </table>
        </td>
        </tr>
    </tbody>
</table>