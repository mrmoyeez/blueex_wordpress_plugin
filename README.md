# Blue Ex Wordpress Plugin
Blue ex is a courier service. This plugin will enable you to connect [Blue ex](http://blue-ex.com/) API and your [woocommerce](https://wordpress.org/plugins/woocommerce/) store.You will find the configuration menu for this plugin under the _"Settings"_** tab in wordpress dashboard.

#### 1. Creating configuration Page:
First of all, you need to create a configuration page for this plugin in the wordpress admin dashboard.
Below code will call the configuration page.

![alt text](https://github.com/virtualforce/blueex_wordpress_plugin/blob/master/images/configuration.png "call to configuration page")

_blueex_settings_page.php_** is the page that is responsible for the configuration. This page contains the form with some basic configuration as below.

![alt text](https://github.com/virtualforce/blueex_wordpress_plugin/blob/master/images/configuration1.png "configuration page")

#### 2. Dependency Check:
Now let's move to the core side of this plugin. As this plugin is going to work with woocommerce that is another plugin of wordpress so we need to make sure that plugin is installed and active.If woocommerce is installed, we will create a new database table for storing tracking no.And below code do this work.

![alt text](https://github.com/virtualforce/blueex_wordpress_plugin/blob/master/images/woocommerce-check.png "Dependency Check")

#### 3. Call to Main Function:
Now we have to write a function that will send data in XML form to Blue Ex API and that function is 
`blueex_display_order_data_processing_status()`

In this function , i have put several checks for order details and city codes that was provided by blue ex support.

Now above discussed function needs to call on some event/action of wordpress.And that is done by below code.

![alt text](https://github.com/virtualforce/blueex_wordpress_plugin/blob/master/images/function_call.png "Call to main function")


#### 4. Show Tracking number on user's side:
To show tracking no to user's side order detail page, you need to call another hook `(woocommerce_view_order )` of wordpress and `blueex_display_order_view_data_user_account` is a function that is attached to this hook.

```php
add_action( 'woocommerce_view_order', 'blueex_display_order_view_data_user_account', 30 );
```

#### 5. Show Tracking number on admin's side:
Now if you want to show the tracking number against each order in the admin side order's detail page, we will call another hook `(woocommerce_admin_order_data_after_shipping_address)`. I have put the tracking no under the shipping section.But we can show it in billing section or order detail section as well.`blueex_display_order_data_in_admin_order_page` is a function attached with this hook.

```php
add_action('woocommerce_admin_order_data_after_shipping_address','blueex_display_order_data_in_admin_order_page' );
```

### References:

 * [Hooks](https://developer.wordpress.org/plugins/hooks/) for basic hooks of wordpress
 * [WPMUDEV](https://premium.wpmudev.org/blog/creating-database-tables-for-plugins/) for creating database tables while installing plugin
 * [squelchdesign](http://squelchdesign.com/web-design-newbury/woocommerce-detecting-order-complete-on-order-completion/) for hooks on order status