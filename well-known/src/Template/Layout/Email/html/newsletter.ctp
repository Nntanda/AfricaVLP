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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html style="width:100%;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;">
  <head>
    <meta http-equiv="Content-Security-Policy" content="script-src 'none'; connect-src 'none'; object-src 'none'; form-action 'none';">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="telephone=no" name="format-detection">
    <title><?= $this->fetch('title') ?></title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,700,700i" rel="stylesheet">
    <!--<![endif]-->
    <style type="text/css">
      @media only screen and (max-width:600px) {
        a,
        ol li,
        p,
        ul li {
          font-size: 14px!important;
          line-height: 150%!important;
        }
        h1 {
          font-size: 25px!important;
          text-align: center;
          line-height: 120%!important;
        }
        h2 {
          font-size: 24px!important;
          text-align: center;
          line-height: 120%!important;
        }
        h3 {
          font-size: 20px!important;
          text-align: center;
          line-height: 120%!important;
        }
        h1 a {
          font-size: 25px!important;
        }
        h2 a {
          font-size: 24px!important;
        }
        h3 a {
          font-size: 20px!important;
        }
        .es-menu td a {
          font-size: 14px!important;
        }
        .es-header-body a,
        .es-header-body ol li,
        .es-header-body p,
        .es-header-body ul li {
          font-size: 16px!important;
        }
        .es-footer-body a,
        .es-footer-body ol li,
        .es-footer-body p,
        .es-footer-body ul li {
          font-size: 14px!important;
        }
        .es-infoblock a,
        .es-infoblock ol li,
        .es-infoblock p,
        .es-infoblock ul li {
          font-size: 12px!important;
        }
        *[class="gmail-fix"] {
          display: none!important;
        }
        .es-m-txt-c,
        .es-m-txt-c h1,
        .es-m-txt-c h2,
        .es-m-txt-c h3 {
          text-align: center!important;
        }
        .es-m-txt-r,
        .es-m-txt-r h1,
        .es-m-txt-r h2,
        .es-m-txt-r h3 {
          text-align: right!important;
        }
        .es-m-txt-l,
        .es-m-txt-l h1,
        .es-m-txt-l h2,
        .es-m-txt-l h3 {
          text-align: left!important;
        }
        .es-m-txt-c img,
        .es-m-txt-l img,
        .es-m-txt-r img {
          display: inline!important;
        }
        .es-button-border {
          display: block!important;
        }
        a.es-button {
          font-size: 20px!important;
          display: block!important;
          border-left-width: 0px!important;
          border-right-width: 0px!important;
        }
        .es-btn-fw {
          border-width: 10px 0px!important;
          text-align: center!important;
        }
        .es-adaptive table,
        .es-btn-fw,
        .es-btn-fw-brdr,
        .es-left,
        .es-right {
          width: 100%!important;
        }
        .es-content,
        .es-content table,
        .es-footer,
        .es-footer table,
        .es-header,
        .es-header table {
          width: 100%!important;
          max-width: 600px!important;
        }
        .es-adapt-td {
          display: block!important;
          width: 100%!important;
        }
        .adapt-img {
          width: 100%!important;
          height: auto!important;
        }
        .es-m-p0 {
          padding: 0;
        }
        .es-m-p0r {
          padding-right: 0px!important;
        }
        .es-m-p0l {
          padding-left: 0px!important;
        }
        .es-m-p0t {
          padding-top: 0px!important;
        }
        .es-m-p0b {
          padding-bottom: 0!important;
        }
        .es-m-p20b {
          padding-bottom: 20px!important;
        }
        .es-hidden,
        .es-mobile-hidden {
          display: none!important;
        }
        .es-desk-hidden {
          display: table-row!important;
          width: auto!important;
          overflow: visible!important;
          float: none!important;
          max-height: inherit!important;
          line-height: inherit!important;
        }
        .es-desk-menu-hidden {
          display: table-cell!important;
        }
        .esd-block-html table,
        table.es-table-not-adapt {
          width: auto!important;
        }
        table.es-social {
          display: inline-block!important;
        }
        table.es-social td {
          display: inline-block!important;
        }
      }
      @media screen and (max-width:9999px) {
        .cboxcheck:checked + input + * .thumb-carousel {
          height: auto!important;
          max-height: none!important;
          max-width: none!important;
          line-height: 0;
        }
        .thumb-carousel span {
          font-size: 0;
          line-height: 0;
        }
        .cboxcheck:checked + input + * .thumb-carousel .car-content {
          display: none;
          max-height: 0;
          overflow: hidden;
        }
        .cbox0:checked + * .content-1,
        .thumb-carousel .cbox1:checked + span .content-1,
        .thumb-carousel .cbox2:checked + span .content-2,
        .thumb-carousel .cbox3:checked + span .content-3,
        .thumb-carousel .cbox4:checked + span .content-4,
        .thumb-carousel .cbox5:checked + span .content-5 {
          display: block!important;
          max-height: none!important;
          overflow: visible!important;
        }
        .thumb-carousel .thumb {
          cursor: pointer;
          display: inline-block!important;
          width: 17.5%;
          margin: 1% 0.61%;
          border: 0 solid rgb(187, 187, 187);
        }
        .moz-text-html .thumb {
          display: none!important;
        }
        .thumb-carousel .thumb:hover {
          border: 0 solid rgb(68, 68, 68);
        }
        .cbox0:checked + * .thumb-1,
        .thumb-carousel .cbox1:checked + span .thumb-1,
        .thumb-carousel .cbox2:checked + span .thumb-2,
        .thumb-carousel .cbox3:checked + span .thumb-3,
        .thumb-carousel .cbox4:checked + span .thumb-4,
        .thumb-carousel .cbox5:checked + span .thumb-5 {
          border-color: rgb(51, 51, 51);
        }
        .thumb-carousel .thumb img {
          width: 100%;
          height: auto;
        }
        .thumb-carousel img {
          max-height: none!important;
        }
        .cboxcheck:checked + input + * .fallback {
          display: none!important;
          display: none;
          max-height: 0;
          height: 0;
          overflow: hidden;
        }
      }
      @media screen and (max-width:600px) {
        .car-table.responsive,
        .car-table.responsive .fallback .car-content img,
        .car-table.responsive .thumb-carousel,
        .car-table.responsive .thumb-carousel .car-content img {
          width: 100%!important;
          height: auto;
        }
      }
      @media screen {}
      .rollover:hover .rollover-first {
        max-height: 0px!important;
      }
      .rollover:hover .rollover-second {
        max-height: none!important;
      }
      #outlook a {
        padding: 0;
      }
      .ExternalClass {
        width: 100%;
      }
      .ExternalClass,
      .ExternalClass div,
      .ExternalClass font,
      .ExternalClass p,
      .ExternalClass span,
      .ExternalClass td {
        line-height: 100%;
      }
      .es-button {
        mso-style-priority: 100!important;
        text-decoration: none!important;
      }
      a[x-apple-data-detectors] {
        color: inherit!important;
        text-decoration: none!important;
        font-size: inherit!important;
        font-family: inherit!important;
        font-weight: inherit!important;
        line-height: inherit!important;
      }
      .es-desk-hidden {
        display: none;
        float: left;
        overflow: hidden;
        width: 0;
        max-height: 0;
        line-height: 0;
        mso-hide: all;
      }
      td .es-button-border:hover a.es-button-1561106637843 {}
      td .es-button-border-1561106819630:hover {
        border-color: #1A5632 #1A5632 #1A5632 #1A5632!important;
        background: #1A5632!important;
      }
      td .es-button-border:hover a.es-button-1561106847016 {}
      td .es-button-border-1561106847016:hover {
        border-color: #1A5632 #1A5632 #1A5632 #1A5632!important;
        background: #1A5632!important;
      }

      @media only screen and (max-width:445px){
        .bottom-txt{
          font-size: 12px !important;
        }

        .bottom-txt br{
          display: none;
        }

        .sign-txt{
          font-size: 11px !important;
        }
      }
    </style>
    <meta property="og:title" content="Email Digest"/><meta property="og:description" content=""/><meta property="og:image" content="https://uxyja.stripocdn.email/content/guids/CABINET_6c25b12971c62bfcba3327de5ffcfe61/images/63461562841100137.gif"/><meta property="og:url" content="https://viewstripo.email/template/02f8d2e9-10a2-4621-83d9-6b4af027fda3"/><meta property="og:type" content="article"/></head>
  <body style="width:100%;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;">
    <div class="es-wrapper-color" style="background-color:#F6F6F6;">
      <!--[if gte mso 9]> <v:background xmlns:v="urn:schemas-microsoft-com:vml" fill="t"> <v:fill type="tile" color="#f6f6f6"></v:fill> </v:background> <![endif]-->
      <table
        class="es-wrapper"
        width="100%"
        cellspacing="0"
        cellpadding="0"
        style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-repeat:repeat;background-position:center top;">
        <tbody>
          <tr style="border-collapse:collapse;">
            <td valign="top" style="padding:0;Margin:0;">
              <table cellpadding="0" cellspacing="0" class="es-content" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;">
                <tbody>
                  <tr style="border-collapse:collapse;">
                    <td class="es-adaptive" align="center" style="padding:0;Margin:0;">
                      <table class="es-content-body" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:transparent;" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="#00000000">
                        <tbody>
                          <tr style="border-collapse:collapse;">
                            <td align="left" style="padding:10px;Margin:0;">
                              <!--[if mso]><table width="580"><tr><td width="280" valign="top"><![endif]-->
                              <table class="es-left" cellspacing="0" cellpadding="0" align="left" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left;">
                                <tbody>
                                  <tr style="border-collapse:collapse;">
                                    <td width="280" align="left" style="padding:0;Margin:0;">
                                      <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                        <tbody>
                                          <tr style="border-collapse:collapse;">
                                            <td class="es-infoblock es-m-txt-c" align="left" style="padding:0;Margin:0;line-height:14px;font-size:12px;color:#CCCCCC;">
                                              <!-- <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:12px;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;line-height:14px;color:#CCCCCC;">Put your preheader text here</p> -->
                                            </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                              <!--[if mso]></td><td width="20"></td><td width="280" valign="top"><![endif]-->
                              <table class="es-right" cellspacing="0" cellpadding="0" align="right" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:right;">
                                <tbody>
                                  <tr style="border-collapse:collapse;">
                                    <td width="280" align="left" style="padding:0;Margin:0;">
                                      <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                        <tbody>
                                          <tr style="border-collapse:collapse;">
                                            <td align="right" class="es-infoblock es-m-txt-c" style="padding:0;Margin:0;line-height:14px;font-size:12px;color:#CCCCCC;">
                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:12px;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;line-height:14px;color:#CCCCCC;"></p>
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
              <table
                cellpadding="0"
                cellspacing="0"
                class="es-header"
                align="center"
                style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top;">
                <tbody>
                  <tr style="border-collapse:collapse;">
                    <td align="center" bgcolor="transparent" style="padding:0;Margin:0;background-color:transparent;">
                      <table class="es-header-body" width="600" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;">
                        <tbody>
                          <tr style="border-collapse:collapse;">
                            <td class="esdev-adapt-off" align="left" style="padding:0;Margin:0;">
                              <table width="600" cellpadding="0" cellspacing="0" class="esdev-mso-table" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                <tbody>
                                  <tr style="border-collapse:collapse;">
                                    <td class="esdev-mso-td" valign="top" style="padding:0;Margin:0;">
                                      <table cellpadding="0" cellspacing="0" class="es-left" align="left" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left;">
                                        <tbody>
                                          <tr style="border-collapse:collapse;">
                                            <td width="198" class="es-m-p0r" align="center" style="padding:0;Margin:0;">
                                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                                <tbody>
                                                  <tr style="border-collapse:collapse;">
                                                    <td align="center" style="padding:0;Margin:0;padding-bottom:10px;padding-top:20px;font-size:0;">
                                                      <a
                                                        target="_blank"
                                                        href="#"
                                                        style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;font-size:14px;text-decoration:underline;color:#84A5CA;"><img src="<?= $this->Url->image('logo-color.png', ['fullBase' => true]) ?>" alt="alt" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;" width="100"></a>
                                                    </td>
                                                  </tr>
                                                </tbody>
                                              </table>
                                            </td>
                                            <td width="5" style="padding:0;Margin:0;"></td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </td>
                                    <td class="esdev-mso-td" valign="top" style="padding:0;Margin:0;">

                                    </td>
                                    <td class="esdev-mso-td" valign="top" style="padding:0;Margin:0;">

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
              <table cellpadding="0" cellspacing="0" class="es-content" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;">
                <tbody>
                  <tr style="border-collapse:collapse;">
                    <td align="center" bgcolor="transparent" style="padding:0;Margin:0;background-color:transparent;">
                      <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" width="600" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;">
                        <tbody>
                          <tr style="border-collapse:collapse;">
                            <td align="left" style="padding:0;Margin:0;background-position:center top;">
                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                <tbody>
                                  <tr style="border-collapse:collapse;">
                                    <td width="600" align="center" valign="top" style="padding:0;Margin:0;">
                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                        <tbody>
                                          <tr style="border-collapse:collapse;">
                                            <td align="center" style="padding:0;Margin:0;font-size:0;border-top: 0.5px solid #1A5632;">

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
              <?= $this->fetch('content') ?>
              <!--  -->
              <table
                cellpadding="0"
                cellspacing="0"
                class="es-footer"
                align="center"
                style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top;">
                <tbody>
                  <tr style="border-collapse:collapse;">
                    <td align="center" style="padding:0;Margin:0;">
                      <table class="es-footer-body" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#1A5632;" width="600" cellspacing="0" cellpadding="0" bgcolor="#1A5632" align="center">
                        <tbody>
                          <tr style="border-collapse:collapse;">
                            <td align="left" style="Margin:0;padding-bottom:10px;padding-top:10px;padding-left:20px;padding-right:20px;background-position:center top;background-color:#1A5632;" bgcolor="#1A5632">

                            </td>
                          </tr>
                          <tr style="border-collapse:collapse;">
                            <td align="left" style="padding:0;Margin:0;padding-left:20px;padding-right:20px;">
                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                <tbody>
                                  <tr style="border-collapse:collapse;">
                                    <td width="560" align="center" valign="top" style="padding:0;Margin:0;">

                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </td>
                          </tr>
                          <tr style="border-collapse:collapse;">
                            <td align="left" style="padding:0;Margin:0;padding-bottom:15px;padding-left:20px;padding-right:20px;">
                              <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                <tbody>
                                  <tr style="border-collapse:collapse;">
                                    <td width="560" valign="top" align="center" style="padding:0;Margin:0;">
                                      <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                        <tbody>
                                          <tr style="border-collapse:collapse;">
                                            <td align="center" bgcolor="transparent" style="padding:0;Margin:0;padding-bottom:10px;font-size:0;background-color:transparent;">
                                              <table class="es-table-not-adapt es-social" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                                <tbody>
                                                  <tr style="border-collapse:collapse;">
                                                    <td valign="top" align="center" style="padding:0;Margin:0;padding-right:15px;"> <a href="https://www.facebook.com/AfricanUnionCommission" target="_blank"> <img title="Facebook" src="<?= $this->Url->image('facebook.png', ['fullBase' => true]) ?>" alt="Fb" width="32" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;"></a></td>
                                                    <td valign="top" align="center" style="padding:0;Margin:0;padding-right:15px;"> <a href="https://twitter.com/_AfricanUnion" target="_blank"> <img title="Twitter" src="<?= $this->Url->image('twitter.png', ['fullBase' => true]) ?>" alt="Tw" width="32" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;"></a></td>
                                                    <td valign="top" align="center" style="padding:0;Margin:0;padding-right:15px;"> <a href="https://www.youtube.com/user/AUCommission" target="_blank"> <img title="Youtube" src="<?= $this->Url->image('youtube.png', ['fullBase' => true]) ?>" alt="Yt" width="32" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;"></a></td>
                                                    <!-- <td valign="top" align="center" style="padding:0;Margin:0;padding-right:10px;"><img title="Instagram" src="instagram.png" alt="P" width="32" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;"></td> -->
                                                  </tr>
                                                </tbody>
                                              </table>
                                            </td>
                                          </tr>
                                          <tr style="border-collapse:collapse;">
                                            <td align="center" style="Margin:0;padding-top:10px;padding-bottom:10px;padding-left:20px;padding-right:20px;font-size:0;">
                                              <table border="0" width="75%" height="100%" cellpadding="0" cellspacing="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                                <tbody>
                                                  <tr style="border-collapse:collapse;">
                                                    <td style="padding:0;Margin:0px;border-bottom:1px solid #ffffff;background:none;height:1px;width:100%;margin:0px;"></td>
                                                  </tr>
                                                </tbody>
                                              </table>
                                            </td>
                                          </tr>
                                          <tr style="border-collapse:collapse;">
                                            <td align="center" style="padding:0;Margin:0;padding-top:5px;">
                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:13px;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;line-height:20px;color:#ffffff;">
                                                <a
                                                  target="_blank"
                                                  style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;font-size:13px;text-decoration:underline;color:#ffffff;"
                                                  href="#">Privacy</a>
                                                |
                                                <a
                                                  target="_blank"
                                                  style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;font-size:13px;text-decoration:underline;color:#ffffff;"
                                                  class="unsubscribe"
                                                  href="">Unsubscribe</a>
                                              </p>
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
              <table cellpadding="0" cellspacing="0" class="es-content" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;">
                <tbody>
                  <tr style="border-collapse:collapse;">
                    <td align="center" style="padding:0;Margin:0;">
                      <table bgcolor="transparent" class="es-content-body" align="center" cellpadding="0" cellspacing="0" width="600" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:transparent;">
                        <tbody>
                          <tr style="border-collapse:collapse;">
                            <td align="left" style="Margin:0;padding-left:20px;padding-right:20px;padding-top:30px;padding-bottom:30px;background-position:left top;">
                              <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                <tbody>
                                  <tr style="border-collapse:collapse;">
                                    <td width="560" valign="top" align="center" style="padding:0;Margin:0;">
                                      <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                        <tbody>
                                          <tr style="border-collapse:collapse;">
                                            <td align="center" colspan="2">
                                              <p style="color:#a2a2a2; font-size:13px; line-height:17px; font-style:italic; margin-top:10px; font-weight:400;" class="bottom-txt">If you have received this communication in error, please delete this <br>
                                                mail and notify us immediately at <a href="#" style="color: #4A90E2;">support@africanunion.com</a>.</p>
                                            </td>
                                          </tr>
                                          <tr>
                                            <td align="center" valign="top" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; color: #999999; font-family: Open Sans, Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 0;">
                                              <p class="sign-txt" style="font-size: 12px; line-height: 20px;">© <?= date('Y') ?> African Union</p>
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
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </body>
</html>
