<?php 	
require("../inc/conex.inc");
session_start();



//Check if invoice is mine
$ssqlp = "select payments.*, DATE_FORMAT(cycle_start,'%m/%d/%Y') as start, DATE_FORMAT(cycle_end,'%m/%d/%Y') as end, users.email, DATE_FORMAT(payments.date,'%Y-%m-%d') as fecha from payments, users where payments.user_id = users.id and payments.user_id = '".$_SESSION['usr']."' and payments.id = '".$invoice."'";
$result = mysql_query($ssqlp);
if($row = mysql_fetch_array($result)){

	$paid_amount = 0;
	if($row["paid_amount"] > 0){
		$paid_amount = $row["paid_amount"];
	}
	
?>

<style type="text/Css">


.clearfix:after {
  content: "";
  display: table;
  clear: both;
}

a {
  color: #0087C3;
  text-decoration: none;
}


#logo {
  float: left;
  margin-top: 8px;
  position: absolute;
  top:0;
  left:0;
}

#logo img {
  height: 40px;
  display: block;
  float: left;
}

#company {
  float: right;
  text-align: right;
  border-bottom:1px solid #666;
  padding-bottom:20px;
}

#company p{
  margin:0;
  padding:0;
}



h2.name {
  font-size: 20px;
  font-weight: normal;
  margin: 0;
  color: #222222;
}

table {
  width: 100%;
  border-collapse: collapse;
  border-spacing: 0;
  margin-bottom: 20px;
}

table th,
table td {
  padding: 20px;
  background: #F9F9F9;
  text-align: center;
  border-bottom: 1px solid #DEDEDE;
}




table th {
  white-space: nowrap;        
  font-weight: normal;
}

table#details {
  width:100%;
  margin-bottom: 60px;
  margin-top:20px;
}

table#details td{
  background: #FFFFFF;
  width:50%;
  padding: 0;
  border-bottom: 1px solid #FFFFFF;
}

table#details p{
  margin:0 0 2px 0;
}

table td {
  text-align: right;
}

table#details td#client,
table#details td#client p{
  text-align: left;
}

table#details td#client{
  border-left: 6px solid #0087C3;
  padding-left: 6px;
}

table#details td#client p, table#details td#invoice p{
  color:#777;
}

table#details td#invoice h1{
  color: #0087C3;
}

table td h3{
  color: #57B223;
  font-size: 1.2em;
  font-weight: normal;
  margin: 0 0 0.2em 0;
}

table .no {
  color: #FFFFFF;
  font-size: 1.6em;
  background: #57B223;
}

table .desc {
  text-align: left;
  width:280px;
}

table .unit {
  background: #F5F5F5;
}

table .qty {
}

table .total {
  background: #F0F0F0;
}

table td.unit,
table td.qty,
table td.total {
  font-size: 1.2em;
}

table tbody tr:last-child td {
  border: none;
}

table tfoot td {
  padding: 10px 20px;
  background: #FFFFFF;
  border-bottom: 1px solid #000000;
  font-size: 1.2em;
  white-space: nowrap; 
  border-top: 1px solid #AAAAAA; 
}

table tfoot td.no-border{
  border-bottom:none;
}


table tfoot td.green-border{
  border-bottom:1px solid #57B223;
}

table tfoot tr:first-child td {
  border-top: none; 
}

table tfoot tr:last-child td {
  color: #57B223;
  font-size: 16px;
  border-top: 1px solid #57B223; 

}

table tfoot tr td:first-child {
  border: none;
}

#thanks{
  font-size: 24px;
  margin-bottom: 50px;
}

#notices{
  padding-left: 6px;
  border-left: 6px solid #0087C3;  
}

#notices .notice {
  font-size: 1.2em;
}

footer {
  color: #777777;
  width: 100%;
  height: 30px;
  position: absolute;
  bottom: 0;
  border-top: 1px solid #AAAAAA;
  padding: 8px 0;
  text-align: center;
}

</style>

<page style="font-size: 14px" backtop="7mm" backbottom="7mm" backleft="10mm" backright="10mm">

      <div id="logo">
        <img src="./template/logo.jpg">
      </div>
      <div id="company">
        <h2 class="name">Spinattic LLC</h2>
        <p>PO Box 2285 | Glens Falls, NY 12801</p>
        <p>Te. 800 204 6950</p>
        <p>hello@spinattic.com</p>
      </div>
      <table id="details">
        <tr>
          <td id="client">
            <p class="to">INVOICE TO:</p>
            <h2 class="name"><?php echo $row["name"];?></h2>
            <p class="address"><?php echo $row["address"].", ".$row["state"]." ".$row["zip"].", ".$row["country"];?></p>
            <p class="email"><?php echo $row["email"];?></p>
            <p class="account">Account #: <?php echo $row["user_id"];?></p>
          </td>
          <td id="invoice">
            <h1>INVOICE</h1>
            <p class="date">Invoice Number: <?php echo $row["spinattic_invoice"];?></p>
            <p class="date">Invoice date: <?php echo $row["fecha"];?></p>
            <p class="date">Due Date: <?php echo $row["fecha"];?></p>
          </td>
        </tr>
     </table>
