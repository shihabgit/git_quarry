/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function getToday()
{
    var dt = new Date();
    var y = dt.getFullYear();
    var m = dt.getMonth();
    var d = dt.getDate();
    m++;
    if (m < 10)
        m = ("0" + m);
    if (d < 10)
        d = ("0" + d);
    return d + '-' + m + '-' + y;
}

function getCurrentTime()
{
    var d = new Date(); // for now
    var hh = d.getHours(); // => 9
    var mm = d.getMinutes(); // =>  30
    var ss = d.getSeconds();
    return hh + ':' + mm + ':' + ss;
}