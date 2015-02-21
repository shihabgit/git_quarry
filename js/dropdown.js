/**
 * Used in http://localhost/the_quarry/individual_rates/add
 *          Usage example: creatOptGroup($('#indv_fk_party_destinations'), json);
 *          
 *          
 * @param {type} $select : object of HTML <SELECT> element where the option should be inserted.
 * @param {type} json   :   JSON out put:
 *                           Eg: {"Best":[{"id":"2","name":"Best H.b"},{"id":"3","name":"Best Interlocks"}],
 *                                "Parambadan":[{"id":"4","name":"H.b"},{"id":"7","name":"Interlocks"}]}
 *                           
 *                           PHP Format: array(array('optgroup_lable1'=>array(array(id1,name1),array(id2,name2))));
 *                           Eg:
 *                              $json['Best'][0] => Array ( 'id' => 2, 'name' => 'Best H.b'  );
 *                              $json['Best'][1] => Array ( 'id' => 3, 'name' => 'Best Interlocks' );
 *                              $json['Parambadan'][0] => Array ( 'id' => 4, 'name' => 'H.b' );
 *                              $json['Parambadan'][1] => Array ( 'id' => 7, 'name' => 'Interlocks' );
 *                           
 *                           if No Options;
 *                              $json['-- No Destinations Found --'][] = array('id' => '', 'name' => '--No Options--');
 *                              
 *                              ---------  Visit Controller party_destinations/getDestinationsByParties. ------------
 * @returns {undefined}
 */
function creatOptGroup($select, json)
{
    $select.html('');
    $.each(json, function(groupName, options) {
        var $optgroup = $("<optgroup>", {label: groupName});
        $optgroup.appendTo($select);
        $.each(options, function(index, opt_array) {
            var $option = $("<option>", {text: opt_array.name, value: opt_array.id});
            $option.appendTo($optgroup);
        });
    });
}




function setOptions(id, path)
{
    var path = site_url + path;
    //var element_id = $(id).val();
    $(id).html('No Options');

    var values = ''; // eg: {parent_id: parent_id, status: 1}

    $.getJSON(path, values, function(data) {
        var options = '';
        for (var x = 0; x < data.length; x++) {
            options += '<option value="' + data[x]['value'] + '">' + data[x]['text'] + '</option>';
        }
        $(id).html(options);
    });

}

/**
 * 
 * @param {type} id
 * @param {type} childs
 * @param {type} path
 * @param {type} pre_func   : Function to be called before ajax sending.
 * @param {type} post_func  : Function to be called after ajax response.
 * @returns {undefined}
 */
function resetOptions(id, childs, path, pre_func, post_func)
{
    var path = site_url + path;
    childs = childs.split(",");
    var parent_id = $(id).val();
    var index;

    for (index = 0; index < childs.length; ++index) {
        //$('#'+childs[index]+' option[value!="0"]').remove();
        $('#' + childs[index] + ' option').remove();
        $('#' + childs[index]).html('<option value="">No Options</option>');
    }
    if (!parent_id)
        return;
    if (pre_func)
        pre_func();
    $.getJSON(path, {parent_id: parent_id}, function(data) {
        var options = '';
        for (var x = 0; x < data.length; x++) {
            options += '<option value="' + data[x]['value'] + '">' + data[x]['text'] + '</option>';
        }
        $('#' + childs[0]).html(options);
        if (post_func)
            post_func();
    });

}

function resetOptions2(id, childs, path, selected)
{
    var path = site_url + path;
    childs = childs.split(",");
    var parent_id = $(id).val();
    var index;

    for (index = 0; index < childs.length; ++index) {
        //$('#'+childs[index]+' option[value!="0"]').remove();
        $('#' + childs[index] + ' option').remove();
        $('#' + childs[index]).html('<option value="">No Options</option>');
    }
    if (!parent_id)
        return;

    $.getJSON(path, {parent_id: parent_id}, function(data) {
        var options = '';
        for (var x = 0; x < data.length; x++) {
            if (data[x]['value'] == selected)
                options += '<option value="' + data[x]['value'] + '" selected>' + data[x]['text'] + '</option>';
            else
                options += '<option value="' + data[x]['value'] + '">' + data[x]['text'] + '</option>';
        }
        $('#' + childs[0]).html(options);
    });

}

function resetOptions3(parent, childs, path, selected)
{
    var path = site_url + path;
    childs = childs.split(",");
    var index;

    for (index = 0; index < childs.length; ++index) {
        //$('#'+childs[index]+' option[value!="0"]').remove();
        $('#' + childs[index] + ' option').remove();
        $('#' + childs[index]).html('<option value="">No Options</option>');
    }
    if (!parent)
        return;

    $.getJSON(path, {parent_id: parent}, function(data) {
        var options = '';
        for (var x = 0; x < data.length; x++) {
            if (data[x]['value'] == selected)
                options += '<option value="' + data[x]['value'] + '" selected>' + data[x]['text'] + '</option>';
            else
                options += '<option value="' + data[x]['value'] + '">' + data[x]['text'] + '</option>';
        }
        $('#' + childs[0]).html(options);
    });

}