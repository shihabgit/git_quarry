<div id="pop_wlog_common" class="popupBox">
    <div class="dv-popupTitle">
        <span class="clossButton popupAction" title="Close Window"> X</span>
        <span class="titleColumn">Worklog Details</span> 
    </div>


    <div class="namespan_box"><span class="wlogtitle" ></span></div>


    <input type="hidden" id="p_key" value="">   <!-- Holds verify_id -->

    <div class="wlogData"></div>

    <div class="dragSaveBox">
        <div>
            <input type="checkbox" id="pop_drag" checked=""> Drag 
            <input type="checkbox" id="pop_self_close" checked=""> Self Close
        </div>
        <hr>
        <div>  
            <div style="float: right;"><input type="button" class="btn save" id="1"  title="Verify The Worklog"  value="VERIFY"></div> 
            <div style="float: left;"><input type="button" class="btn save" id="3"  title="Mark The Worklog"  value="MARK"></div>   
            <div align="center"><input type="checkbox" id="pop_do_all" checked=""> Do the action for all same worklogs in other workcentres.</div>
    <div class="clear_boath"></div>
        </div>
    </div>
    
    <p class="responseMessage"></p>
</div>