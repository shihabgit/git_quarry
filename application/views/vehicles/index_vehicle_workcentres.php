<?php $vhcl_id = $GLOBALS['vhcl_id']; ?>

<div class="dv_availability">
    <div style="float: right; margin: 10px;">
        <img class="add_vwc" src="images/add3.png" title="Add Freight   asdf.">
    </div>
    <div class="avl_name">Vehicle Availability</div>



    <?php
    if (isset($availability[$vhcl_id]) && $availability[$vhcl_id])
    {

       foreach ($availability[$vhcl_id] as $row)
       {
          if ($row['vwc_status'] == 1)
          {
             $class = 'vwc_active';
             $status = 'Active';
             $img = 'images/delete6.png';
             $tooltip = "Deactivate Vehicle From Workcentre";
             $update = '<img title="Edit Vehicle\'s Details" class="vwc_edit" src="images/edit13.png">';
          }
          else
          {
             $class = 'inactive';
             $status = 'Inactive';
             $img = 'images/activate2.png';
             $tooltip = "Activate Vehicle In Workcentre";
             $update = '';
          }
          ?>
          <table style="width:100%;" class="tbl_availability" cellpadding="0" cellspacing="0">

              <tbody>
                  <tr>
                      <th style="text-align:center">

              <div style="float:left">
                  <span class="wcntr_txt">Workcentre: </span>
                  <span class="wcntr <?php echo $class; ?>" title="<?php echo $status; ?>"><?php echo $row['wcntr_name']; ?></span>
              </div>

              <div class="vwc_dv" style="float:left;margin: 1px 0 0 10px;">
                  <?php echo $update; ?>
                  <img class="vwc_delete" title="<?php echo $tooltip; ?>" src="<?php echo $img; ?>">
                  <input class="vwc_id" type="hidden" value="<?php echo $row['vwc_id']; ?>">
                  <input class="vwc_wc_name" type="hidden" value="<?php echo $row['wcntr_name']; ?>">
                  <input class="vwc_status" type="hidden" value="<?php echo $row['vwc_status']; ?>">
              </div>

              <div style="float:right">
                  <span class="dob"><?php echo formatDate($row['vwc_date'], FALSE); ?></span>
              </div>

              </th>
              </tr>

              <tr>
                  <td>


                      &nbsp;

                      <?php
                      if ($GLOBALS['vhcl_ownership'] == 1)
                      {
                         ?>
                         <span class="measurement">Buying Price: </span>       
                         <span class="quantity"><?php echo $row['vwc_cost']; ?></span>  

                         <?php
                      }
                      ?>

                      <?php
                      if (intval($row['vwc_sold_price']) && ($GLOBALS['vhcl_ownership'] == 1))
                      {
                         ?>
                         <span class="measurement">Sold Price: </span>       
                         <span class="quantity"><?php echo $row['vwc_sold_price']; ?></span>  
                      <?php } ?>




                      <?php
                      $ob_txt = '';
                      if (intval($row['vwc_ob']))
                         $ob_txt = ($row['vwc_ob_mode'] == 1) ? ' Cr' : ($row['vwc_ob_mode'] == 2 ? ' Dr' : '');
                      ?>
                      <span class="measurement">O.B: </span>       
                      <span class="quantity"><?php echo $row['vwc_ob'] . $ob_txt; ?></span>  


                      <?php
                      if ($GLOBALS['vhcl_ownership'] == 1)
                      {
                         ?>
                         <span class="measurement">Hrly: </span>       
                         <span class="quantity"><?php echo $row['vwc_hourly_rate']; ?></span>  
                         <?php
                      }
                      ?>

                      <?php
                      if ($GLOBALS['vhcl_ownership'] == 1)
                      {
                         ?>
                         <span class="measurement">Daily: </span>       
                         <span class="quantity"><?php echo $row['vwc_daily_rate']; ?></span>  
                         <?php
                      }
                      ?>

                      <?php
                      if ($GLOBALS['vhcl_ownership'] == 1)
                      {
                         ?>
                         <span class="measurement">Monthly: </span>       
                         <span class="quantity"><?php echo $row['vwc_monthly_rate']; ?></span> 
                         <?php
                      }
                      ?>
                  </td>
              </tr>
              <?php
              if ($GLOBALS['vhcl_ownership'] == 1) // If our's vehicle.
              {
                 ?>

                 <tr>
                     <td>

                         <div class="bring" style="margin-top: 5px;">
                             <?php $GLOBALS['vwc_fk_workcentres'] = $row['vwc_fk_workcentres']; ?>
                             <?php $GLOBALS['vwc_workcentres_name'] = $row['wcntr_name']; ?>                      
                             <?php $this->load->view('vehicles/index_freight_charges'); ?>   
                         </div>

                     </td>
                 </tr>
                 <?php
              }
              ?>

              </tbody>
          </table>
          <?php
       }
    }
    else
       echo '<table><tbody><tr class="nodata"><th><font color="red">Logical Error:</font><br>Vehilce is not available in any workcentre.</th></tr></tbody></table>';
    ?>

    <div class="avl_name2"></div> <!-- This div is just for design needs -->

</div>
