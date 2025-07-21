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

$first = $newsContent[0];
?>

<table cellpadding="0" cellspacing="0" class="es-content" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;">
    <tbody>
        <tr style="border-collapse:collapse;">
            <td align="center" bgcolor="transparent" style="padding:0;Margin:0;background-color:transparent;">
                <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" width="600" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;">
                    <tbody>
                        <tr style="border-collapse:collapse;">
                            <td align="left" style="Margin:0;padding-top:20px;padding-left:20px;padding-right:20px;padding-bottom:25px;background-position:center top;">
                                <!--[if mso]><table width="560" cellpadding="0" cellspacing="0"><tr><td width="270" valign="top"><![endif]-->
                                <table cellpadding="0" cellspacing="0" class="es-left" align="left" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left;">
                                    <tbody>
                                        <tr style="border-collapse:collapse;">
                                            <td width="270" class="es-m-p20b" align="left" style="padding:0;Margin:0;">
                                                <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                                    <tbody>
                                                        <tr style="border-collapse:collapse;">
                                                            <td align="center" height="10" style="padding:0;Margin:0;"></td>
                                                        </tr>
                                                        <tr style="border-collapse:collapse;">
                                                            <td align="left" class="es-m-txt-l" style="padding:0;Margin:0;">
                                                                <h2 style="Margin:0;line-height:29px;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:22px;font-style:normal;font-weight:bold;color:#1A5632;"><?= h($first->title) ?></h2>
                                                            </td>
                                                        </tr>
                                                        <tr style="border-collapse:collapse;">
                                                            <td align="left" style="padding:0;Margin:0;padding-top:10px;padding-bottom:10px;font-size:0;">
                                                                <table border="0" width="55%" height="100%" cellpadding="0" cellspacing="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                                                <tbody>
                                                                    <tr style="border-collapse:collapse;">
                                                                    <td style="padding:0;Margin:0px;border-bottom:3px solid #9F2241;background:none;height:1px;width:100%;margin:0px;"></td>
                                                                    </tr>
                                                                </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr style="border-collapse:collapse;">
                                                            <td align="left" style="padding:0;Margin:0;">
                                                                <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;line-height:21px;color:#333333;"><?= $this->Text->truncate(strip_tags($first->content), 150, ['ellipsis' => '...']) ?></p>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!--[if mso]></td><td width="20"></td><td width="270" valign="top"><![endif]-->
                                <table cellpadding="0" cellspacing="0" class="es-right" align="right" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:right;">
                                    <tbody>
                                        <tr style="border-collapse:collapse;">
                                            <td width="270" align="left" style="padding:0;Margin:0;">
                                                <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                                    <tbody>
                                                        <tr style="border-collapse:collapse;">
                                                            <td align="center" style="padding:0;Margin:0;font-size:0;">
                                                                <a
                                                                target="_blank"
                                                                class="rollover"
                                                                href="<?= $this->Url->build(['controller' => 'News', 'action' => 'view', $first->id], true) ?>"
                                                                style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;font-size:14px;text-decoration:underline;color:#2CB543;"><img
                                                                class="adapt-img rollover-first"
                                                                src="<?= $first->image ?>"
                                                                alt="alt"
                                                                style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;"
                                                                width="270">
                                                                <div style="mso-hide:all;">
                                                                    <img
                                                                    width="270"
                                                                    class="adapt-img rollover-second"
                                                                    style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;max-height:0px;"
                                                                    src="<?= $first->image ?>"
                                                                    alt="alt">
                                                                </div>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!--[if mso]></td></tr></table><![endif]-->
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>

