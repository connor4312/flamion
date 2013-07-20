<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta property="og:title" content="*|MC:SUBJECT|*" />
        
        <title>{{ $subject }}</title>
		<style type="text/css">
#outlook a{padding:0;}
body{width:100% !important;} .ReadMsgBody{width:100%;} .ExternalClass{width:100%;}
body{-webkit-text-size-adjust:none;}

body{margin:0; padding:0;}
img{border:0; height:auto; line-height:100%; outline:none; text-decoration:none;}
table td{border-collapse:collapse;}
#backgroundTable{height:100% !important; margin:0; padding:0; width:100% !important;}

body, #backgroundTable{
	background-color:#FAFAFA;
	margin:20px 0 0;
}
#templateContainer{
	border: 1px solid #DDDDDD;
}
h1, .h1{
	color:#202020;
	display:block;
	font-family:Arial;
	font-size:34px;
	font-weight:bold;
	line-height:100%;
	margin-top:0;
	margin-right:0;
	margin-bottom:10px;
	margin-left:0;
	text-align:left;
}
h2, .h2{
	color:#202020;
	display:block;
	font-family:Arial;
	font-size:30px;
	font-weight:bold;
	line-height:100%;
	margin-top:0;
	margin-right:0;
	margin-bottom:10px;
	margin-left:0;
	text-align:left;
}
h3, .h3{
	color:#202020;
	display:block;
	font-family:Arial;
	font-size:26px;
	font-weight:bold;
	line-height:100%;
	margin-top:0;
	margin-right:0;
	margin-bottom:10px;
	margin-left:0;
	text-align:left;
}
h4, .h4{
	color:#202020;
	display:block;
	font-family:Arial;
	font-size:22px;
	font-weight:bold;
	line-height:100%;
	margin-top:0;
	margin-right:0;
	margin-bottom:10px;
	margin-left:0;
	text-align:left;
}
#templateHeader{
	background-color:#FFFFFF;
	border-bottom:0;
}
.headerContent{
	color:#202020;
	font-family:Arial;
	font-size:34px;
	font-weight:bold;
	line-height:100%;
	padding:0;
	text-align:center;
	vertical-align:middle;
}
.headerContent a:link, .headerContent a:visited, .headerContent a .yshortcuts, .preheaderContent div a:link, .preheaderContent div a:visited, .preheaderContent div a .yshortcuts, .bodyContent div a:link, .bodyContent div a:visited, .bodyContent div a .yshortcuts, .footerContent div a:link, .footerContent div a:visited, .footerContent div a .yshortcuts{
	color:#7faf1b;
	font-weight:normal;
	text-decoration:none;
	border-bottom:1px dotted #7faf1b;
}

#headerImage{
	height:auto;
	max-width:600px !important;
}
#templateContainer, .bodyContent{
	background-color:#FFFFFF;
}
.bodyContent div{
	color:#505050;
	font-family:Arial;
	font-size:14px;
	line-height:150%;
	text-align:left;
}

.bodyContent img{
	display:inline;
	height:auto;
}
		</style>
	</head>
    <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
    	<center>
        	<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="backgroundTable">
            	<tr>
                	<td align="center" valign="top">
                    	<table border="0" cellpadding="0" cellspacing="0" width="600" id="templateContainer">
                        	<tr>
                            	<td align="center" valign="top">
                                	<table border="0" cellpadding="0" cellspacing="0" width="600" id="templateHeader">
                                        <tr>
                                            <td class="headerContent">
                                            
                                            	<img src="http://host-seed.com/img/email-banner.jpg" style="max-width:600px;" id="headerImage campaign-icon" mc:label="header_image" mc:edit="header_image" mc:allowdesigner mc:allowtext />
                                            
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        	<tr>
                            	<td align="center" valign="top">
                                	<table border="0" cellpadding="0" cellspacing="0" width="600" id="templateBody">
                                    	<tr>
                                            <td valign="top" class="bodyContent">
                                                <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td valign="top">
                                                            <div>
																{{ $content }}
                                                            </div>
														</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <br />
                    </td>
                </tr>
            </table>
        </center>
    </body>
</html>