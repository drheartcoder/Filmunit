
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title></title>
   </head>
   <body style="background:#1f1e1d; margin:0px; padding:0px; font-size:12px; font-family:Arial, Helvetica, sans-serif; line-height:21px; color:#bababa; text-align:justify;">
   <style type="text/css">
      .email-button a{
                     border: 1px solid
                     #302563
                     ;
                     color:
                     #302563
                     ;
                     display: block;
                     font-size: 15px;
                     height: initial;
                     letter-spacing: 0.4px;
                     margin: 0 auto;
                     max-width: 204px;
                     padding: 9px 4px;
                     text-align: center;
                     text-decoration: none;
                     text-transform: uppercase;
                     width: 100%;
                  }
   </style>
      <div style="max-width:630px;width:100%;margin:0 auto;background:#1f1e1d;">
        <div style="padding:0px 15px;">
      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
         <tr>
            <td>&nbsp;</td>
         </tr>
         <tr>
            <td  style="padding:15px; border:1px solid #3f3f3f;">
               <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                     <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                           <tr>
                              <td><a href="#"><!--{{config('app.project.name')}}--><img src="{{url('/')}}/images/logo-home.png"  alt="logo" width="182px" height="" /></a></td>
                              <td align="right" style="font-size:13px; font-weight:bold;">{{ date('d-m-Y') }}</td>
                           </tr>
                        </table>
                     </td>
                  </tr>
                  <tr>
                     <td height="10"></td>
                  </tr>
                  <tr>
                     <td  height="1" bgcolor="#3f3f3f"></td>
                  </tr>
                  <tr>
                     <td  height="10"></td>
                  </tr>
                  <tr>
                     <td>
                     
                        {!! $content !!}
                      
                     </td>
                        
                  </tr>
                  <tr>
                     <td>&nbsp;</td>
                  </tr>
                  <tr>
                     <td height="2" bgcolor="#3f3f3f"></td>
                  </tr>
                  <tr>
                     <td height="10" style="background-color:#2a2a2a;"></td>
                  </tr>
                  <tr>
                     <td style="text-align:center; color:#fff;background-color:#2a2a2a; padding-bottom:10px;"> Copyright {{ date("Y") }} by <a href="{{url('/')}}" style="text-align:center; color:#bababa;">{{ config('app.project.name') }}</a> All Right Reserved.

                     </td>
                  </tr>
               </table>
            </td>
         </tr>
         <tr>
            <td>&nbsp;</td>
         </tr>
      </table>
        </div>      
      </div>       
   </body>
</html>