<table cellpadding="0" cellspacing="0" class="es-content" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;">
    <tbody>
        <tr style="border-collapse:collapse;">
        <td align="center" style="padding:0;Margin:0;">
            <table bgcolor="#fafafa" class="es-content-body" align="center" cellpadding="0" cellspacing="0" width="600" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FAFAFA;">
            <tbody>
                <tr style="border-collapse:collapse;">
                    <td align="left" style="padding:0;Margin:0;padding-top:20px;padding-left:20px;padding-right:20px;background-position:center top;background-color:#FAFAFA;" bgcolor="#fafafa">
                        <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                            <tbody>
                                <tr style="border-collapse:collapse;">
                                    <td width="560" align="center" valign="top" style="padding:0;Margin:0;">
                                        <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                        <tbody>
                                            <tr style="border-collapse:collapse;">
                                                <td align="center" style="padding:0;Margin:0;">
                                                    <h2 style="Margin:0;line-height:29px;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:20px;font-style:normal;font-weight:bold;color:#1A5632;">Top News</h2>
                                                </td>
                                            </tr>
                                            <tr style="border-collapse:collapse;">
                                                <td align="center" style="Margin:0;padding-bottom:5px;padding-top:5px;padding-left:10px;padding-right:10px;font-size:0;">
                                                    <table border="0" width="12%" height="100%" cellpadding="0" cellspacing="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                                    <tbody>
                                                        <tr style="border-collapse:collapse;">
                                                        <td style="padding:0;Margin:0px;border-bottom:3px solid #9F2241;background:none;height:1px;width:100%;margin:0px;"></td>
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

                <tr style="border-collapse:collapse;">
                    <td align="left" style="Margin:0;padding-bottom:5px;padding-top:10px;padding-left:20px;padding-right:20px;background-color:#FAFAFA;background-position:center top;" bgcolor="#fafafa">
                        <!--[if mso]><table width="560" cellpadding="0" cellspacing="0"><tr><td width="272" valign="top"><![endif]-->
                        <?php for ($i = 1; ($i < 3 && $i < count($newsContent)); $i++): ?>
                        <table cellpadding="0" cellspacing="0" class="es-<?= $i === 1 ? 'left' : 'right' ?>" align="<?= $i === 1 ? 'left' : 'right' ?>" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left;">
                            <tbody>
                                <tr style="border-collapse:collapse;">
                                <td class="es-m-p20b" width="272" align="left" style="padding:0;Margin:0;">
                                    <table
                                    width="100%"
                                    cellspacing="0"
                                    cellpadding="0"
                                    style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;border-width:1px;border-style:solid;border-color:#EFEFEF;background-color:#FFFFFF;background-position:center top;"
                                    bgcolor="#ffffff">
                                    <tbody>
                                        <tr style="border-collapse:collapse;">
                                            <td align="center" style="padding:0;Margin:0;font-size:0;">
                                                <a
                                                target="_blank"
                                                href="#"
                                                style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;font-size:14px;text-decoration:underline;color:#2CB543;"><img
                                                class="adapt-img img-1"
                                                src="<?= $newsContent[$i]->image ?>"
                                                alt="<?= $newsContent[$i]->title ?>"
                                                title="<?= $newsContent[$i]->title ?>"
                                                style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;background:url(https://stripo.email/static//assets/img/default-img-back.png) 50% center no-repeat #F9F9F9;box-shadow:#EEEEEE 0px 0px 0px 1px inset;"
                                                width="270"></a>
                                            </td>
                                        </tr>
                                        <tr style="border-collapse:collapse;">
                                            <td align="left" style="padding:0;Margin:0;padding-top:10px;padding-left:15px;padding-right:15px;">
                                                <h4 style="Margin:0;line-height:120%;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;color:#404040;"><?= $newsContent[$i]->title ?></h4>
                                            </td>
                                        </tr>
                                        <tr style="border-collapse:collapse;">
                                            <td align="left" class="product-description" style="Margin:0;padding-top:5px;padding-bottom:10px;padding-left:15px;padding-right:15px;">
                                                <p class="product-description" style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;line-height:21px;color:#666666;"><?= $this->Text->truncate(strip_tags($newsContent[$i]->content), 150, ['ellipsis' => '...']) ?></p>
                                            </td>
                                        </tr>
                                        <tr style="border-collapse:collapse;">
                                            <td align="left" style="Margin:0;padding-top:10px;padding-bottom:10px;padding-left:15px;padding-right:15px;">
                                                <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:15px;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;line-height:23px;color:#002240;">
                                                <strong>
                                                    <a
                                                    target="_blank"
                                                    style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;font-size:15px;text-decoration:none;color:#9F2241;"
                                                    href="<?= $this->Url->build(['controller' => 'News', 'action' => 'view', $newsContent[$i]->id], true) ?>">READ MORE ➟</a>
                                                </strong>
                                                </p>
                                            </td>
                                        </tr>
                                    </tbody>
                                    </table>
                                </td>
                                </tr>
                            </tbody>
                        </table>
                        <!--[if mso]></td><td width="15"></td><td width="273" valign="top"><![endif]-->
                        <?php endfor; ?>
                        <!--[if mso]></td></tr></table><![endif]-->
                    
                    </td>
                </tr>
                <tr style="border-collapse:collapse;">
                    <td align="left" style="Margin:0;padding-bottom:5px;padding-top:10px;padding-left:20px;padding-right:20px;background-color:#FAFAFA;background-position:center top;" bgcolor="#fafafa">
                        <!--[if mso]><table width="560" cellpadding="0" cellspacing="0"><tr><td width="272" valign="top"><![endif]-->
                        <?php for ($i = 3; ($i < 5 && $i < count($newsContent)); $i++): ?>
                        <table cellpadding="0" cellspacing="0" class="es-<?= $i === 3 ? 'left' : 'right' ?>" align="<?= $i === 3 ? 'left' : 'right' ?>" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left;">
                            <tbody>
                                <tr style="border-collapse:collapse;">
                                <td class="es-m-p20b" width="272" align="left" style="padding:0;Margin:0;">
                                    <table
                                    width="100%"
                                    cellspacing="0"
                                    cellpadding="0"
                                    style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;border-width:1px;border-style:solid;border-color:#EFEFEF;background-color:#FFFFFF;background-position:center top;"
                                    bgcolor="#ffffff">
                                    <tbody>
                                        <tr style="border-collapse:collapse;">
                                            <td align="center" style="padding:0;Margin:0;font-size:0;">
                                                <a
                                                target="_blank"
                                                href="#"
                                                style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;font-size:14px;text-decoration:underline;color:#2CB543;"><img
                                                class="adapt-img img-1"
                                                src="<?= $newsContent[$i]->image ?>"
                                                alt="<?= $newsContent[$i]->title ?>"
                                                title="<?= $newsContent[$i]->title ?>"
                                                style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;background:url(https://stripo.email/static//assets/img/default-img-back.png) 50% center no-repeat #F9F9F9;box-shadow:#EEEEEE 0px 0px 0px 1px inset;"
                                                width="270"></a>
                                            </td>
                                        </tr>
                                        <tr style="border-collapse:collapse;">
                                            <td align="left" style="padding:0;Margin:0;padding-top:10px;padding-left:15px;padding-right:15px;">
                                                <h4 style="Margin:0;line-height:120%;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;color:#404040;"><?= $newsContent[$i]->title ?></h4>
                                            </td>
                                        </tr>
                                        <tr style="border-collapse:collapse;">
                                            <td align="left" class="product-description" style="Margin:0;padding-top:5px;padding-bottom:10px;padding-left:15px;padding-right:15px;">
                                                <p class="product-description" style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;line-height:21px;color:#666666;"><?= $this->Text->truncate(strip_tags($newsContent[$i]->content), 150, ['ellipsis' => '...']) ?></p>
                                            </td>
                                        </tr>
                                        <tr style="border-collapse:collapse;">
                                            <td align="left" style="Margin:0;padding-top:10px;padding-bottom:10px;padding-left:15px;padding-right:15px;">
                                                <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:15px;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;line-height:23px;color:#002240;">
                                                <strong>
                                                    <a
                                                    target="_blank"
                                                    style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;font-size:15px;text-decoration:none;color:#9F2241;"
                                                    href="<?= $this->Url->build(['controller' => 'News', 'action' => 'view', $newsContent[$i]->id], true) ?>">READ MORE ➟</a>
                                                </strong>
                                                </p>
                                            </td>
                                        </tr>
                                    </tbody>
                                    </table>
                                </td>
                                </tr>
                            </tbody>
                        </table>
                        <!--[if mso]></td><td width="15"></td><td width="273" valign="top"><![endif]-->
                        <?php endfor; ?>
                        <!--[if mso]></td></tr></table><![endif]-->
                    
                    </td>
                </tr>
            </tbody>
            </table>
        </td>
        </tr>
    </tbody>
</table>