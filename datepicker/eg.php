<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Untitled Document</title>
        <link rel="stylesheet" href="css/datepicker.css" type="text/css" />
        <!--<link rel="stylesheet" media="screen" type="text/css" href="css/layout.css" />-->
        <title>DatePicker - jQuery plugin</title>
        <script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript" src="js/datepicker.js"></script>
        <script type="text/javascript" src="js/eye.js"></script>
        <script type="text/javascript" src="js/utils.js"></script>
        <script type="text/javascript" src="js/layout.js?ver=1.0.2"></script>
        
        <style type="text/css">
            .mytests {
                margin: 5px 10px;
            }
            
            .mytests  td, .mytests  th{
                padding : 5px;
            }
            
            h1{
                text-align: left;
            }
        </style>
        
        
    </head>

    <body>

        
         
        
        <h1>Click on text box</h1>
        <table width="500px" border="1" class="mytests">            
            <tr>
                <th>Date Of Birth</th>
                <td><input class="inputDate" id="DATE_OF_BIRTH" value="06/02/1983" /></td>
            </tr>          
            <tr>
                <th>Date Of Join</th>
                <td><input class="inputDate" id="DATE_OF_JOIN" value="" /> </td>
            </tr>
        </table>
        
        
        <h1>Click on a button</h1>
        <table width="500px" border="1"  class="mytests">            
            <tr>
                <th>Date</th>
                <td>
                            <div class="dateContainer" style="float: left; padding: 5px;">
                                From
                                <input class="dateField" id="DATE_FROM" value="" /> 
                                <img src="images/calendar.png"  class="calendarButton"> 
                            
                            </div>
                                
                          
                            <div class="dateContainer" style="float: left; padding: 5px;">
                                To
                                <input class="dateField" id="DATE_TO" value="06/02/1983" /> 
                                <span>  <img src="images/calendar.png" class="calendarButton">  </span> 
                            </div>
                </td>
            </tr>
        </table>
            
        <h1>Click on both button and text box</h1>
        <table width="500px" border="1"  class="mytests">            
            <tr>
                <th>Date</th>
                <td>
                            <div class="dateContainer" style="float: left; padding: 5px;">
                                From
                                <input class="dateField inputDate" id="DATE_FROM" value="" /> 
                                <img src="images/calendar.png"  class="calendarButton"> 
                            
                            </div>
                                
                          
                            <div class="dateContainer" style="float: left; padding: 5px;">
                                To
                                <input class="dateField inputDate" id="DATE_TO" value="06/02/1983" /> 
                                <span>  <img src="images/calendar.png" class="calendarButton">  </span> 
                            </div>
                </td>
            </tr>
        </table>
        


    </body>
</html>