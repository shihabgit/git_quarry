<?php
$itm_id = $GLOBALS['itm_id'];
$itm_name = $GLOBALS['itm_name'];



foreach ($dests[$itm_id] as $wc_id => $pdst_details)
{
   if ($pdst_details)
   {
      $wcntr_name = isset($workcentres[$wc_id]) ? $workcentres[$wc_id] : 'Logical Error';
      $total_unit_count = count($units[$itm_id]);

      $total_cols = ($total_unit_count * 2) + 1; // 1 => column for party-destinations. 2 => Each unit contains 2 colums. one for purchase and other for sale.
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
                  <th rowspan="2"><p class="tile_content_h_title">Party - Destination</p></th>
            <?php

            foreach ($units[$itm_id] as $unt)
            {
               echo '<th colspan="2">';
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
            <tr>
               <?php
               for ($i = 1; $i <= $total_unit_count; $i++)
               {
                  ?>
                  <th><p class="tile_content_h_title">Purchase</p></th>
               <th><p class="tile_content_h_title">Selling</p></th>
               <?php
            }
            ?>

            </tr>
            <?php

            foreach ($pdst_details as $pdst_row)
            {
               ?> 
            <tr class="indv_tr">
                  <td>   
                     <div style="float:left">      
                        <img title="Edit Rates" src="images/edit12.png" class="indv_edit">&nbsp;      
                        <img title="Delete Rates" src="images/delete5.png" class="indv_delete">&nbsp;  
                     </div>   
                     <?php $party = $pdst_row['pty_name'] . '-' . $pdst_row['pdst_name'];?>
                     <div style="float:left;" class="location">
                        <?php echo $party; ?>
                        <input type="hidden" class="party_name" value="<?php echo $party; ?>">
                     </div>
                  </td>


                  <?php
                  foreach ($units[$itm_id] as $unt)
                  {
                     $p_rate = '';
                     $s_rate = '';
                     $indv_id = '';
                     foreach ($indvs[$itm_id][$wc_id][$pdst_row['DEST_ID']] as $indv_row)
                     {
                        if ($unt['unt_id'] == $indv_row['indv_fk_units'])
                        {
                           $p_rate = $indv_row['indv_p_rate'];
                           $s_rate = $indv_row['indv_s_rate'];
                           $indv_id = $indv_row['indv_id'];
                        }
                     }

                     echo '<td>';
                     echo $p_rate ;
                     if($indv_id)
                        echo '<input type="hidden" value="'.$indv_id.'" class="indv_id">';
                     echo '</td>';
                     echo '<td>' . $s_rate . '</td>';
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


