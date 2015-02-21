<!--

Notes: 
    
    1.  All popup-box must have a class "popupBox" and its own id. id start with a prefix "pop_" then "ControllerName" then "MethodName".ie:-

    for exampleid = <div id="pop_controllerName_methodName" class="popupBox">   

-->
<!--        Add Owner      -->
<div id="pop_category_add" class="popupBox">
    <div class="dv-popupTitle">
        <span class="clossButton popupAction" title="Close Window"> X</span>
        <span class="titleColumn">ADD ITEM CATEGORY</span> 
    </div>

    <!--<div class="namespan_box"><span class="namespan" >Firm: <?php echo strtoupper($this->firm_name) ?></span></div>-->



    <input type="hidden" id="p_key" value="">

    <table>


        <tr>
            <th>Name: </th>
            <td>
                <input type="text" id="itmcat_name" value="" style="width:200px;">
            </td>
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
        $('#pop_category_add .save').click(function() {

            //Showing Loading image till ajax responds
            $("#pop_category_add .responseMessage").html('<img src="images/ajax-loader.gif" /> Loading....');

            $.post(site_url + "item_category/add", {
                itmcat_name: $('#pop_category_add #itmcat_name').val(),
                itmcat_status: 1
            }, function(result) {
                if (result)
                {
                    if (result == 1)
                    {
                        // Hiding save buttun to prevent the chance for re-entering of data.
                        $('#pop_category_add .save').hide();

                        // Listing newly created item_category in related the dropdown.
                        setOptions('#pop_head_add #itmcat_id', 'item_category/get_active_cats');


                        $("#pop_category_add .responseMessage").html('<div class="pop_success"><img src="images/success_1.gif" width="15" height="15"  /> Data saved successfully!!!</div>');

                        // Following code is for when popup is used with items/add. 
                        // The variable clsfunc is defined @ from where the popup was loaded.
                        if (clsfunc == 'items/add')
                        {
                            setOptions('.sec_table #itmcat_id', 'item_category/get_active_cats');

                            // Clossing popup box automatically after 1 seconds.
                            setTimeout(function() {
                                $('#pop_category_add').hide();
                            }, 1000);
                        }

                        // Following code is for when popup is used with item_heads/index.
                        else if (clsfunc == 'item_heads/index')
                        {
                            // Refreshing page automatically after 1 seconds.
                            setTimeout(function() {
                                location.reload(true);
                            }, 1000);
                        }
                    }
                    else
                        $("#pop_category_add .responseMessage").html(result);
                }
            });


        });

    });


    function init_pop_category_add(action) {
        // Setting initial values of elements.                

        //You have to escape the brackets with \\ if the id is array.
        $('#pop_category_add #itmcat_name').val('');

        $('#pop_category_add #pop_drag').prop('checked', 'true');
        $('#pop_category_add #pop_self_close').prop('checked', 'true');
        $('#pop_category_add .save').show();
        $("#pop_category_add .responseMessage").html('');

        //Making the popup box draggable.
        $("#pop_category_add").draggable();
        dragUndrag($("#pop_category_add"));

        // Initializing Popup-Window settings
        popupSettings($("#pop_category_add"));
    }







</script>
