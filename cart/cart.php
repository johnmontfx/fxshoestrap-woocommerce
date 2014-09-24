<?php
/**
 * Cart Page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $ss_framework;

wc_print_notices();

do_action( 'woocommerce_before_cart' ); ?>

<form action="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" method="post">

<?php do_action( 'woocommerce_before_cart_table' ); ?>

<table class="shop_table cart table" cellspacing="0">
	<thead>
		<tr>
			<?php echo $ss_framework->open_col( 'th', array( 'tablet' => 1 ), null, 'product-remove', null ); ?>
			&nbsp;
			<?php echo $ss_framework->close_col( 'th' ); ?>

			<?php echo $ss_framework->open_col( 'th', array( 'tablet' => 2 ), null, 'product-thumbnail hidden-xs', null ); ?>
			&nbsp;
			<?php echo $ss_framework->close_col( 'th' ); ?>

			<?php echo $ss_framework->open_col( 'th', array( 'tablet' => 3 ), null, 'product-name', null ); ?>
			<?php _e( 'Product', 'woocommerce' ); ?>
			<?php echo $ss_framework->close_col( 'th' ); ?>

			<?php echo $ss_framework->open_col( 'th', array( 'tablet' => 2 ), null, 'product-price', null ); ?>
			<?php _e( 'Price', 'woocommerce' ); ?>
			<?php echo $ss_framework->close_col( 'th' ); ?>

			<?php echo $ss_framework->open_col( 'th', array( 'tablet' => 2 ), null, 'product-quantity', null ); ?>
			<?php _e( 'Quantity', 'woocommerce' ); ?>
			<?php echo $ss_framework->close_col( 'th' ); ?>

			<?php echo $ss_framework->open_col( 'th', array( 'tablet' => 2 ), null, 'product-subtotal text-right', null ); ?>
			<?php _e( 'Total', 'woocommerce' ); ?>
			<?php echo $ss_framework->close_col( 'th' ); ?>
		</tr>
	</thead>
	<tbody>
		<?php do_action( 'woocommerce_before_cart_contents' ); ?>

		<?php
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				?>
				<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

					<?php echo $ss_framework->open_col( 'td', array( 'tablet' => 1 ), null, 'product-remove', null ); ?>
						<?php
							echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf( '<a href="%s" class="btn btn-danger remove" title="%s"><i class="el-icon-remove"></i></a>', esc_url( WC()->cart->get_remove_url( $cart_item_key ) ), __( 'Remove this item', 'woocommerce' ) ), $cart_item_key );
						?>
					<?php echo $ss_framework->close_col( 'td' ); ?>

					<?php echo $ss_framework->open_col( 'td', array( 'tablet' => 2 ), null, 'product-thumbnail hidden-xs', null ); ?>
						<?php
							$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

							if ( ! $_product->is_visible() )
								echo $thumbnail;
							else
								printf( '<a href="%s">%s</a>', $_product->get_permalink(), $thumbnail );
						?>
					<?php echo $ss_framework->close_col( 'td' ); ?>

					<?php echo $ss_framework->open_col( 'td', array( 'tablet' => 3 ), null, 'product-name', null ); ?>
						<?php
							if ( ! $_product->is_visible() )
								echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
							else
								echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', $_product->get_permalink(), $_product->get_title() ), $cart_item, $cart_item_key );

							// Meta data
							echo WC()->cart->get_item_data( $cart_item );

               				// Backorder notification
               				if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) )
               					echo '<p class="backorder_notification">' . __( 'Available on backorder', 'woocommerce' ) . '</p>';
						?>
					<?php echo $ss_framework->close_col( 'td' ); ?>

					<?php echo $ss_framework->open_col( 'td', array( 'tablet' => 2 ), null, 'product-price', null ); ?>
						<?php
							echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
						?>
					<?php echo $ss_framework->close_col( 'td' ); ?>

					<?php echo $ss_framework->open_col( 'td', array( 'tablet' => 2 ), null, 'product-quantity', null ); ?>
						<?php
							if ( $_product->is_sold_individually() ) {
								$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
							} else {
								$product_quantity = woocommerce_quantity_input( array(
									'input_name'  => "cart[{$cart_item_key}][qty]",
									'input_value' => $cart_item['quantity'],
									'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
								), $_product, false );
							}

							echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key );
						?>
					<?php echo $ss_framework->close_col( 'td' ); ?>

					<?php echo $ss_framework->open_col( 'td', array( 'tablet' => 2 ), null, 'product-subtotal text-right', null ); ?>
						<?php
							echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
						?>
					<?php echo $ss_framework->close_col( 'td' ); ?>
				</tr>
				<?php
			}
		}

		do_action( 'woocommerce_cart_contents' );
		?>
		<tr>
			<td colspan="6" class="actions">
				<?php echo $ss_framework->open_col( 'div', array( 'mobile' => 12 ), null, null, null ); ?>

					<?php if ( WC()->cart->coupons_enabled() ) { ?>
						<div class="coupon row">

							<span class="<?php echo $ss_framework->column_classes( array( 'mobile' => 6 ), null ); ?>">
								<input type="text" name="coupon_code" class="input-text <?php echo $ss_framework->form_input_classes(); ?> " id="coupon_code" value="" placeholder="<?php _e( 'Coupon', 'woocommerce' ); ?>:" style="width:100%;"/>
							</span>
							<span class="<?php echo $ss_framework->column_classes( array( 'mobile' => 6 ), null ); ?>">
								<input type="submit" class="postfix <?php echo $ss_framework->button_classes( 'success', 'block' ); ?> " name="apply_coupon" value="<?php _e( 'Apply Coupon', 'woocommerce' ); ?>" />
							</span>

							<?php do_action('woocommerce_cart_coupon'); ?>

						</div>
					<?php } ?>
					<hr>
				<?php echo $ss_framework->close_col( 'div' ); ?>

				<?php if ( WC()->cart->coupons_enabled() ) : ?>
					<?php echo $ss_framework->open_col( 'div', array( 'mobile' => 12 ), null, 'ss-wc-cart-actions', null ); ?>
				<?php else : ?>
					<div class="ss-wc-cart-actions">
				<?php endif; ?>
					<div class="<?php echo $ss_framework->button_group_classes( null, null, null ); ?>" style="width: 100%;">
						<input type="submit" class="<?php echo $ss_framework->column_classes( array( 'mobile' => 6 ), null ); ?> <?php echo $ss_framework->button_classes( 'default', 'medium', null, null ); ?>" name="update_cart" value="<?php _e( 'Update Cart', 'woocommerce' ); ?>" />
 						<input type="submit" class="<?php echo $ss_framework->column_classes( array( 'mobile' => 6 ), null ); ?> <?php echo $ss_framework->button_classes( 'primary', 'medium', null, 'checkout-button wc-forward' ); ?>" name="proceed" value="<?php _e( 'Proceed to Checkout', 'woocommerce' ); ?>" />
					</div>
					<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
					<hr>
				<?php echo $ss_framework->close_col( 'div' ); ?>
				<?php echo $ss_framework->clearfix(); ?>
				<?php wp_nonce_field( 'woocommerce-cart' ); ?>
			</td>
		</tr>

		<?php do_action( 'woocommerce_after_cart_contents' ); ?>
	</tbody>
</table>

<?php echo $ss_framework->clearfix(); ?>

<?php do_action( 'woocommerce_after_cart_table' ); ?>

<?php echo $ss_framework->clearfix(); ?>

</form>

<div class="cart-collaterals">

	<?php do_action( 'woocommerce_cart_collaterals' ); ?>

	<?php woocommerce_cart_totals(); ?>

	<?php woocommerce_shipping_calculator(); ?>

</div>

<?php echo $ss_framework->clearfix(); ?>

<?php do_action( 'woocommerce_after_cart' ); ?>

<?php echo $ss_framework->clearfix(); ?>