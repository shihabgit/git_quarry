<?php $vhcl_id = $GLOBALS['vhcl_id']; ?>
<table style="width:100%;" class="adbdr" cellpadding="0" cellspacing="0">

   <tbody>
      <tr>
         <th class="xpl">
            Labours In The Vehicle
            <img id="vemp_add" src="images/add3.png" title="Add Labours To The Vehicle.">&nbsp;
            <?php
            if (isset($labours[$vhcl_id]) && $labours[$vhcl_id])
            {
               ?>
               <img class="vemp_edit" src="images/edit12.png" title="Edit Labours">&nbsp;
               <img class="vemp_delete" src="images/delete5.png" title="Delete Labours">

            <?php } ?>
         </th>
      </tr>


      <?php
      if (isset($labours[$vhcl_id]) && $labours[$vhcl_id])
      {
         ?>
         <tr>
            <td style="text-align: left;">
               <span class="drivers">Drivers:</span>    
               <span class="labours">
                  <?php
                  $vemps = array();
                  foreach ($labours[$vhcl_id] as $lbrs)
                     if ($lbrs['emp_category'] == 4)   // Driver.
                        $vemps[] = ($lbrs['vemp_is_default'] == 1) ? '<span class="default_labour" title="Default Driver">' . $lbrs['emp_name'] . '</span>' : $lbrs['emp_name'];
                  if (!$vemps)
                     echo "No Drivers Found.";
                  else
                     echo implode(', ', $vemps);
                  ?>
               </span>   
            </td>
         </tr>
         <tr> 
            <td style="text-align: left;">
               <span class="loaders">Loaders:</span>    
               <span class="labours">
                  <?php
                  $vemps = array();
                  foreach ($labours[$vhcl_id] as $lbrs)
                     if ($lbrs['emp_category'] == 5)   // Loaders.
                        $vemps[] = ($lbrs['vemp_is_default'] == 1) ? '<span class="default_labour" title="Default Loader">' . $lbrs['emp_name'] . '</span>' : $lbrs['emp_name'];

                  if (!$vemps)
                     echo "No Loaders Found.";
                  else
                     echo implode(', ', $vemps);
                  ?>
               </span>    
            </td>
         </tr>
         <?php
      }
      else
         echo '<tr class="nodata"><th>No Labours Found</th></tr>';
      ?>
   </tbody>
</table>
