<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
</head>
<body style="width:100%; margin:0; padding:0; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; font-family: 'Poppins', sans-serif;">

<table style="width: 100%;    background-color: #f2f2f2;">
    <tbody>
    <tr>
        <td style="">
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" class="templateContainer"
                   style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;max-width: 600px !important;background: white">
                <tbody>
                <tr>
                    <td valign="top" class="bodyContainer"
                        style="background:transparent none no-repeat center/cover;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: transparent;background-image: none;background-repeat: no-repeat;background-position: center;background-size: cover;border-top: 0;border-bottom: 0;padding-top: 0;padding-bottom: 0;">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnImageBlock"
                               style="min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                            <tbody class="mcnImageBlockOuter">
                            <tr>
                                <td valign="top"
                                    style="padding: 0px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"
                                    class="mcnImageBlockInner">
                                    <table align="left" width="100%" border="0" cellpadding="0" cellspacing="0"
                                           class="mcnImageContentContainer"
                                           style="min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                        <tbody>
                                        <tr>
                                            <td class="mcnImageContent" valign="top"
                                                style="padding-right: 0px;padding-left: 0px;padding-top: 0;padding-bottom: 0;text-align: center;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">


                                                <img align="center" alt=""
                                                     src="@yield('mail_banner')"
                                                     width="600"
                                                     style="max-width: 600px;padding-bottom: 0;display: inline !important;vertical-align: bottom;border: 0;height: auto;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;"
                                                     class="mcnImage">


                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock"
                               style="min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                            <tbody class="mcnTextBlockOuter">
                            <tr>
                                <td valign="top" class="mcnTextBlockInner"
                                    style="padding-top: 9px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                    <!--[if mso]>
                                    <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%"
                                           style="width:100%;">
                                        <tr>
                                    <![endif]-->

                                    <!--[if mso]>
                                    <td valign="top" width="600" style="width:600px;">
                                    <![endif]-->
                                    <table align="left" border="0" cellpadding="0" cellspacing="0"
                                           style="max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"
                                           width="100%" class="mcnTextContentContainer">
                                        <tbody>
                                        <tr>

                                            <td valign="top" class="mcnTextContent"
                                                style="padding-top: 0;padding-right: 18px;padding-bottom: 9px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #808080;font-family: Helvetica;font-size: 16px;line-height: 150%;text-align: left;">

                                                <p style="text-align: justify;margin: 10px 0;padding: 0;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;color: #808080;font-family: Helvetica;font-size: 16px;line-height: 150%;">
                                                    @yield('mail_content')
                                                </p>
                                                {{--<p>--}}
                                                    {{--Sincerely,<br>--}}
                                                    {{--Your friends at <strong>FireApps</strong>--}}
                                                {{--</p>--}}

                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <!--[if mso]>
                                    </td>
                                    <![endif]-->

                                    <!--[if mso]>
                                    </tr>
                                    </table>
                                    <![endif]-->
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        {{--<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnDividerBlock"--}}
                               {{--style="min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;table-layout: fixed !important;">--}}
                            {{--<tbody class="mcnDividerBlockOuter">--}}
                            {{--<tr>--}}
                                {{--<td class="mcnDividerBlockInner"--}}
                                    {{--style="min-width: 100%;padding: 9px 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">--}}
                                    {{--<table class="mcnDividerContent" border="0" cellpadding="0" cellspacing="0"--}}
                                           {{--width="100%"--}}
                                           {{--style="min-width: 100%;border-top: 1px solid #E0E0E0;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">--}}
                                        {{--<tbody>--}}
                                        {{--<tr>--}}
                                            {{--<td style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">--}}
                                                {{--<span></span>--}}
                                            {{--</td>--}}
                                        {{--</tr>--}}
                                        {{--</tbody>--}}
                                    {{--</table>--}}
                                {{--</td>--}}
                            {{--</tr>--}}
                            {{--</tbody>--}}
                        {{--</table>--}}
                        {{--<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnDividerBlock"--}}
                               {{--style="min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;table-layout: fixed !important;">--}}
                            {{--<tbody class="mcnDividerBlockOuter">--}}
                            {{--<tr>--}}
                                {{--<td class="mcnDividerBlockInner"--}}
                                    {{--style="min-width: 100%;padding: 18px 18px 0px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">--}}
                                    {{--<table class="mcnDividerContent" border="0" cellpadding="0" cellspacing="0"--}}
                                           {{--width="100%"--}}
                                           {{--style="min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">--}}
                                        {{--<tbody>--}}
                                        {{--<tr>--}}
                                            {{--<td style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">--}}
                                                {{--<span></span>--}}
                                            {{--</td>--}}
                                        {{--</tr>--}}
                                        {{--</tbody>--}}
                                    {{--</table>--}}
                                {{--</td>--}}
                            {{--</tr>--}}
                            {{--</tbody>--}}
                        {{--</table>--}}
                        {{--<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnButtonBlock"--}}
                               {{--style="min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">--}}
                            {{--<tbody class="mcnButtonBlockOuter">--}}
                            {{--<tr>--}}
                                {{--<td style="padding-top: 0;padding-right: 18px;padding-bottom: 18px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"--}}
                                    {{--valign="top" align="center" class="mcnButtonBlockInner">--}}
                                    {{--<table border="0" cellpadding="0" cellspacing="0" width="100%"--}}
                                           {{--class="mcnButtonContentContainer"--}}
                                           {{--style="border-collapse: separate !important;border-radius: 3px;background-color: #F63663;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">--}}
                                        {{--<tbody>--}}
                                        {{--<tr>--}}
                                            {{--<td align="center" valign="middle" class="mcnButtonContent"--}}
                                                {{--style="font-family: Helvetica;font-size: 18px;padding: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">--}}
                                                {{--<a class="mcnButton " title="Get more FREE awesome apps"--}}
                                                   {{--href="http://bit.ly/shopifyfireapps" target="_self"--}}
                                                   {{--style="font-weight: bold;letter-spacing: -0.5px;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;display: block;">Get--}}
                                                    {{--more FREE awesome apps</a>--}}
                                            {{--</td>--}}
                                        {{--</tr>--}}
                                        {{--</tbody>--}}
                                    {{--</table>--}}
                                {{--</td>--}}
                            {{--</tr>--}}
                            {{--</tbody>--}}
                        {{--</table>--}}
                    {{--</td>--}}
                {{--</tr>--}}
                {{--</tbody>--}}
            {{--</table>--}}

            {{--<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" class="templateContainer"--}}
                   {{--style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;max-width: 600px !important;">--}}
                {{--<tbody>--}}
                {{--<tr>--}}
                    {{--<td valign="top" class="footerContainer"--}}
                        {{--style="background:transparent none no-repeat center/cover;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: transparent;background-image: none;background-repeat: no-repeat;background-position: center;background-size: cover;border-top: 0;border-bottom: 0;padding-top: 0;padding-bottom: 0;">--}}
                        {{--<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnDividerBlock"--}}
                               {{--style="min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;table-layout: fixed !important;">--}}
                            {{--<tbody class="mcnDividerBlockOuter">--}}
                            {{--<tr>--}}
                                {{--<td class="mcnDividerBlockInner"--}}
                                    {{--style="min-width: 100%;padding: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">--}}
                                    {{--<table class="mcnDividerContent" border="0" cellpadding="0" cellspacing="0"--}}
                                           {{--width="100%"--}}
                                           {{--style="min-width: 100%;border-top: 2px solid #505050;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">--}}
                                        {{--<tbody>--}}
                                        {{--<tr>--}}
                                            {{--<td style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">--}}
                                                {{--<span></span>--}}
                                            {{--</td>--}}
                                        {{--</tr>--}}
                                        {{--</tbody>--}}
                                    {{--</table>--}}
                                    {{--<!----}}
                                                    {{--<td class="mcnDividerBlockInner" style="padding: 18px;">--}}
                                                    {{--<hr class="mcnDividerContent" style="border-bottom-color:none; border-left-color:none; border-right-color:none; border-bottom-width:0; border-left-width:0; border-right-width:0; margin-top:0; margin-right:0; margin-bottom:0; margin-left:0;" />--}}
                                    {{---->--}}
                                {{--</td>--}}
                            {{--</tr>--}}
                            {{--</tbody>--}}
                        {{--</table>--}}
                        {{--<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnFollowBlock"--}}
                               {{--style="min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">--}}
                            {{--<tbody class="mcnFollowBlockOuter">--}}
                            {{--<tr>--}}
                                {{--<td align="center" valign="top"--}}
                                    {{--style="padding: 9px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"--}}
                                    {{--class="mcnFollowBlockInner">--}}
                                    {{--<table border="0" cellpadding="0" cellspacing="0" width="100%"--}}
                                           {{--class="mcnFollowContentContainer"--}}
                                           {{--style="min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">--}}
                                        {{--<tbody>--}}
                                        {{--<tr>--}}
                                            {{--<td align="center"--}}
                                                {{--style="padding-left: 9px;padding-right: 9px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">--}}
                                                {{--<table border="0" cellpadding="0" cellspacing="0" width="100%"--}}
                                                       {{--style="min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"--}}
                                                       {{--class="mcnFollowContent">--}}
                                                    {{--<tbody>--}}
                                                    {{--<tr>--}}
                                                        {{--<td align="center" valign="top"--}}
                                                            {{--style="padding-top: 9px;padding-right: 9px;padding-left: 9px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">--}}
                                                            {{--<table align="center" border="0" cellpadding="0"--}}
                                                                   {{--cellspacing="0"--}}
                                                                   {{--style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">--}}
                                                                {{--<tbody>--}}
                                                                {{--<tr>--}}
                                                                    {{--<td align="center" valign="top"--}}
                                                                        {{--style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">--}}
                                                                        {{--<!--[if mso]>--}}
                                                                        {{--<table align="center" border="0" cellspacing="0"--}}
                                                                               {{--cellpadding="0">--}}
                                                                            {{--<tr>--}}
                                                                        {{--<![endif]-->--}}

                                                                        {{--<!--[if mso]>--}}
                                                                        {{--<td align="center" valign="top">--}}
                                                                        {{--<![endif]-->--}}


                                                                        {{--<table align="left" border="0" cellpadding="0"--}}
                                                                               {{--cellspacing="0"--}}
                                                                               {{--style="display: inline;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">--}}
                                                                            {{--<tbody>--}}
                                                                            {{--<tr>--}}
                                                                                {{--<td valign="top"--}}
                                                                                    {{--style="padding-right: 10px;padding-bottom: 9px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"--}}
                                                                                    {{--class="mcnFollowContentItemContainer">--}}
                                                                                    {{--<table border="0" cellpadding="0"--}}
                                                                                           {{--cellspacing="0" width="100%"--}}
                                                                                           {{--class="mcnFollowContentItem"--}}
                                                                                           {{--style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">--}}
                                                                                        {{--<tbody>--}}
                                                                                        {{--<tr>--}}
                                                                                            {{--<td align="left"--}}
                                                                                                {{--valign="middle"--}}
                                                                                                {{--style="padding-top: 5px;padding-right: 10px;padding-bottom: 5px;padding-left: 9px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">--}}
                                                                                                {{--<table align="left"--}}
                                                                                                       {{--border="0"--}}
                                                                                                       {{--cellpadding="0"--}}
                                                                                                       {{--cellspacing="0"--}}
                                                                                                       {{--width=""--}}
                                                                                                       {{--style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">--}}
                                                                                                    {{--<tbody>--}}
                                                                                                    {{--<tr>--}}

                                                                                                        {{--<td align="center"--}}
                                                                                                            {{--valign="middle"--}}
                                                                                                            {{--width="24"--}}
                                                                                                            {{--class="mcnFollowIconContent"--}}
                                                                                                            {{--style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">--}}
                                                                                                            {{--<a href="http://www.facebook.com/fireapps.io/"--}}
                                                                                                               {{--target="_blank"--}}
                                                                                                               {{--style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><img--}}
                                                                                                                        {{--src="https://cdn-images.mailchimp.com/icons/social-block-v2/color-facebook-48.png"--}}
                                                                                                                        {{--style="display: block;border: 0;height: auto;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;"--}}
                                                                                                                        {{--height="24"--}}
                                                                                                                        {{--width="24"--}}
                                                                                                                        {{--class=""></a>--}}
                                                                                                        {{--</td>--}}


                                                                                                    {{--</tr>--}}
                                                                                                    {{--</tbody>--}}
                                                                                                {{--</table>--}}
                                                                                            {{--</td>--}}
                                                                                        {{--</tr>--}}
                                                                                        {{--</tbody>--}}
                                                                                    {{--</table>--}}
                                                                                {{--</td>--}}
                                                                            {{--</tr>--}}
                                                                            {{--</tbody>--}}
                                                                        {{--</table>--}}

                                                                        {{--<!--[if mso]>--}}
                                                                        {{--</td>--}}
                                                                        {{--<![endif]-->--}}

                                                                        {{--<!--[if mso]>--}}
                                                                        {{--<td align="center" valign="top">--}}
                                                                        {{--<![endif]-->--}}


                                                                        {{--<table align="left" border="0" cellpadding="0"--}}
                                                                               {{--cellspacing="0"--}}
                                                                               {{--style="display: inline;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">--}}
                                                                            {{--<tbody>--}}
                                                                            {{--<tr>--}}
                                                                                {{--<td valign="top"--}}
                                                                                    {{--style="padding-right: 10px;padding-bottom: 9px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"--}}
                                                                                    {{--class="mcnFollowContentItemContainer">--}}
                                                                                    {{--<table border="0" cellpadding="0"--}}
                                                                                           {{--cellspacing="0" width="100%"--}}
                                                                                           {{--class="mcnFollowContentItem"--}}
                                                                                           {{--style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">--}}
                                                                                        {{--<tbody>--}}
                                                                                        {{--<tr>--}}
                                                                                            {{--<td align="left"--}}
                                                                                                {{--valign="middle"--}}
                                                                                                {{--style="padding-top: 5px;padding-right: 10px;padding-bottom: 5px;padding-left: 9px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">--}}
                                                                                                {{--<table align="left"--}}
                                                                                                       {{--border="0"--}}
                                                                                                       {{--cellpadding="0"--}}
                                                                                                       {{--cellspacing="0"--}}
                                                                                                       {{--width=""--}}
                                                                                                       {{--style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">--}}
                                                                                                    {{--<tbody>--}}
                                                                                                    {{--<tr>--}}

                                                                                                        {{--<td align="center"--}}
                                                                                                            {{--valign="middle"--}}
                                                                                                            {{--width="24"--}}
                                                                                                            {{--class="mcnFollowIconContent"--}}
                                                                                                            {{--style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">--}}
                                                                                                            {{--<a href="http://bit.ly/fireapps_youtube"--}}
                                                                                                               {{--target="_blank"--}}
                                                                                                               {{--style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><img--}}
                                                                                                                        {{--src="https://cdn-images.mailchimp.com/icons/social-block-v2/color-youtube-48.png"--}}
                                                                                                                        {{--style="display: block;border: 0;height: auto;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;"--}}
                                                                                                                        {{--height="24"--}}
                                                                                                                        {{--width="24"--}}
                                                                                                                        {{--class=""></a>--}}
                                                                                                        {{--</td>--}}


                                                                                                    {{--</tr>--}}
                                                                                                    {{--</tbody>--}}
                                                                                                {{--</table>--}}
                                                                                            {{--</td>--}}
                                                                                        {{--</tr>--}}
                                                                                        {{--</tbody>--}}
                                                                                    {{--</table>--}}
                                                                                {{--</td>--}}
                                                                            {{--</tr>--}}
                                                                            {{--</tbody>--}}
                                                                        {{--</table>--}}

                                                                        {{--<!--[if mso]>--}}
                                                                        {{--</td>--}}
                                                                        {{--<![endif]-->--}}

                                                                        {{--<!--[if mso]>--}}
                                                                        {{--<td align="center" valign="top">--}}
                                                                        {{--<![endif]-->--}}


                                                                        {{--<table align="left" border="0" cellpadding="0"--}}
                                                                               {{--cellspacing="0"--}}
                                                                               {{--style="display: inline;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">--}}
                                                                            {{--<tbody>--}}
                                                                            {{--<tr>--}}
                                                                                {{--<td valign="top"--}}
                                                                                    {{--style="padding-right: 0;padding-bottom: 9px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"--}}
                                                                                    {{--class="mcnFollowContentItemContainer">--}}
                                                                                    {{--<table border="0" cellpadding="0"--}}
                                                                                           {{--cellspacing="0" width="100%"--}}
                                                                                           {{--class="mcnFollowContentItem"--}}
                                                                                           {{--style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">--}}
                                                                                        {{--<tbody>--}}
                                                                                        {{--<tr>--}}
                                                                                            {{--<td align="left"--}}
                                                                                                {{--valign="middle"--}}
                                                                                                {{--style="padding-top: 5px;padding-right: 10px;padding-bottom: 5px;padding-left: 9px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">--}}
                                                                                                {{--<table align="left"--}}
                                                                                                       {{--border="0"--}}
                                                                                                       {{--cellpadding="0"--}}
                                                                                                       {{--cellspacing="0"--}}
                                                                                                       {{--width=""--}}
                                                                                                       {{--style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">--}}
                                                                                                    {{--<tbody>--}}
                                                                                                    {{--<tr>--}}

                                                                                                        {{--<td align="center"--}}
                                                                                                            {{--valign="middle"--}}
                                                                                                            {{--width="24"--}}
                                                                                                            {{--class="mcnFollowIconContent"--}}
                                                                                                            {{--style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">--}}
                                                                                                            {{--<a href="https://apps.shopify.com/ali-reviews"--}}
                                                                                                               {{--target="_blank"--}}
                                                                                                               {{--style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><img--}}
                                                                                                                        {{--src="https://cdn-images.mailchimp.com/icons/social-block-v2/color-link-48.png"--}}
                                                                                                                        {{--style="display: block;border: 0;height: auto;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;"--}}
                                                                                                                        {{--height="24"--}}
                                                                                                                        {{--width="24"--}}
                                                                                                                        {{--class=""></a>--}}
                                                                                                        {{--</td>--}}


                                                                                                    {{--</tr>--}}
                                                                                                    {{--</tbody>--}}
                                                                                                {{--</table>--}}
                                                                                            {{--</td>--}}
                                                                                        {{--</tr>--}}
                                                                                        {{--</tbody>--}}
                                                                                    {{--</table>--}}
                                                                                {{--</td>--}}
                                                                            {{--</tr>--}}
                                                                            {{--</tbody>--}}
                                                                        {{--</table>--}}

                                                                        {{--<!--[if mso]>--}}
                                                                        {{--</td>--}}
                                                                        {{--<![endif]-->--}}

                                                                        {{--<!--[if mso]>--}}
                                                                        {{--</tr>--}}
                                                                        {{--</table>--}}
                                                                        {{--<![endif]-->--}}
                                                                    {{--</td>--}}
                                                                {{--</tr>--}}
                                                                {{--</tbody>--}}
                                                            {{--</table>--}}
                                                        {{--</td>--}}
                                                    {{--</tr>--}}
                                                    {{--</tbody>--}}
                                                {{--</table>--}}
                                            {{--</td>--}}
                                        {{--</tr>--}}
                                        {{--</tbody>--}}
                                    {{--</table>--}}

                                {{--</td>--}}
                            {{--</tr>--}}
                            {{--</tbody>--}}
                        {{--</table>--}}
                        {{--<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock"--}}
                               {{--style="min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">--}}
                            {{--<tbody class="mcnTextBlockOuter">--}}
                            {{--<tr>--}}
                                {{--<td valign="top" class="mcnTextBlockInner"--}}
                                    {{--style="padding-top: 9px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">--}}
                                    {{--<!--[if mso]>--}}
                                    {{--<table align="left" border="0" cellspacing="0" cellpadding="0" width="100%"--}}
                                           {{--style="width:100%;">--}}
                                        {{--<tr>--}}
                                    {{--<![endif]-->--}}

                                    {{--<!--[if mso]>--}}
                                    {{--<td valign="top" width="600" style="width:600px;">--}}
                                    {{--<![endif]-->--}}
                                    {{--<table align="left" border="0" cellpadding="0" cellspacing="0"--}}
                                           {{--style="max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"--}}
                                           {{--width="100%" class="mcnTextContentContainer">--}}
                                        {{--<tbody>--}}
                                        {{--<tr>--}}

                                            {{--<td valign="top" class="mcnTextContent"--}}
                                                {{--style="padding-top: 0;padding-right: 18px;padding-bottom: 9px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #333;font-family: Helvetica;font-size: 12px;line-height: 150%;text-align: center;">--}}
                                                {{--If you wish to unsubscribe <a href="{{ route('email.unsub') }}" style="color:#F63663">click here</a><br>--}}
                                                {{--FireApps - Premium Apps for Shopify<br>--}}
                                                {{--1017/23 Lac Long Quan <br>--}}
                                                {{--HCM, Tan Binh 700000<br>--}}
                                                {{--Vietnam--}}
                                                {{--<br><br>--}}
                                                {{--FireApps Team., FireApps Ltd.--}}
                                                {{--View our <a href="https://fireapps.io/privacy-policy" style="color:#F63663">privacy policy</a>--}}
                                            {{--</td>--}}
                                        {{--</tr>--}}
                                        {{--</tbody>--}}
                                    {{--</table>--}}
                                    {{--<!--[if mso]>--}}
                                    {{--</td>--}}
                                    {{--<![endif]-->--}}

                                    {{--<!--[if mso]>--}}
                                    {{--</tr>--}}
                                    {{--</table>--}}
                                    {{--<![endif]-->--}}
                                {{--</td>--}}
                            {{--</tr>--}}
                            {{--</tbody>--}}
                        {{--</table>--}}
                    {{--</td>--}}
                {{--</tr>--}}
                {{--</tbody>--}}
            {{--</table>--}}

        </td>
    </tr>
    </tbody>
</table>


</body>
</html>