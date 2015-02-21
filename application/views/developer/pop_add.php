<!--

Notes: 
    
    1.  All popup-box must have a class "popupBox" and its own id. id start with a prefix "pop_" then "ControllerName" then "MethodName".ie:-

    for exampleid = <div id="pop_controllerName_methodName" class="popupBox">   

-->
<!--        Add Owner      -->
<div id="pop_tasks_add" class="popupBox">
    <div class="dv-popupTitle">
        <span class="clossButton popupAction" title="Close Window"> X</span>
        <span class="titleColumn">ADD TASK</span> 
    </div>

    <table>


        <tr>
            <th>Parent Id: </th>
            <td>
                <input type="text" class="intOnly" id="tsk_parent" value="" style="width:150px;">
            </td>
        </tr>

        <tr>
            <th>Name: </th>
            <td>
                <input type="text" id="tsk_name" value="" style="width:150px;">
               
            </td>
        </tr>

        <tr>
            <th>Description: </th>
            <td>
                <input type="text" id="tsk_description" value="" style="width:150px;">
               
            </td>
        </tr>
        
        <tr>
            <th>Url: </th>
            <td>
                <input type="text" id="tsk_url" value="" style="width:150px;">
            </td>
        </tr>

        <tr>
            <th>Position: </th>
            <td>
                <input type="text" class="intOnly" id="tsk_pos" value="" style="width:150px;">
            </td>
        </tr>
        

        

        <tr>
            <td> <input type="checkbox" id="common"> Common task.  </td>
            <td> <input type="checkbox" id="show"> Show in menu list.</td>
        </tr>
        
        
        
        
        
        
        

    </table>
    <div class="dragSaveBox">
        <div class="dragColumn">
            <input type="checkbox" id="pop_drag"> Drag 
            <input type="checkbox" id="pop_self_close"> Self Close
        </div>
        <div class="saveColumn">
        <!--<input type="button" class="btn"  title="Save Data"  value="SAVE">-->
            <img src="images/save.png" class="save" title="Save Data"> 

        </div>
    </div>
    <div class="clear_boath"></div>
    <p class="responseMessage"></p>
</div>




<script type="text/javascript">



    $(document).ready(function() {

        //Saving data
        $('#pop_tasks_add .save').click(function() {

            //Showing Loading image till ajax responds
            $("#pop_tasks_add .responseMessage").html('<img src="images/ajax-loader.gif" /> Loading....');



            //     

            $.ajax({
                url: site_url + "developer/add",
                data: {
                    tsk_parent: $('#pop_tasks_add #tsk_parent').val(), 
                    tsk_name: $('#pop_tasks_add #tsk_name').val(), 
                    tsk_description: $('#pop_tasks_add #tsk_description').val(), 
                    tsk_url: $('#pop_tasks_add #tsk_url').val() ,
                    tsk_pos: $('#pop_tasks_add #tsk_pos').val() , 
                    common: $('#pop_tasks_add #common').prop('checked'),
                    show:   $('#pop_tasks_add #show').prop('checked')
                },
                type: 'post',
                success: function(result) {
                    //alert(result);
                    if (result == 1)
                    {
                        // Hiding save buttun to prevent the chance for re-entering of data.
                        $('#pop_tasks_add .save').hide();

                        var msg = '<div class="pop_success"><img src="images/success_1.gif" width="15" height="15"/> Data saved successfully!!!</div>';

                        $("#pop_tasks_add .responseMessage").html(msg);

                        // Refresh browser 
                        setTimeout(function() {
                            location.reload(true)
                        }, 1000);
                    }
                    else
                        $("#pop_tasks_add .responseMessage").html(result);

                }
            });

        });

    });


    function init_pop_tasks_add(action) {
        // Setting initial values of elements.    
        
        $('#pop_tasks_add #tsk_parent').val('');
        $('#pop_tasks_add #tsk_name').val('');
        $('#pop_tasks_add #tsk_description').val('');
        $('#pop_tasks_add #tsk_url').val('');
        $('#pop_tasks_add #tsk_pos').val('');

        $('#pop_tasks_add #common').prop('checked', '');
        $('#pop_tasks_add #show').prop('checked', 'true');

        $('#pop_tasks_add #pop_drag').prop('checked', 'true');
        $('#pop_tasks_add #pop_self_close').prop('checked', 'true');
        $('#pop_tasks_add .save').show();
        $("#pop_tasks_add .responseMessage").html('');

        //Making the popup box draggable.
        $("#pop_tasks_add").draggable();
        dragUndrag($("#pop_tasks_add"));

        // Initializing Popup-Window settings
        popupSettings($("#pop_tasks_add"));
    }







</script>
