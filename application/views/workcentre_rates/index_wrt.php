<?php
$itm_id = $GLOBALS['itm_id'];
$itm_name = $GLOBALS['itm_name'];



foreach ($wcntrs_to[$itm_id] as $wc_id => $wcTo_details)
{
   if ($wcTo_details)
   {
      $wcntr_name = isset($workcentres[$wc_id]) ? $workcentres[$wc_id] : 'Logical Error';
      $total_unit_count = count($units[$itm_id]);

      $total_cols = $total_unit_count + 1; // 1 => column for wrt_fk_workcentres_to. 2 => Each unit contains 2 colums. one for purchase and other for sale.
      ?>
      <div style="margin-top: 5px;" class="bring">

         <table cellspacing="0" cellpadding="0" class="adbdr" style="width:100%;">

            <tbody>
               <tr>
                  <th colspan="<?php echo $total_cols; ?>" class="xpl">
                     <span >Rate of <span class="wcntr vwc_active"><?php echo $itm_name; ?></span> in workcentre: 
                        <span class="wcntr vwc_active"><?php echo $wcntr_name; ?></span>
                  </th> 
               </tr>
               <tr>
                  <th rowspan="2"><p class="tile_content_h_title">Workcentres</p></th>
            <th colspan="<?php echo $total_unit_count; ?>">Selling Rate</th>
            </tr>
            <tr>


               <?php
               foreach ($units[$itm_id] as $unt)
               {
                  echo '<th>';
                  echo '<p class="tile_content_h_title">';
                  echo $unt['unt_name'];
                  if ($unt['unt_is_parent'] == 1)
                     echo ' <img title="Basic unit of the item." src="images/parent.png">';
                  else if (isset($unit_details[$itm_id][$unt['unt_parent']]['unt_name']))
                     echo ' <span class="rel">(' . $unt['unt_relation'] . ' ' . $unit_details[$itm_id][$unt['unt_parent']]['unt_name'] . ')</span>';
                  echo '</p>';
                  echo '</th>';
               }
               ?>
            </tr>

            <?php
            foreach ($wcTo_details as $wcTo_row)
            {
               ?> 
               <tr class="wrt_tr">
                  <td>   
                     <div style="float:left">      
                        <img title="Edit Rates" src="images/edit12.png" class="wrt_edit">&nbsp;      
                        <img title="Delete Rates" src="images/delete5.png" class="wrt_delete">&nbsp;  
                     </div>   

                     <div style="float:left;" class="location">
         <?php echo $wcTo_row['wcntr_name']; ?>
                        <input type="hidden" class="wcTo_name" value="<?php echo $wcTo_row['wcntr_name']; ?>">
                     </div>
                  </td>


         <?php
         foreach ($units[$itm_id] as $unt)
         {
            $s_rate = '';
            $wrt_id = '';

            foreach ($wrts[$itm_id][$wc_id][$wcTo_row['wcntr_id']] as $wrt_row)
            {
               if ($unt['unt_id'] == $wrt_row['wrt_fk_units'])
               {
                  $s_rate = $wrt_row['wrt_s_rate'];
                  $wrt_id = $wrt_row['wrt_id'];
               }
            }

            echo '<td>';
            echo $s_rate;
            if ($wrt_id)
               echo '<input type="hidden" value="' . $wrt_id . '" class="wrt_id">';
            echo '</td>';
         }
         ?>
               </tr>
                  <?php
               }
               ?>





            </tbody>
         </table>   
      </div>
      <?php
   }
}
?>            