<p>&nbsp;</p>
      <table border="0" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
            <th class="desc">SERVICE DESCRIPTION</th>
            <th class="unit">RATE</th>
            <th class="qty">QUANTITY</th>
            <th class="total">SUBTOTAL</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="desc"><h3><?php echo ucfirst(strtolower($row["upgraded_to_level"]));?> account yearly service</h3>Start: <?php echo $row["start"];?> - End: <?php echo $row["end"];?></td>
            <td class="unit">$ <?php echo number_format($row["original_amount"] / $row["q"], 2);?></td>
            <td class="qty"><?php echo $row["q"];?></td>
            <td class="total">$ <?php echo number_format($row["original_amount"], 2);?></td>
          </tr>
          
          <?php 
          $c_value = 0;
          if($row["coupon_id"] != ''){

          	$ssqlp_c = "select * from payments_coupons where LCASE(id) = '".strtolower($row["coupon_id"])."'";
          	$result_c = mysql_query($ssqlp_c);
          	$row_c = mysql_fetch_array($result_c);
          		if($row_c["type"] == 'porcent'){
          			$c_simbol = '% ';
          			$c_value = number_format($row["original_amount"] * $row_c["value"]/100, 2);
	          	}else{
	          		$c_simbol = '$ ';
	          		$c_value = $row_c["value"];
	          	}
          	
          	?>
          <tr>
            <td class="desc"><h3>Discount</h3>Coupon discount applied</td>
            <td class="unit"><?php echo $c_simbol.$row_c["value"];?></td>
            <td class="qty">1</td>
            <td class="total">$ -<?php echo $c_value;?></td>
          </tr>
          <?php }?>
          
          <?php if($row["credit_unused"] > 0){?>
          <tr>
            <td class="desc"><h3>Credit</h3>Unused cicle of Advanced account</td>
            <td class="unit">$ -<?php if($row["credit_unused"] >= $row["original_amount"] - $c_value) {echo number_format($row["original_amount"] - $c_value, 2);}else{echo number_format($row["credit_unused"], 2);}?></td>
            <td class="qty">1</td>
            <td class="total">$ -<?php if($row["credit_unused"] >= $row["original_amount"] - $c_value) {echo number_format($row["original_amount"] - $c_value, 2);}else{echo number_format($row["credit_unused"], 2);}?></td>
          </tr>
          <?php }?>
          
          <?php if($row["credit_balance"] > 0){?>
          <tr>
            <td class="desc"><h3>Credit</h3>Credit balance</td>
            <td class="unit">$ -<?php if($row["credit_balance"] >= $row["original_amount"] - $row["credit_unused"] - $c_value) {echo number_format($row["original_amount"] - $row["credit_unused"] - $c_value, 2);}else{echo number_format($row["credit_balance"], 2);}?></td>
            <td class="qty">1</td>
            <td class="total">$ -<?php if($row["credit_balance"] >= $row["original_amount"] - $row["credit_unused"] - $c_value) {echo number_format($row["original_amount"] - $row["credit_unused"] - $c_value, 2);}else{echo number_format($row["credit_balance"], 2);}?></td>
          </tr>
          <?php }?>          
        </tbody>

        <tfoot>
          <tr>
            <td class="no-border"></td>
            <td colspan="2">TOTAL</td>
            <td>$ <?php if($paid_amount > 0) {echo number_format($paid_amount, 2);}else{echo '0.00';}?></td>
          </tr>
          <!-- tr>
            <td class="no-border"></td>
            <td colspan="2">TAX 1%</td>
            <td>$3.60</td>
          </tr>
          <tr>
            <td class="no-border"></td>
            <td colspan="2">GRAND TOTAL</td>
            <td>$363.60</td>
          </tr-->
          <tr>
            <td class="no-border"></td>
            <td colspan="2"  class="no-border"></td>
            <td  class="no-border"></td>
          </tr>
          <?php if($paid_amount >0){?>
          <tr>
            <td class="no-border">Payment #: <?php echo $row["2co_sale_id"];?></td>
            <td colspan="2" class="green-border">AMOUNT PAID</td>
            <td  class="green-border">$ <?php echo number_format($paid_amount, 2);?></td>
          </tr>
          <?php }?>
          
          <tr>
            <td class="no-border"></td>
            <td colspan="2" style="color:#57B223;font-size" class="no-border">DUE BALANCE</td>
            <td style="color:#57B223" class="no-border">$ 0.00</td>
          </tr>
                    
        </tfoot>
      </table>
      <div id="thanks">Thank you!</div>
      <!-- div id="notices">
        <div>NOTICE:</div>
        <div class="notice">A finance charge of 1.5% will be made on unpaid balances after 30 days.</div>
      </div-->
</page>
<?php }?>