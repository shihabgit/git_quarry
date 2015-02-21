<?php $vhcl_id = $GLOBALS['vhcl_id']; ?>
<?php $vwc_fk_workcentres = $GLOBALS['vwc_fk_workcentres']; ?>
<?php $vwc_workcentres_name = $GLOBALS['vwc_workcentres_name']; ?>

<table style="width:100%;" class="adbdr" cellpadding="0" cellspacing="0">

   <tbody>
      <tr>
         <th class="xpl" colspan="4">
            Freight Charges For The Vehicle From <?php echo $vwc_workcentres_name; ?>
            <img class="add_fc" src="images/add3.png" title="Add Freight.">
            <input type="hidden" class="fc_wcntr_id" value="<?php echo $vwc_fk_workcentres; ?>">
            <input type="hidden" class="fc_wcntr" value="<?php echo $vwc_workcentres_name; ?>">
         </th> 
      </tr>
      <tr>
         <th><p class="tile_content_h_title">Party - Destination</p></th>
<th><p class="tile_content_h_title">Rent</p></th>
<th><p class="tile_content_h_title">Bata</p></th>
<th><p class="tile_content_h_title">Loading</p></th>
</tr>


<?php
if (isset($freight[$vhcl_id]) && $freight[$vhcl_id])
{
   foreach ($freight[$vhcl_id] as $row)
   {
      if ($row['fc_fk_workcentres'] == $vwc_fk_workcentres)
      {
         echo '<tr>';

         echo '<td>';
         $party = $row['pty_name'] . ' - ' . $row['pdst_name'];

         echo '   <div style="float:left" >';
         echo '      <img class="fc_edit" src="images/edit12.png" title="Edit Freight Charges">&nbsp;';
         echo '      <img class="fc_delete" src="images/delete5.png" title="Delete Freight Charges">&nbsp;';
         echo '      <input type="hidden" class="fc_id" value="' . $row['fc_id'] . '">';
         echo '      <input type="hidden" class="fc_party" value="' . $party . '">';
         echo '   </div>';

         echo '   <div class="location" style="float:left;">' . $party . '</div>';
         echo '</td>';

         $add_rent = $row['fc_add_rent'] == 1 ? ' <img src="images/tick.png" title="Rent Will Be Added To The  Bill Amount"> ' : '';
         echo '<td>' . $row['fc_rent'] . $add_rent . '</td>';

         $add_bata = $row['fc_add_bata'] == 1 ? ' <img src="images/tick.png" title="Bata Will Be Added To The  Bill Amount"> ' : '';
         echo '<td>' . $row['fc_bata'] . $add_bata . '</td>';

         $add_loading = $row['fc_add_loading'] == 1 ? ' <img src="images/tick.png" title="Loading Will Be Added To The  Bill Amount"> ' : '';
         echo '<td>' . $row['fc_loading'] . $add_loading . '</td>';

         echo '</tr>';
      }
   }
}
else
   echo '<tr class="nodata"><th colspan="4">No Freight Charges Found</th></tr>';
?>
</tbody>
</table>