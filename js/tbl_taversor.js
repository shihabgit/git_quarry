// Focusing to next/previous/above/below input element having className = 'nextInput' on pressing ENTER/ARROW key.

/*
 
 Traversing table must have the classname 'tbl_traversor'. and traversing elements must have the classname 'nextInput' and the parent row that contain the elements must have the className 'tr_traversor';
 
 Table structure
 ---------------
 <table class="tbl_traversor">
 <tbody>
 <tr class="tr_traversor">
 <td> <input class="nextInput"> </td>
 <td> <input class="nextInput"> </td>
 <td> <input class="nextInput"> </td>
 </tr> 
 </tbody>
 </table>
 
 */

// Declaring global variables.


// If you want to append a new row to the table when traversing reached at last column of last row, make the value of addRow = true by calling the function initTraversor(). It can be called from your php file (view page)
addRow = true;

// If you want to focus to the first '.nextInput' of Tbl:tbl_traversor on page load, make the value of focus_nextInput = true  by calling the function initTraversor(). It can be called from your php file (view page)
focus_nextInput = true;


// If you want all inputs defaultly to be empty in the newly create row, make the value of clearInputs = true  by calling the function initTraversor(). It can be called from your php file (view page)
clearInputs = true;


// If you want your own values for new '.nextInput' in newly created row, you can define a function with name = your_option in your view page and add values manually for each inputs. (Make sure the value of variable 'clearInputs = false') The function must be in global scope.(If you no need your own values for new inputs, no need to define the function anywhere).The syntax is follows.


//                // The function is defined for js/tbl_traversor.js
//                function your_option(row)
//                {
//                    row.find('#some_id').val('Hellow world');
//                }







$(document).ready(function() {

    var leftArrow = 37;
    var upArrow = 38;
    var rightArrow = 39;
    var downArrow = 40;
    var enter = 13;

    // On page load, focusing to the first element having class 'nextInput'.
    if (focus_nextInput)
        $('.nextInput').eq(0).focus();

    $('.tbl_traversor .nextInput').keyup(function(e) {

        var curRowIndex = $(this).closest('.tbl_traversor').find('tr.tr_traversor').index($(this).closest('tr.tr_traversor'));
        var curColIndex = $(this).closest('tr.tr_traversor').find('td').index($(this).closest('td'));
        var lastRow = $(this).closest('.tbl_traversor').find('tbody tr:last');
        var lastRowIndex = lastRow.index();
        var lastColIndex = $(this).closest('.tbl_traversor').find('tbody td:last').index();
        var is_last_col = ((curRowIndex == lastRowIndex) && (curColIndex == lastColIndex)) ? true : false;
        var indexInTable = $(this).closest('.tbl_traversor').find('.nextInput').index($(this));
        var indexInRow = $(this).closest('tr.tr_traversor').find('.nextInput').index($(this));
        var key = e.keyCode || e.which;

        // move back if the key is SHIFT + ENTER combination or Left Arrow Key.
        if ((key == enter && e.shiftKey) || (key == leftArrow)) {
            $(this).closest('.tbl_traversor').find('.nextInput').eq(indexInTable - 1).focus().select();
        }

        // Move forward if the key is ENTER. In the case of "textarea", allow to insert a new line.
        else if ((key === 13 && !$(this).is("textarea")) || (key == rightArrow)) {
            if (addRow && is_last_col)
                createRow(lastRow);
            else
                $(this).closest('.tbl_traversor').find('.nextInput').eq(indexInTable + 1).focus().select();
        }

        // Move up if upArrow pressed. In the case of "select" / "textarea", allow traversing though inside the element itself.
        else if (key == upArrow && !$(this).is("select") && !$(this).is("textarea")) {
            $(this).closest('.tbl_traversor').find('tr.tr_traversor').eq(curRowIndex - 1).find('.nextInput').eq(indexInRow).focus().select();
        }

        // Move up if downArrow pressed. In the case of "select" / "textarea", allow traversing though inside the element itself.
        else if (key == downArrow && !$(this).is("select") && !$(this).is("textarea")) {
            if (addRow && is_last_col)
                createRow(lastRow);
            else
                $(this).closest('.tbl_traversor').find('tr.tr_traversor').eq(curRowIndex + 1).find('.nextInput').eq(indexInRow).focus().select();
        }

    });



// Preventing form submiting when ENTER key press.
// Before calling the keyup event, the keypressed event will be called. So blocking this.
    $('.tbl_traversor .nextInput').keypress(function(e) {
        var key = e.keyCode || e.which;

        // if the input is a 'select' element, the left/right arrows will change its selected index. so blocking this.
        if (key == 13 || key == leftArrow || key == rightArrow)
        {
            e.preventDefault();
            return false;
        }
    });

    // Deleting a row
    $('.tbl_traversor .remove_row').click(function() {
        if ($(this).closest('.tbl_traversor').find('.tr_traversor').length > 1)
        {   
            var myTable = $(this).closest('.tbl_traversor');
            $(this).closest('.tr_traversor').remove();
            setRowNo($(myTable,'.tr_traversor'));
        }
    });

    //After incorrect validation, hiding error messages when focusing in related input.            
    $('.tbl_traversor .nextInput').focusin(function() {

        if ($(this).hasClass('dateField'))
        {
            $(this).closest('.dateContainer').find('.dialog-box-border').hide();
        }
        else
            $(this).parent().find('.dialog-box-border').hide();
    });
    
    $('.tbl_traversor .nextInput').dblclick(function(){
        $(this).val('');
    })

    // Appending row
    function createRow(lastRow)
    {
        lastRow.clone(true).insertAfter(lastRow);
        lastRow = lastRow.closest('.tbl_traversor tbody').find('tr:last');

        // If the user want to be empty all '.nextInput' in the new row.
        if (clearInputs)
        {
            lastRow.find('.nextInput').val('');
            lastRow.find('.nextInput:first').focus();
        }

        // If you want your own values for new '.nextInput', you can define a function with name = your_option in your view page. The function must be in global scope
        else if (typeof your_option == 'function')
        {
            // The function must be defined in your view page.
            your_option(lastRow);
        }
        else
            alert('The function "your_option" must be defied in your veiw page in global scope.');

       setRowNo(lastRow) ;    

    }
    
    function setRowNo(row)
    {
        row.closest('.tbl_traversor').find('.tr_traversor').each(function(){
            var rowNo = $(this).index();
            rowNo += 1;
            $(this).find('.rowNo').html(rowNo);
        });
    }



});

//Determins column append or not.
function initTraversor(status1, status2, status3)
{
    addRow = status1;
    focus_nextInput = status2;
    clearInputs = status3;
}