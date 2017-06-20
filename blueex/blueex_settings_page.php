<?php 
    if($_POST['blueex_hidden'] == 'Y') {
        //Form data sent
        $blueex_usernameStore = $_POST['blueex_username'];
        update_option('blueex_username', $blueex_usernameStore);
         
        $blueex_paswwordStore = $_POST['blueex_paswword'];
        update_option('blueex_paswword', $blueex_paswwordStore);
        
        $eventName = $_POST['eventName'];
        update_option('eventName_blueex_called', $eventName);

        $blueex_username = get_option('blueex_username');
        $blueex_paswword = get_option('blueex_paswword');
        $EventName = get_option('eventName_blueex_called');
        ?>
        <div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
        <?php
    } else {
        //Normal page display
        $blueex_username = get_option('blueex_username');
        $blueex_paswword = get_option('blueex_paswword');
        $EventName = get_option('eventName_blueex_called');
        
    }
?>
<div class="wrap">
    <?php    echo "<h2>" . __( 'Blue Ex Settings', 'blueex_utdwp' ) . "</h2>"; ?>
     
    <form name="blueex_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <input type="hidden" name="blueex_hidden" value="Y">
        <p><?php _e("Blue Ex Username: " ); ?><input required="required" type="text" name="blueex_username" value="<?php echo $blueex_username; ?>" size="20"></p>
        <p><?php _e("Blue Ex Password: " ); ?><input required="required" type="number" min="1" name="blueex_paswword" value="<?php echo $blueex_paswword; ?>" size="20"></p>
         <p>
         <?php _e("Event on which API called: " ); ?>
         <select name="eventName">
            <option value="woocommerce_order_status_processing" <?php if($EventName == 'woocommerce_order_status_processing') { ?>selected="selected" <?php } ?>>Order Processing</option>
            <option value="woocommerce_order_status_completed" <?php if($EventName == 'woocommerce_order_status_completed') { ?>selected="selected" <?php } ?>>Order Completed</option>
            <option value="woocommerce_thankyou" <?php if($EventName == 'woocommerce_thankyou') { ?>selected="selected" <?php } ?>>On Placing Order/Order Thank you page</option>
         </select>    
         </p>
        <p class="submit">
        <input type="submit" name="Submit" value="<?php _e('Update Options', 'blueex_utdwp' ) ?>" />
        </p>
    </form>
</div>
