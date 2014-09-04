<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="box">
  <div class="heading">
    <div class="buttons" style="float:right;">
      <a onclick="$('#form').attr('action', '<?php echo $edit_transaction; ?>'); $('#form').submit();" class="button"><?php echo $button_edit; ?></a>
    </div>
  </div>
</div>

<form action="" method="post" enctype="multipart/form-data" id="form">
  <table class="list">
    <thead>
      <tr>
        <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
        <td class="left"><?php echo $column_date_added; ?></td>
        <td class="left"><?php echo $column_status; ?></td>
        <td class="left"><?php echo $column_description; ?></td>
        <td class="right"><?php echo $column_amount; ?></td>      
      </tr>
    </thead>
    <tbody>
      <?php if ($transactions) { ?>
      <?php foreach ($transactions as $transaction) { ?>
      <tr>
        <td style="text-align: center;">
          <?php if ($transaction['selected']) { ?>
            <input type="checkbox" name="selected[]" value="<?php echo $transaction['affiliate_transaction_id']; ?>" id="<?php echo $transaction['affiliate_transaction_id']; ?>_select" checked="checked" />
          <?php } else { ?>
            <input type="checkbox" name="selected[]" value="<?php echo $transaction['affiliate_transaction_id']; ?>" id="<?php echo $transaction['affiliate_transaction_id']; ?>_select" />
          <?php } ?>
        </td>
        <td class="left"><?php echo $transaction['date_added']; ?></td>
        <td class="left">
          <select name="<?php echo $transaction['affiliate_transaction_id']; ?>_status" 
                  id="<?php echo $transaction['affiliate_transaction_id']; ?>_select" 
                  onclick='document.getElementById("<?php echo $transaction['affiliate_transaction_id']; ?>_select").setAttribute("checked","checked");'> 
            <option value=""><?php echo $text_select; ?></option>
            <?php foreach ($transaction_statuses as $status) { ?>
              <?php if ($status['order_status_id'] == $transaction['status_id']) { ?> 
                <option value="<?php echo $status['order_status_id']; ?>" selected="selected"><?php echo $status['name']; ?></option>
              <?php } else { ?>          
                <option value="<?php echo $status['order_status_id']; ?>"><?php echo $status['name']; ?></option>
              <?php } ?>            
            <?php } ?>
          </select>
        </td>
        <td class="left">
          <input type="text" 
                 name="<?php echo $transaction['affiliate_transaction_id']; ?>_description" 
                 id="<?php echo $transaction['affiliate_transaction_id']; ?>_select" 
                 value="<?php echo $transaction['description']; ?>" 
                 onclick='document.getElementById("<?php echo $transaction['affiliate_transaction_id']; ?>_select").setAttribute("checked","checked");'/>
        </td>
        <td class="right"><?php echo $transaction['amount']; ?></td>
      </tr>
      <?php } ?>      
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td class="right"><b><?php echo $text_order_product_total; ?></b></td>
        <td class="right"><?php echo $total_order_product; ?></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td class="right"><b><?php echo $text_commission_total; ?></b></td>
        <td class="right"><?php echo $total_commission; ?></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td class="right"><b><?php echo $text_total_due; ?></b></td>
        <td class="right"><?php echo $total; ?></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td class="right"><b><?php echo $text_transaction_total2; ?></b></td>
        <td class="right"><?php echo $total_transaction; ?></td>
      </tr>      
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td class="right"><b><?php echo $text_balance_due; ?></b></td>
        <td class="right"><?php echo $balance_due; ?></td>
      </tr>
      <?php } else { ?>
      <tr>
        <td class="center" colspan="5"><?php echo $text_no_results; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</form>

<div class="pagination"><?php echo $pagination; ?></div>