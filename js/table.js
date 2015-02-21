// JavaScript Document

//Setting width of the each column of the header section of all tables having class="tbl_grid" same to its corresponding 'tbody td'.
function arangeGrid()
{
    if ($('.datagrid .tbl_grid').hasClass('no-data-to-display'))
        return;

    $('.datagrid .tbl_grid_head_container .tbl_grid_head').each(function() {

        var sourceOuterWidth = $(this).outerWidth();
        var col = $(this).index();




        /*<<=============== Setting the width of footer colums =============================================>>*/

        // Getting target column and widths.
        var target = $(this).closest('.datagrid').find('.auto_set_width .tbl_grid_head').eq(col);
        var targetWidth = target.width();
        var targetOuterWidth = target.outerWidth();
        var targetExtraWidth = targetOuterWidth - targetWidth;

        // Calculating new width for the target column.
        var newWidth = sourceOuterWidth - targetExtraWidth;

        // Resetting targetWidth.
        target.width(newWidth);
        /*<<================================================================================================>>*/

        //alert('col:'+col+'\n'+'SourceWidth: '+$(this).width()+'\n'+'sourceOuterWidth:'+sourceOuterWidth+'\n'+'targetExtraWidth:'+ targetExtraWidth +'\n'+'targetWidth:'+targetWidth +'\n'+'targetOuterWidth:'+targetOuterWidth+'\n'+'New Width: '+target.width()+'\n'+'NewOuterWidth:'+target.outerWidth());



        /*<<=============== Setting the width of tbody <td> colums =========================================>>*/

        // Getting the index of the last target column.
        var lastCol = $(this).closest('.datagrid').find('.tbl_grid tbody tr:first td:last').index();

        // Leaving the last column idle. Otherwise our aim will fail.
        if (col == lastCol)
        {
            var lastFooterColumn = $(this).closest('.datagrid').find('.auto_set_width .tbl_grid_head').eq(col);
            var lastTbodyColumn = $(this).closest('.datagrid').find('.tbl_grid tbody tr:first td').eq(col);
            var lastTbodyColumnOuterWidth = lastTbodyColumn.outerWidth();
            var lastFooterColumnWidth = lastFooterColumn.width();
            var lastFooterColumnOuterWidth = lastFooterColumn.outerWidth();
            var lastFooterColumnExtraWidth = lastFooterColumnOuterWidth - lastFooterColumnWidth;

            // Calculating new width for the target column.
            var lastFooterColumnNewWidth = lastTbodyColumnOuterWidth - lastFooterColumnExtraWidth;

            // Resetting targetWidth.
            lastFooterColumn.width(lastFooterColumnNewWidth);

            return;
        }

        // Getting target column and widths.
        target = $(this).closest('.datagrid').find('.tbl_grid tbody tr:first td').eq(col);
        targetWidth = target.width();
        targetOuterWidth = target.outerWidth();
        targetExtraWidth = targetOuterWidth - targetWidth;

        // Calculating new width for the target column.
        newWidth = sourceOuterWidth - targetExtraWidth;

        // Resetting targetWidth.
        target.width(newWidth);

        /*<<================================================================================================>>*/

    });
}


$(document).ready(function() {

    //Aligning table head
    arangeGrid();


    var oddColor = '#FFF';
    var evenColor = '#F0E5CC';
    var hoverColor = '#ECB182';
    var selectedColor = '#DCC489';



    // On clicking 'allCheck' main checkbox.
    $('.datagrid .checkUncheckAll').change(function() {
        $(this).closest('.datagrid').find('.tbl_grid tbody input[type="checkbox"]').prop('checked', $(this).prop('checked'));
        if ($(this).prop('checked'))
        {
            $(this).closest('.datagrid').find('.tbl_grid tbody tr').each(function() {
                $(this).css('background-color', selectedColor);
            });
        }
        else
        {
            $(this).closest('.datagrid').find('.tbl_grid tbody tr:nth-child(odd)').css('background-color', oddColor);
            $(this).closest('.datagrid').find('.tbl_grid tbody tr:nth-child(even)').css('background-color', evenColor);
        }
    });

    // On Clicking individual checkboxes on each row
    $('.datagrid .gridSlNo').change(function() {
        if (!$(this).prop('checked'))
        {
            $(this).prop('checked', true);
            $(this).parent('td').parent('tr').css('background-color', selectedColor);
            if (typeof ($(this).closest('tbody').find('.gridSlNo:not(:checked)').prop('checked')) === "undefined")
                $(this).closest('.datagrid').find('.checkUncheckAll').prop('checked', true);
        }
        else
        {
            $(this).prop('checked', false);
            $(this).closest('.datagrid').find('.checkUncheckAll').prop('checked', false);
            var index = ($(this).closest('td').parent()[0].sectionRowIndex);
            var indexMod = index % 2;
            if (indexMod)
                $(this).parent('td').parent('tr').css('background-color', evenColor);
            else
                $(this).parent('td').parent('tr').css('background-color', oddColor);
        }
    });

    // On clicking on any row of the table
    $('.datagrid .tbl_grid tbody tr').click(function() {

        if ($(this).find('.gridSlNo').prop('checked'))
        {
            $(this).find('.gridSlNo').prop('checked', false);
            var index = ($(this).index());
            var indexMod = index % 2;
            if (indexMod)
                $(this).css('background-color', evenColor);
            else
                $(this).css('background-color', oddColor);
            $(this).closest('.datagrid').find('.checkUncheckAll').prop('checked', false);
        }
        else
        {
            $(this).find('.gridSlNo').prop('checked', true);
            $(this).css('background-color', selectedColor);
            if (typeof ($(this).closest('tbody').find('.gridSlNo:not(:checked)').prop('checked')) === "undefined")
                $(this).closest('.datagrid').find('.checkUncheckAll').prop('checked', true);
        }

    });

    /*
     //On Mouse hovering through rows of the table
     $('.datagrid .tbl_grid tbody  tr').hover(function() {
     if (!$(this).closest('.datagrid').find('.checkUncheckAll').prop('checked'))
     {
     $(this).closest('.tbl_grid').find('tbody tr').each(function() {
     if (!$(this).find('.gridSlNo').prop('checked'))
     {
     var index = ($(this).index());
     var indexMod = index % 2;
     if (indexMod)
     $(this).css('background-color', evenColor);
     else
     $(this).css('background-color', oddColor);
     }
     });
     if (!$(this).find('.gridSlNo').prop('checked'))
     $(this).css('background-color', hoverColor);
     
     }
     });
     */

    /*    //On mouse leave the table
     $('.datagrid .tbl_grid').mouseleave(function() {
     if (!$(this).closest('.datagrid').find('.checkUncheckAll').prop('checked'))
     {
     $(this).find('tbody tr').each(function() {
     if (!$(this).find('.gridSlNo').prop('checked'))
     {
     var index = ($(this).index());
     var indexMod = index % 2;
     if (indexMod)
     $(this).css('background-color', evenColor);
     else
     $(this).css('background-color', oddColor);
     }
     else
     $(this).css('background-color', selectedColor);
     });
     }
     });
     */


});