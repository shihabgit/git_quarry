<link href="css/<?php echo $this->themes[$this->theme]['text']; ?>/p_bill_add.css" rel="stylesheet" type="text/css" />
<div class="bill_popup">
   <div id="dv_bill">
      <div id="p_bill_type">
         <div id="bill_right" title="Next Bill">&nbsp;</div>
         <div id="bill_left" title="Previous Bill">&nbsp;</div>
         <div id="blname">PURCHASE BILL &ensp;<img src="images/delete2.png" /></div>
      </div>
      <div id="bill_head">
         <div id="top_head">
            <div class="top_head_col"> <span class="spn_bill_no">Bill No</span>
               <input type="text" name="bill_no" value="1" style="width:50px;"/>
            </div>
            <div class="top_head_col"> <span class="spn_ref_no">Reference No</span>
               <input type="text" name="bill_no" value="1" style="width:50px;"/>
            </div>
            <div class="top_head_col"> <span class="spn_date">12/05/2015</span> </div>
            <div class="top_head_col"> <span class="spn_tax">Taxable</span> </div>
            <div class="top_head_col"> <span class="spn_tax_no">Compounted</span> </div>
            <div class="clear_boath"></div>
         </div>
         <div id="botom_head">
            <table class="tbl_botom_head" cellspacing="6" style="">
               <tbody>
                  <tr>
                     <td style="width: 295px;"><table class="basic_details" style="width:100%" cellpadding="2" cellspacing="0">
                           <tr>
                              <td>Workcentre</td>
                              <td><select name="">
                                    <option value="">Select</option>
                                    <option value="1">Quarry</option>
                                    <option value="2">Crusher</option>
                                 </select></td>
                           </tr>

                           <tr>
                              <td>Party Type</td>
                              <td>
                                 <span class="label_val">
                                    <input type="radio" name="party_type" value="1" checked="checked" /> Existing &nbsp;
                                    <input type="radio" name="party_type" value="2" /> Temperory
                                 </span>
                              </td>
                           </tr>

                           <tr>
                              <td>Party</td>
                              <td>
                                 <select name="party">
                                    <option value="">Select</option>
                                    <option value="1">Best</option>
                                    <option value="2">Parmbadan</option>
                                 </select>
                                 <input type="text" name="tmp_party" value="" style="display:none;" />
                              </td>
                           </tr>  
                           <tr>
                              <td>Destination</td>
                              <td><select name="destination">
                                    <option value="">Select</option>
                                    <option value="1">HB</option>
                                    <option value="2">Interlocks</option>
                                 </select></td>
                           </tr>
                           <tr>
                              <td>Old Balance</td>
                              <td style="color:#FFF;">&nbsp;<span id="party_ob" class="label_val">25000.00 Cr.</span></td>
                           </tr>
                           <tr>
                              <td>Current Balance</td>
                              <td style="color:#FFF;">&nbsp;<span id="party_cb" class="label_val">60000.00 Cr.</span></td>
                           </tr>
                        </table></td>
                     <td style="width: 500px;">
                        <div class="dv_vown"> <span class="vown_1">Vehicle Ownership:</span> <span class="label_val">
                              <input type="radio" name="vhcl_owner" value="1" checked="checked" />
                              Ours &nbsp;
                              <input type="radio" name="vhcl_owner" value="2" />
                              Others &nbsp;
                              <input type="radio" name="vhcl_owner" value="3" />
                              Parties &nbsp;
                              <input type="radio" name="vhcl_owner" value="4" />
                              Temperory </span> </div>
                        <div class="dv_vown_block">
                           <table class="vehicle_details" style="width:100%" cellpadding="2" cellspacing="0">
                              <tr>
                                 <td>Vehicle</td>
                                 <td><select name="vhcl">
                                       <option value="">Select</option>
                                       <option value="1">KL 10 AB 1212</option>
                                       <option value="2">KL 12 AF 4545</option>
                                    </select>
                                    <input type="text" name="tmp_vhcl" value="" style="display:none;" /></td>
                                 <td>Rent</td>
                                 <td><input type="text" name="rent" value="" style="width:65px" />
                                    <input type="checkbox" name="add_rent" />
                                    Add to bill</td>
                              </tr>
                              <tr>
                                 <td>Driver</td>
                                 <td><select name="driver">
                                       <option value="">Select</option>
                                       <option value="1">Zubair KM</option>
                                       <option value="2">Ashraf T</option>
                                    </select></td>
                                 <td>Bata</td>
                                 <td><input type="text" name="bata" value="" /></td>
                              </tr>
                              <tr>
                                 <td rowspan="2">Loaders</td>
                                 <td rowspan="2"><select name="loaders[]" class="loaders" multiple="multiple" style="height:60px;">
                                       <option value="">Select</option>
                                       <option value="1">Sundaran</option>
                                       <option value="2">Mani</option>
                                    </select></td>
                                 <td>Loading Charge</td>
                                 <td><input type="text" name="ld_charge" value="" /></td>
                              </tr>
                              <tr>
                                 <td>Loading Mode</td>
                                 <td><select name="ld_mode">
                                       <option value="">Select</option>
                                       <option value="1">Individually</option>
                                       <option value="2">Shared</option>
                                    </select></td>
                              </tr>
                           </table>
                        </div></td>
                     <td style="width: 295px;"><table class="bill_settings" style="width:100%" cellpadding="2" cellspacing="0">
                           <tr>
                              <td>Item Category</td>
                              <td><select name="">
                                    <option value="1">Metal</option>
                                    <option value="2">Explossives</option>
                                 </select></td>
                           </tr>
                           <tr>
                              <td>Item Head</td>
                              <td><select name="">
                                    <option value="1">Rowmaterials</option>
                                    <option value="2">Finished goods</option>
                                 </select></td>
                           </tr>
                           <tr>
                              <td colspan="2">
                                 <input type="checkbox" name="" /> 
                                 <span class="label_val">Show all drivers</span>
                              </td>

                           </tr>
                        </table></td>
                  </tr>
               </tbody>
            </table>
         </div>
      </div>
      <div id="bill_body">
         <div id="bdybox">
            <table class="tbl_bill_body tbl_traversor" cellpadding="0" cellspacing="0">
               <thead>
                  <tr>
                     <th style="width:80px;">No</th>
                     <th style="width:206px;">Item</th>
                     <th style="width:100px;">Quantity</th>
                     <th style="width:90px;">Unit</th>
                     <th style="width:100px;">Rate</th>
                     <th style="width:130px;">Amount</th>
                     <th style="width:100px;">VAT</th>
                     <th style="width:100px;">CESS</th>
                     <th style="width:150px;">Gross Amount</th>
                  </tr>
               </thead>
               <tbody>
                  <tr class="tr_traversor">
                     <td><div class="row_remvr"><img src="images/delete5.png" /></div>
                        <div class="slno">12</div></td>
                     <td><select name="" style="width:206px;" class="nextInput">
                           <option value="1">6" Boller</option>
                           <option value="2">Boller</option>
                        </select></td>
                     <td><input type="text" name="" style="width:100px;" class="nextInput"/></td>
                     <td><select name="" style="width:100px;" class="nextInput">
                           <option value="1">CFT</option>
                        </select></td>
                     <td><input type="text" name="" style="width:100px;" class="nextInput"/></td>
                     <td><input type="text" name="" style="width:130px;" readonly="readonly" /></td>
                     <td><input type="text" name="" style="width:100px;" readonly="readonly" /></td>
                     <td><input type="text" name="" style="width:100px;" readonly="readonly" /></td>
                     <td><input type="text" name="" style="width:150px;" readonly="readonly" /></td>
                  </tr>
               </tbody>
               <tfoot>
                  <tr>
                     <td colspan="7" style="vertical-align:bottom;">
                        <input type="text" class="left" name="remarks" placeholder="Remarks....." style="width:848px;" /> 
                     </td>
                     <td colspan="2" style="vertical-align:top;">
                        <div class="dv_additionals">
                           <div class="adtnl_title">
                              <div class="adtl">BILL ADDITIVE</div>
                              <div class="add_img"><img src="images/add3.png" title="Add Additive" id="add_adtv" /></div>
                              <div class="clear_boath"></div>
                           </div>

                           <div class="dv_additional_body" id="adtv">
                              <!-- The following element will be loaded here by jquery. <div class="dv_additionals_row"></div>-->


                              <div class="total_additionals">
                                 <div class="total_additionals_col"><span class="nTotal_text">Total Additive</span></div>
                                 <div class="total_additionals_col"><span class="nTotal_val" >2000.00</span></div>
                                 <div class="clear_boath"></div>
                              </div>
                           </div>
                           <div class="adtnl_title">
                              <div class="adtl">BILL DEDUCTIVE</div>
                              <div class="add_img"><img src="images/add3.png" title="Add Deductive" id="add_ddtv" /></div>
                              <div class="clear_boath"></div>
                           </div>
                           <div class="dv_additional_body" id="ddtv">
                              <!-- The following element will be loaded here by jquery. <div class="dv_additionals_row"></div>-->
                              <div class="total_additionals">
                                 <div class="total_additionals_col"><span class="nTotal_text">Total Deductive</span></div>
                                 <div class="total_additionals_col"><span class="nTotal_val" >2000.00</span></div>
                                 <div class="clear_boath"></div>
                              </div>

                           </div>
                        </div>


                        <div class="dv_freights">
                           <!--The following div will be inserted here by jquery <div class="dv_freights_row"></div>-->
                        </div>


                        <div class="dv_pay">
                           <table cellpadding="0" cellspacing="0" class="tbl_pay">
                              <tbody>
                                 <tr>
                                    <td style="width:120px"><span class="spn_pay">Round Off</span></td>
                                    <td><input type="text" name="" style="width: 87px;" class="right" /></td>
                                 </tr>
                                 <tr>
                                    <td style="width:120px"><span class="spn_pay">Paid</span></td>
                                    <td><input type="text" name="" style="width: 87px;" class="right" /></td>
                                 </tr>
                              </tbody>
                           </table>
                        </div>


                     </td>
                  </tr>
               </tfoot>
            </table>
         </div>
      </div>
      <div id="bill_foot">
         <ul>
            <li> <span class="ntbaltxt">Balance</span> <span class="ntbalval">258000</span> <span class="is_print">
                  <input type="checkbox" name="print_on_save" checked="checked" />
                  Print on save</span> </li>
            <li>
               <input class="save_btn" type="button" name="save" value="SAVE" />
            </li>
         </ul>
      </div>
   </div>
</div>

<script type="text/javascript" src="js/tbl_traversor2.js"></script>
<script type="text/javascript" src="js/purchase_bill_add.js"></script>
