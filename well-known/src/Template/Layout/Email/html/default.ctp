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
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
    <title><?= $this->fetch('title') ?></title>
    <style>
      body {
      -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;
      }
      img {
      -ms-interpolation-mode: bicubic;
      }
      img {
      border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none;
      }
      body {
      height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important;
      }
      span{
        font-weight: 600;
      }
      .other-txt{
        text-align: left !important;
      }
      .other-cnt{
        width: 80% !important;
      }
      @media screen and (max-width: 600px) {
        .img-max {
          width: 100% !important; max-width: 100% !important; height: auto !important;
        }
        .max-width {
          max-width: 100% !important;
        }
        .mobile-wrapper {
          width: 85% !important; max-width: 85% !important;
        }
        .mobile-padding {
          padding-left: 5% !important; padding-right: 5% !important;
        }
        .top-bg {
          padding-top: 20px !important;
        }
        .logo{
          width: 120px !important;
        }
        .headline{
          height: 80px !important;
        }
        .headline h2{
          padding-top: 20px !important;
          font-size: 22px !important;
          font-weight: 500 !important;
        }
        .icon{
          margin: 15px 0 5px !important;
          height: auto !important;
        }
        .main-icon{
          width: 50px !important;
        }
        .main-txt h2{
          font-size: 18px !important;
        }
        .main-txt p{
          font-size: 14px !important;
          line-height: 1.5 !important;
        }
        .main-txt p br{
          display: none;
        }
        .btn{
          font-size: 14px !important;
          padding: 12px 42px !important;
        }
        .other-txt{
          font-size: 13px !important;
          margin-bottom: 0 !important;
        }
        .download img{
          width: 120px !important;
        }
        .bottom-txt{
          font-size: 12px !important;
        }
        .bottom-txt br{
          display: none;
        }
        .t-table{
          width: 90% !important;
          font-size: 14px !important;
        }
        .other-tb{
          width: 90% !important;
        }
      }

      @media screen and (max-width: 440px){
        .main-txt h2 br{
          display: none;
        }

        .main-txt h2{
          font-size: 16px !important;
        }

        .other-txt{
          font-size: 12px !important;
        }
      }

      @media screen and (max-width: 375px){
        .logo{
          width: 90px !important;
        }
        .headline{
          height: 60px !important;
        }
        .headline h2{
          padding-top: 15px !important;
          font-size: 20px !important;
          font-weight: 500 !important;
        }
        .icon{
          margin: 0 auto !important;
          height: auto !important;
        }
        .main-icon{
          width: 50px !important;
        }
        .main-txt h2{
          font-size: 15px !important;
          padding: 10px 0 !important;
          line-height: 1.5 !important;
        }
        .main-txt h2 br{
          display: none;
        }
        .main-txt p{
          font-size: 12px !important;
          line-height: 1.5 !important;
          margin-bottom: 0 !important;
        }
        .btn{
          font-size: 12px !important;
          padding: 12px 25px !important;
        }
        .download img{
          width: 120px !important;
        }
        .main-txt .greetings{
          padding-bottom: 0 !important;
        }
        .t-table{
          width: 100% !important;
          font-size: 13px !important;
        }
        .other-tb{
          width: 100% !important;
        }
      }
    </style>
