<?php if (!isset($redirect)) { ?>

    <div class="checkout-product">
        <table class="table">
            <thead>
                <tr>
                    <td class="name"><?php echo $column_name; ?></td>
                    <td class="model"><?php echo $column_model; ?></td>
                    <td class="quantity"><?php echo $column_quantity; ?></td>
                    <td class="price"><?php echo $column_price; ?></td>
                    <td class="total"><?php echo $column_total; ?></td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product) { ?>
                    <?php if($product['recurring']): ?>
                        <tr>
                            <td colspan="6" style="border:none;">
                                <span class="glyphicon glyphicon-repeat"></span>
                                <span style="float:left;line-height:18px; margin-left:10px;">
                                    <strong><?php echo $text_recurring_item ?></strong>
                                </span>
                                <?php echo $product['profile_description'] ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td class="name">
                            <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                            <?php foreach ($product['option'] as $option) { ?>
                                <br />
                                &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                            <?php }
                            if($product['recurring']): ?>
                                <br />
                                &nbsp;
                                <small><?php echo $text_payment_profile ?>: <?php echo $product['profile_name'] ?></small>
                            <?php endif; ?>
                        </td>
                        <td class="model"><?php echo $product['model']; ?></td>
                        <td class="quantity"><?php echo $product['quantity']; ?></td>
                        <td class="price"><?php echo $product['price']; ?></td>
                        <td class="total"><?php echo $product['total']; ?></td>
                    </tr>
                <?php } ?>
                <?php foreach ($vouchers as $voucher) { ?>
                    <tr>
                        <td class="name"><?php echo $voucher['description']; ?></td>
                        <td class="model"></td>
                        <td class="quantity">1</td>
                        <td class="price"><?php echo $voucher['amount']; ?></td>
                        <td class="total"><?php echo $voucher['amount']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="price"><strong>Sub Total:</strong></td>
                    <td class="total"><?php echo $sub_total; ?></td>
                </tr>
                <tr>
                    <td colspan="4" class="price"><strong>Shipping Total:</strong></td>
                    <td class="total"><?php echo $shipping_total; ?></td>
                </tr>
                <tr>
                    <td colspan="4" class="price"><strong>Total Total:</strong></td>
                    <td class="total"><?php echo $total_total; ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="payment"><?php echo $payment; ?></div>
<?php } else { ?>
	<script type="text/javascript"><!--
		location = '<?php echo $redirect; ?>';
    //--></script>
<?php } ?>