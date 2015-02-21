<?php $vhcl_id = $GLOBALS['vhcl_id']; ?>
<table style="width:100%;" class="adbdr" cellpadding="0" cellspacing="0">

   <tbody>
      <tr>
         <th class="xpl" colspan="4">
            Freight Charges For The Vehicle Between Workcentres
            <img class="add_ifc" src="images/add3.png" title="Add Freight.">
         </th>
      </tr>
      <tr>
         <th><p class="tile_content_h_title">Workcentres</p></th>
<th><p class="tile_content_h_title">Rent</p></th>
<th><p class="tile_content_h_title">Bata</p></th>
<th><p class="tile_content_h_title">Loading</p></th>
</tr>


<?php
if (isset($inter_freight[$vhcl_id]) && $inter_freight[$vhcl_id])
{
   foreach($inter_freight[$vhcl_id] as $ifc_row)
   {
      echo '<tr>';
      
      
      echo '<td>';
      echo '   <div style="float:left">';
      echo '      <img class="ifc_edit" src="images/edit12.png" title="Edit Freight Charges">&nbsp;';
      echo '      <img class="ifc_delete" src="images/delete5.png" title="Delete Freight Charges">&nbsp;';
      echo '      <input type="hidden" class="ifc_id" value="'.$ifc_row['ifc_id'].'">';
      echo '      <input type="hidden" class="ifc_from" value="'.$ifc_row['wcntr_from'].'">';
      echo '      <input type="hidden" class="ifc_to" value="'.$ifc_row['wcntr_to'].'">';
      echo '   </div>'; 
      
      echo '   <div class="location" style="float:left;">';
      echo '      <div style="float:left">'.$ifc_row['wcntr_from']. '</div>';
      echo '      <div style="margin: 7px 2px 0 2px;float:left"> <img src="images/arrow-both2.png"></div>';
      echo '      <div style="float:left">'.$ifc_row['wcntr_to'].'</div>';
      echo '   </div>';
      echo '</td>';
      
      
      $add_rent = $ifc_row['ifc_add_rent'] == 1 ? ' <img src="images/tick.png" title="Rent Will Be Added To The  Bill Amount"> ':'';
      echo '<td>'.$ifc_row['ifc_rent'].$add_rent.'</td>';
      
      
      $add_bata = $ifc_row['ifc_add_bata'] == 1 ? ' <img src="images/tick.png" title="Bata Will Be Added To The  Bill Amount"> ':'';
      echo '<td>'.$ifc_row['ifc_bata'].$add_bata.'</td>';
      
      
      $add_loading = $ifc_row['ifc_add_loading'] == 1 ? ' <img src="images/tick.png" title="Loading Will Be Added To The  Bill Amount"> ':'';
      echo '<td>'.$ifc_row['ifc_loading'].$add_loading.'</td>';
      
      
      echo '</tr>';
   }
   
}
else
   echo '<tr class="nodata"><th colspan="4">No Freight Charges Found</th></tr>';
?>
</tbody>
</table>