</head>
<body style="!important background-color: #f5f5f5; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; height: 100% !important; margin: 0; padding: 0; width: 100% !important;"
  bgcolor="#f5f5f5">
  <table border="0" cellpadding="0" cellspacing="0" class="main"
    width="100%" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse !important; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: auto !important;">
      <tbody>
        <tr>
          <td align="center" valign="top" width="100%" background="images/bg.jpg" bgcolor="#f5f5f5"
          style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; background-color: #f5f5f5;  mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 35px 20px 0;"
          class="mobile-padding top-bg">
            <!--[if (gte mso 9)|(IE)]>
              <table align="center" border="0" cellspacing="0" cellpadding="0" width="600">
                <tr>
                  <td align="center" valign="top" width="600">
                  <![endif]-->
                  <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%"
                  style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse !important; max-width: 600px; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                    <tbody>
                      <tr>
                        <td align="center" valign="top" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 10px 0 30px;">
                          <img src="<?= $this->Url->image('logo-color.png', ['fullBase' => true]) ?>" border="0" class="logo"
                          style="-ms-interpolation-mode: bicubic; border: 0; display: block; height: auto; line-height: 100%; outline: none; text-decoration: none; width: 160px;">
                        </td>
                      </tr>
                      <tr>
                        <td align="center" bgcolor="#ffffff" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-radius: 10px 10px 0 0; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">

                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <!--[if (gte mso 9)|(IE)]>
                  </td>
                </tr>
              </table>
            <![endif]-->
          </td>
        </tr>
        <tr>
          <td align="center" height="100%" valign="top" width="100%" bgcolor="#f5f5f5" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 0 20px 20px;"
          class="mobile-padding">
            <!--[if (gte mso 9)|(IE)]>
              <table align="center" border="0" cellspacing="0" cellpadding="0" width="600">
                <tr>
                  <td align="center" valign="top" width="600">
                  <![endif]-->
                  <?= $this->fetch('content') ?>
                  <!--[if (gte mso 9)|(IE)]>
                  </td>
                </tr>
              </table>
            <![endif]-->
          </td>
        </tr>
        <tr>
          <td align="center" height="100%" valign="top" width="100%" bgcolor="#f5f5f5" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 0 15px 40px;">
            <!--[if (gte mso 9)|(IE)]>
              <table align="center" border="0" cellspacing="0" cellpadding="0" width="600">
                <tr>
                  <td align="center" valign="top" width="600">
                  <![endif]-->
                  <table id="promo" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:20px;">
                      <tbody>
                        <!-- <tr>
                          <td colspan="2" align="center"> <span style="font-size:14px; font-weight:500; margin-bottom:10px; color:#9B9B9B; font-family: -apple-system,BlinkMacSystemFont,&#39;Segoe UI&#39;,&#39;Roboto&#39;,&#39;Oxygen&#39;,&#39;Ubuntu&#39;,&#39;Cantarell&#39;,&#39;Fira Sans&#39;,&#39;Droid Sans&#39;,&#39;Helvetica Neue&#39;,sans-serif;">Get the Busha Mobile App</span>

                          </td>
                        </tr>
                        <tr>
                          <td colspan="2" height="20"></td>
                        </tr>
                        <tr class="download">
                          <td valign="top" width="50%" align="right">
                            <a href="#"
                            style="display:inline-block;margin-right:10px;">
                              <img src="iphone.png" width="160"
                              border="0" alt="">
                            </a>
                          </td>
                          <td valign="top">
                            <a href="#"
                            style="display:inline-block;margin-left:5px;">
                              <img src="android.png"
                              width="160" border="0" alt="">
                            </a>
                          </td>
                        </tr>
                        <tr>
                          <td colspan="2" height="30"></td>
                        </tr> -->
                        <tr>
                          <td align="center" colspan="2">
                            <p style="color:#a2a2a2; font-size:13px; line-height:17px; font-style:italic; margin-top:10px; font-weight:400;" class="bottom-txt">If you have received this communication in error, please delete this <br>
                              mail and notify us immediately at <a href="#" style="color: #4A90E2;">support@africanunion.com</a>.</p>
                          </td>
                        </tr>
                      </tbody>
                    </table>

                  <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%"
                  style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse !important; max-width: 600px; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                    <tbody>
                      <tr>
                        <td align="center" valign="top" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; color: #999999; font-family: Open Sans, Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 0;">
                          <p style="font-size: 14px; line-height: 20px;">© <?= date('Y') ?> African Union</p>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <!--[if (gte mso 9)|(IE)]>
                  </td>
                </tr>
              </table>
            <![endif]-->
          </td>
        </tr>
      </tbody>
    </table>
    
</body>
</html>
