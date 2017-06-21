<?php
   /*
   Plugin Name: Blue EX for Pakistan
   Plugin URI: https://github.com/virtualforce/blueex_wordpress_plugin
   Description: This plugins enables you to connect with Blue Ex API. When any order is generated, you will have unique tracking no that you can use for tracking on your website or on Blue EX Portal.
   Version: 1.0
   Author: Naqash
   Author URI: http://virtualforce.io/
   License: GPL2
   */

//Check Dependencies
register_activation_hook( __FILE__, 'child_plugin_activate' );
function child_plugin_activate(){
    if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) and current_user_can( 'activate_plugins' ) ) {
        // Stop activation redirect and show error
        wp_die('Sorry, but this plugin requires Woocommerce Plugin to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
    }else{
    	//create DB
		    global $wpdb;
			$charset_collate = $wpdb->get_charset_collate();
			$table_name = $wpdb->prefix . 'order_tracking_blueex';

			$sql = "CREATE TABLE $table_name (
				id int(11) NOT NULL AUTO_INCREMENT,
				order_id int(11) NOT NULL,
  				tracking_no bigint(11) NOT NULL,
				UNIQUE KEY id (id)
			) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
    }
}
//settings page
function blueex_admin_actions_settings() {
    add_options_page("Blue Ex Settings", "Blue Ex Settings", 1, "Blue Ex Settings", "blueex_admin");
}
function blueex_admin() {
    include('blueex_settings_page.php');
} 
add_action('admin_menu', 'blueex_admin_actions_settings');

// get the event type on which API will be hit.
$event_on_call = get_option('eventName_blueex_called');

//function that will be called on specific event
function blueex_display_order_data_processing_status($order_id){ 
	$blueex_username = get_option('blueex_username');
	$blueex_paswword = get_option('blueex_paswword');
	
	//get orders detail
	$order_meta = get_post_meta($order_id);
	//nam check for shipping
	if(empty($order_meta['_shipping_first_name'][0]) || empty($order_meta['_shippingg_last_name'][0])){
		$fullname = $order_meta['_billing_first_name'][0].' '.$order_meta['_billing_last_name'][0];
	}else{
		$fullname = $order_meta['_shipping_first_name'][0].' '.$order_meta['_shipping_last_name'][0];
	}
	//address check for shipping
	if(empty($order_meta['_shipping_address_1'][0]) || empty($order_meta['_shipping_address_2'][0])){
		$fulladdress = $order_meta['_billing_address_1'][0].' '.$order_meta['_billing_address_2'][0];
	}else{
		$fulladdress = $order_meta['_shipping_address_1'][0].' '.$order_meta['_shipping_address_2'][0];
	}
	//COUNTRY check for shipping
	if(empty($order_meta['_shipping_country'][0])){
		$countryCode = $order_meta['_billing_country'][0];
	}else{
		$countryCode = $order_meta['_shipping_country'][0];
	}
	//City check for shipping
	if(empty($order_meta['_shipping_city'][0])){
		if($order_meta['_billing_city'][0] == 'Abdul Hakeem'){
			$city = 'AKM';
		}else if($order_meta['_billing_city'][0] == 'Abottabad'){
			$city = 'ABT';
		}else if($order_meta['_billing_city'][0] == 'Ahmedpur East'){
			$city = 'APE';
		}else if($order_meta['_billing_city'][0] == 'Ahmedpur Lamma'){
			$city = 'APL';
		}else if($order_meta['_billing_city'][0] == 'Akora Khattak'){
			$city = 'AKK';
		}else if($order_meta['_billing_city'][0] == 'Alipur'){
			$city = 'APR';
		}else if($order_meta['_billing_city'][0] == 'Alipur chatta'){
			$city = 'APC';
		}else if($order_meta['_billing_city'][0] == 'Aman Garh'){
			$city = 'AMR';
		}else if($order_meta['_billing_city'][0] == 'Aman Kot'){
			$city = 'ANK';
		}else if($order_meta['_billing_city'][0] == 'Arifwala'){
			$city = 'AFA';
		}else if($order_meta['_billing_city'][0] == 'Attock'){
			$city = 'ATT';
		}else if($order_meta['_billing_city'][0] == 'Badin'){
			$city = 'BDN';
		}else if($order_meta['_billing_city'][0] == 'Bahawal nagar'){
			$city = 'NGR';
		}else if($order_meta['_billing_city'][0] == 'Balakot'){
			$city = 'BLK';
		}else if($order_meta['_billing_city'][0] == 'Bannuu'){
			$city = 'BNP';
		}else if($order_meta['_billing_city'][0] == 'Bari Kot'){
			$city = 'BRK';
		}else if($order_meta['_billing_city'][0] == 'Basirpur'){
			$city = 'BSP';
		}else if($order_meta['_billing_city'][0] == 'Batgram'){
			$city = 'BGR';
		}else if($order_meta['_billing_city'][0] == 'Batkhela'){
			$city = 'BTK';
		}else if($order_meta['_billing_city'][0] == 'Behal'){
			$city = 'BHL';
		}else if($order_meta['_billing_city'][0] == 'Behrain'){
			$city = 'BHN';
		}else if($order_meta['_billing_city'][0] == 'Besham'){
			$city = 'BSH';
		}else if($order_meta['_billing_city'][0] == 'Bhagatwala'){
			$city = 'BTW';
		}else if($order_meta['_billing_city'][0] == 'Bhai Pheru'){
			$city = 'BPR';
		}else if($order_meta['_billing_city'][0] == 'Bhakkar'){
			$city = 'BKK';
		}else if($order_meta['_billing_city'][0] == 'Bhawalpur'){
			$city = 'BHV';
		}else if($order_meta['_billing_city'][0] == 'Bhikky'){
			$city = 'BHK';
		}else if($order_meta['_billing_city'][0] == 'Bhimber'){
			$city = 'BMR';
		}else if($order_meta['_billing_city'][0] == 'Bilot Shareef'){
			$city = 'BSF';
		}else if($order_meta['_billing_city'][0] == 'Bunair'){
			$city = 'BNR';
		}else if($order_meta['_billing_city'][0] == 'Burewala'){
			$city = 'BRW';
		}else if($order_meta['_billing_city'][0] == 'Chak Dera'){
			$city = 'CKD';
		}else if($order_meta['_billing_city'][0] == 'Chak Jhumra'){
			$city = 'CJM';
		}else if($order_meta['_billing_city'][0] == 'Chakwal'){
			$city = 'CKL';
		}else if($order_meta['_billing_city'][0] == 'Char Bagh'){
			$city = 'CHR';
		}else if($order_meta['_billing_city'][0] == 'Charsadda'){
			$city = 'CSD';
		}else if($order_meta['_billing_city'][0] == 'Chashma'){
			$city = 'CMA';
		}else if($order_meta['_billing_city'][0] == 'Chawinda'){
			$city = 'CWD';
		}else if($order_meta['_billing_city'][0] == 'Chichawatni'){
			$city = 'CCW';
		}else if($order_meta['_billing_city'][0] == 'Chiniot'){
			$city = 'IOT';
		}else if($order_meta['_billing_city'][0] == 'Chishtian'){
			$city = 'CHT';
		}else if($order_meta['_billing_city'][0] == 'Choti Zareen'){
			$city = 'CZN';
		}else if($order_meta['_billing_city'][0] == 'Chowk Azam'){
			$city = 'CKA';
		}else if($order_meta['_billing_city'][0] == 'Chowk Munda'){
			$city = 'CKM';
		}else if($order_meta['_billing_city'][0] == 'Daajal'){
			$city = 'DJL';
		}else if($order_meta['_billing_city'][0] == 'Dadu'){
			$city = 'DDU';
		}else if($order_meta['_billing_city'][0] == 'Dadyal AK'){
			$city = 'DDL';
		}else if($order_meta['_billing_city'][0] == 'Daharki'){
			$city = 'DKI';
		}else if($order_meta['_billing_city'][0] == 'Daraban'){
			$city = 'DRB';
		}else if($order_meta['_billing_city'][0] == 'Dargai'){
			$city = 'DRG';
		}else if($order_meta['_billing_city'][0] == 'Dariya Khan'){
			$city = 'DRK';
		}else if($order_meta['_billing_city'][0] == 'Daska'){
			$city = 'DSK';
		}else if($order_meta['_billing_city'][0] == 'Daud Khel'){
			$city = 'DDK';
		}else if($order_meta['_billing_city'][0] == 'Daulatpur'){
			$city = 'DLT';
		}else if($order_meta['_billing_city'][0] == 'Depalpur'){
			$city = 'DPR';
		}else if($order_meta['_billing_city'][0] == 'Dera Allah Yar'){
			$city = 'DAY';
		}else if($order_meta['_billing_city'][0] == 'Dera Ghazi Khan'){
			$city = 'DGK';
		}else if($order_meta['_billing_city'][0] == 'Dera Ismail Khan'){
			$city = 'DIK';
		}else if($order_meta['_billing_city'][0] == 'Dera Murad Jamali'){
			$city = 'DMJ';
		}else if($order_meta['_billing_city'][0] == 'Dharanwala'){
			$city = 'DRW';
		}else if($order_meta['_billing_city'][0] == 'Digri'){
			$city = 'DIG';
		}else if($order_meta['_billing_city'][0] == 'Dina'){
			$city = 'DIN';
		}else if($order_meta['_billing_city'][0] == 'Dinga'){
			$city = 'DNG';
		}else if($order_meta['_billing_city'][0] == 'Donga Bonga'){
			$city = 'DGB';
		}else if($order_meta['_billing_city'][0] == 'Dulywala'){
			$city = 'DYW';
		}else if($order_meta['_billing_city'][0] == 'Dunia pur'){
			$city = 'DNP';
		}else if($order_meta['_billing_city'][0] == 'Esa Khel'){
			$city = 'ESK';
		}else if($order_meta['_billing_city'][0] == 'Faisalabad'){
			$city = 'LYP';
		}else if($order_meta['_billing_city'][0] == 'Faqirwali'){
			$city = 'FRW';
		}else if($order_meta['_billing_city'][0] == 'Fateh Jang'){
			$city = 'FTG';
		}else if($order_meta['_billing_city'][0] == 'Fazilpur'){
			$city = 'FZP';
		}else if($order_meta['_billing_city'][0] == 'Ferozwatowan'){
			$city = 'FTN';
		}else if($order_meta['_billing_city'][0] == 'Fort Abbass'){
			$city = 'FAB';
		}else if($order_meta['_billing_city'][0] == 'Fort Munnro'){
			$city = 'FTM';
		}else if($order_meta['_billing_city'][0] == 'Gadoon Amazai'){
			$city = 'GDA';
		}else if($order_meta['_billing_city'][0] == 'Gaggo Mandi'){
			$city = 'GMD';
		}else if($order_meta['_billing_city'][0] == 'Gahri Kapoora'){
			$city = 'GKP';
		}else if($order_meta['_billing_city'][0] == 'Gakhar Mandi'){
			$city = 'GKM';
		}else if($order_meta['_billing_city'][0] == 'Gambat'){
			$city = 'GBT';
		}else if($order_meta['_billing_city'][0] == 'Garhi Habibullah'){
			$city = 'GHU';
		}else if($order_meta['_billing_city'][0] == 'Gharo Moor'){
			$city = 'GMR';
		}else if($order_meta['_billing_city'][0] == 'Ghazi'){
			$city = 'GZI';
		}else if($order_meta['_billing_city'][0] == 'Ghazi Ghat'){
			$city = 'GHZ';
		}else if($order_meta['_billing_city'][0] == 'Ghotki'){
			$city = 'GTI';
		}else if($order_meta['_billing_city'][0] == 'Gojra'){
			$city = 'RRA';
		}else if($order_meta['_billing_city'][0] == 'Goth Machi'){
			$city = 'GMI';
		}else if($order_meta['_billing_city'][0] == 'Guddu'){
			$city = 'GDN';
		}else if($order_meta['_billing_city'][0] == 'Gujar khan'){
			$city = 'GKN';
		}else if($order_meta['_billing_city'][0] == 'Gujranwala'){
			$city = 'GUJ';
		}else if($order_meta['_billing_city'][0] == 'Gujrat'){
			$city = 'GRT';
		}else if($order_meta['_billing_city'][0] == 'Hafizabad '){
			$city = 'HZD';
		}else if($order_meta['_billing_city'][0] == 'Hangu'){
			$city = 'HNG';
		}else if($order_meta['_billing_city'][0] == 'Harappa'){
			$city = 'HRP';
		}else if($order_meta['_billing_city'][0] == 'Haripur'){
			$city = 'HRI';
		}else if($order_meta['_billing_city'][0] == 'Haroonabad'){
			$city = 'HRN';
		}else if($order_meta['_billing_city'][0] == 'Hattar'){
			$city = 'HTR';
		}else if($order_meta['_billing_city'][0] == 'Hassan abdal'){
			$city = 'HSN';
		}else if($order_meta['_billing_city'][0] == 'Hattian'){
			$city = 'HTN';
		}else if($order_meta['_billing_city'][0] == 'Havali Lakhan'){
			$city = 'HLK';
		}else if($order_meta['_billing_city'][0] == 'HawiliaN'){
			$city = 'HVL';
		}else if($order_meta['_billing_city'][0] == 'Hazro'){
			$city = 'HAZ';
		}else if($order_meta['_billing_city'][0] == 'Head Marala'){
			$city = 'HDM';
		}else if($order_meta['_billing_city'][0] == 'Hujra Shah Mukeem'){
			$city = 'HHM';
		}else if($order_meta['_billing_city'][0] == 'Hyderabad'){
			$city = 'HDD';
		}else if($order_meta['_billing_city'][0] == 'Hyderabad Thal'){
			$city = 'HTL';
		}else if($order_meta['_billing_city'][0] == 'Iskandarabad'){
			$city = 'ISK';
		}else if($order_meta['_billing_city'][0] == 'Islamabad'){
			$city = 'ISB';
		}else if($order_meta['_billing_city'][0] == 'Jacobabad'){
			$city = 'JCB';
		}else if($order_meta['_billing_city'][0] == 'Jahangira'){
			$city = 'JNA';
		}else if($order_meta['_billing_city'][0] == 'Jalalpur Jattan'){
			$city = 'JJT';
		}else if($order_meta['_billing_city'][0] == 'Jalalpur pirwala'){
			$city = 'JPP';
		}else if($order_meta['_billing_city'][0] == 'Jampur'){
			$city = 'JMR';
		}else if($order_meta['_billing_city'][0] == 'Jamshoro'){
			$city = 'JSR';
		}else if($order_meta['_billing_city'][0] == 'Jand'){
			$city = 'JND';
		}else if($order_meta['_billing_city'][0] == 'Jaranwala'){
			$city = 'JWA';
		}else if($order_meta['_billing_city'][0] == 'Jatlaan'){
			$city = 'JTN';
		}else if($order_meta['_billing_city'][0] == 'Jatoi'){
			$city = 'JOT';
		}else if($order_meta['_billing_city'][0] == 'Jauharabad'){
			$city = 'JRD';
		}else if($order_meta['_billing_city'][0] == 'Jehlum'){
			$city = 'JLM';
		}else if($order_meta['_billing_city'][0] == 'Jhang'){
			$city = 'JHG';
		}else if($order_meta['_billing_city'][0] == 'Jhok Mehar Shah'){
			$city = 'JMS';
		}else if($order_meta['_billing_city'][0] == 'Jin Pur'){
			$city = 'JIN';
		}else if($order_meta['_billing_city'][0] == 'K.N. Shah'){
			$city = 'KNS';
		}else if($order_meta['_billing_city'][0] == 'KHEBAR'){
			$city = 'KYR';
		}else if($order_meta['_billing_city'][0] == 'Kabal'){
			$city = 'KBL';
		}else if($order_meta['_billing_city'][0] == 'Kabirwala'){
			$city = 'KBW';
		}else if($order_meta['_billing_city'][0] == 'Kahuta'){
			$city = 'KHT';
		}else if($order_meta['_billing_city'][0] == 'Kala Bagh'){
			$city = 'KBG';
		}else if($order_meta['_billing_city'][0] == 'Kala Shah Kaku'){
			$city = 'KSK';
		}else if($order_meta['_billing_city'][0] == 'Kalam'){
			$city = 'KLM';
		}else if($order_meta['_billing_city'][0] == 'Kallar Kahar'){
			$city = 'KKR';
		}else if($order_meta['_billing_city'][0] == 'Kallar Saddiyian'){
			$city = 'KSD';
		}else if($order_meta['_billing_city'][0] == 'Kaloorkot'){
			$city = 'KLK';
		}else if($order_meta['_billing_city'][0] == 'Kamalia'){
			$city = 'KML';
		}else if($order_meta['_billing_city'][0] == 'Kameer'){
			$city = 'KMR';
		}else if($order_meta['_billing_city'][0] == 'Kamoke'){
			$city = 'KMK';
		}else if($order_meta['_billing_city'][0] == 'Kamra'){
			$city = 'KAM';
		}else if($order_meta['_billing_city'][0] == 'Kandh Kot'){
			$city = 'KKK';
		}else if($order_meta['_billing_city'][0] == 'Kandiaro'){
			$city = 'KDR';
		}else if($order_meta['_billing_city'][0] == 'Kanju Township'){
			$city = 'KTS';
		}else if($order_meta['_billing_city'][0] == 'Karachi'){
			$city = 'KHI';
		}else if($order_meta['_billing_city'][0] == 'Karak'){
			$city = 'KRK';
		}else if($order_meta['_billing_city'][0] == 'Kashmore'){
			$city = 'KSM';
		}else if($order_meta['_billing_city'][0] == 'Kasur'){
			$city = 'KSR';
		}else if($order_meta['_billing_city'][0] == 'Katlang'){
			$city = 'KTL';
		}else if($order_meta['_billing_city'][0] == 'Khaghan'){
			$city = 'KGN';
		}else if($order_meta['_billing_city'][0] == 'Khairpur Mirs'){
			$city = 'KHM';
		}else if($order_meta['_billing_city'][0] == 'Khan Bela'){
			$city = 'NBA';
		}else if($order_meta['_billing_city'][0] == 'Khanewal'){
			$city = 'KWL';
		}else if($order_meta['_billing_city'][0] == 'Khanpur'){
			$city = 'NPR';
		}else if($order_meta['_billing_city'][0] == 'Khanpur dem'){
			$city = 'KPD';
		}else if($order_meta['_billing_city'][0] == 'Khansar'){
			$city = 'KHS';
		}else if($order_meta['_billing_city'][0] == 'Kharian'){
			$city = 'KRN';
		}else if($order_meta['_billing_city'][0] == 'Khawaza Khela'){
			$city = 'KZW';
		}else if($order_meta['_billing_city'][0] == 'Khewra'){
			$city = 'KEW';
		}else if($order_meta['_billing_city'][0] == 'Khichiwala'){
			$city = 'KCI';
		}else if($order_meta['_billing_city'][0] == 'Khurrianwala'){
			$city = 'KRW';
		}else if($order_meta['_billing_city'][0] == 'Khushab'){
			$city = 'SAB';
		}else if($order_meta['_billing_city'][0] == 'Klaske'){
			$city = 'KLS';
		}else if($order_meta['_billing_city'][0] == 'Kohat'){
			$city = 'OHT';
		}else if($order_meta['_billing_city'][0] == 'Kolachi'){
			$city = 'KLH';
		}else if($order_meta['_billing_city'][0] == 'Kot Addu'){
			$city = 'KOT';
		}else if($order_meta['_billing_city'][0] == 'Kot Chutta'){
			$city = 'KOC';
		}else if($order_meta['_billing_city'][0] == 'Kot Mithin'){
			$city = 'KMT';
		}else if($order_meta['_billing_city'][0] == 'Kot Momin'){
			$city = 'KTM';
		}else if($order_meta['_billing_city'][0] == 'Kot Qaisarani'){
			$city = 'KQS';
		}else if($order_meta['_billing_city'][0] == 'Kotla Arab Ali Khan'){
			$city = 'KRB';
		}else if($order_meta['_billing_city'][0] == 'Kotly AK'){
			$city = 'KOL';
		}else if($order_meta['_billing_city'][0] == 'Kotly Loharana'){
			$city = 'KLA';
		}else if($order_meta['_billing_city'][0] == 'Kunri'){
			$city = 'KNI';
		}else if($order_meta['_billing_city'][0] == 'Kurram Agency'){
			$city = 'KMY';
		}else if($order_meta['_billing_city'][0] == 'Lachi'){
			$city = 'LCH';
		}else if($order_meta['_billing_city'][0] == 'Lahore'){
			$city = 'LHE';
		}else if($order_meta['_billing_city'][0] == 'Lakki Marwat'){
			$city = 'LKI';
		}else if($order_meta['_billing_city'][0] == 'Lala Musa'){
			$city = 'LLM';
		}else if($order_meta['_billing_city'][0] == 'Larkana'){
			$city = 'LAR';
		}else if($order_meta['_billing_city'][0] == 'Layya'){
			$city = 'LYY';
		}else if($order_meta['_billing_city'][0] == 'Liaqat Pur'){
			$city = 'LQR';
		}else if($order_meta['_billing_city'][0] == 'Lodhran'){
			$city = 'LOD';
		}else if($order_meta['_billing_city'][0] == 'Lower Dir'){
			$city = 'LDR';
		}else if($order_meta['_billing_city'][0] == 'Maidain'){
			$city = 'MID';
		}else if($order_meta['_billing_city'][0] == 'Malakand'){
			$city = 'MKN';
		}else if($order_meta['_billing_city'][0] == 'Malam Jabba'){
			$city = 'MMJ';
		}else if($order_meta['_billing_city'][0] == 'Malikwal'){
			$city = 'MKL';
		}else if($order_meta['_billing_city'][0] == 'Mandi bahauddin'){
			$city = 'MBD';
		}else if($order_meta['_billing_city'][0] == 'Mandra'){
			$city = 'MND';
		}else if($order_meta['_billing_city'][0] == 'Manekra'){
			$city = 'MNK';
		}else if($order_meta['_billing_city'][0] == 'Manga Mandi'){
			$city = 'MMD';
		}else if($order_meta['_billing_city'][0] == 'Mangla'){
			$city = 'MGL';
		}else if($order_meta['_billing_city'][0] == 'Mansahra'){
			$city = 'MNA';
		}else if($order_meta['_billing_city'][0] == 'Mardan'){
			$city = 'MDN';
		}else if($order_meta['_billing_city'][0] == 'Maroot'){
			$city = 'MRT';
		}else if($order_meta['_billing_city'][0] == 'Matta'){
			$city = 'MTA';
		}else if($order_meta['_billing_city'][0] == 'Mehar'){
			$city = 'MHR';
		}else if($order_meta['_billing_city'][0] == 'Mehrabpur'){
			$city = 'MRB';
		}else if($order_meta['_billing_city'][0] == 'Melsi'){
			$city = 'MLS';
		}else if($order_meta['_billing_city'][0] == 'Mian Channu'){
			$city = 'MCN';
		}else if($order_meta['_billing_city'][0] == 'Mian wali'){
			$city = 'MWI';
		}else if($order_meta['_billing_city'][0] == 'Miclourd Gunj'){
			$city = 'MLG';
		}else if($order_meta['_billing_city'][0] == 'Minchanaabad'){
			$city = 'MHN';
		}else if($order_meta['_billing_city'][0] == 'Mingora'){
			$city = 'MNG';
		}else if($order_meta['_billing_city'][0] == 'Mirpur Azad Kashmir'){
			$city = 'QML';
		}else if($order_meta['_billing_city'][0] == 'Mirpur Khas'){
			$city = 'MPK';
		}else if($order_meta['_billing_city'][0] == 'Mirpur Mathelo'){
			$city = 'MPM';
		}else if($order_meta['_billing_city'][0] == 'Mithi'){
			$city = 'MTH';
		}else if($order_meta['_billing_city'][0] == 'Mitiari'){
			$city = 'MIT';
		}else if($order_meta['_billing_city'][0] == 'Moro'){
			$city = 'MRO';
		}else if($order_meta['_billing_city'][0] == 'Muhammadpur'){
			$city = 'MDP';
		}else if($order_meta['_billing_city'][0] == 'Multan'){
			$city = 'MUX';
		}else if($order_meta['_billing_city'][0] == 'Muridkay'){
			$city = 'MRY';
		}else if($order_meta['_billing_city'][0] == 'Murree'){
			$city = 'REE';
		}else if($order_meta['_billing_city'][0] == 'Muzaffar gargh'){
			$city = 'MZG';
		}else if($order_meta['_billing_city'][0] == 'Muzaffarabad'){
			$city = 'MAK';
		}else if($order_meta['_billing_city'][0] == 'Naran'){
			$city = 'NRN';
		}else if($order_meta['_billing_city'][0] == 'Narowal'){
			$city = 'NRL';
		}else if($order_meta['_billing_city'][0] == 'Nawabshah'){
			$city = 'WNS';
		}else if($order_meta['_billing_city'][0] == 'Nooriabad'){
			$city = 'NRD';
		}else if($order_meta['_billing_city'][0] == 'Noshero Feroze'){
			$city = 'NFZ';
		}else if($order_meta['_billing_city'][0] == 'Notak'){
			$city = 'NTK';
		}else if($order_meta['_billing_city'][0] == 'Noudero'){
			$city = 'NRO';
		}else if($order_meta['_billing_city'][0] == 'Nowshera'){
			$city = 'NOW';
		}else if($order_meta['_billing_city'][0] == 'Nowshera virka'){
			$city = 'NOV';
		}else if($order_meta['_billing_city'][0] == 'Oghi'){
			$city = 'OGI';
		}else if($order_meta['_billing_city'][0] == 'Okara'){
			$city = 'OKR';
		}else if($order_meta['_billing_city'][0] == 'Pabbi'){
			$city = 'PBI';
		}else if($order_meta['_billing_city'][0] == 'Paharpur'){
			$city = 'PHR';
		}else if($order_meta['_billing_city'][0] == 'Paighaan'){
			$city = 'PGN';
		}else if($order_meta['_billing_city'][0] == 'Pak Pattan Sharif'){
			$city = 'PPS';
		}else if($order_meta['_billing_city'][0] == 'Panj Garian'){
			$city = 'PGR';
		}else if($order_meta['_billing_city'][0] == 'Panno aqil'){
			$city = 'PNQ';
		}else if($order_meta['_billing_city'][0] == 'Parachanar'){
			$city = 'PCR';
		}else if($order_meta['_billing_city'][0] == 'Paroa'){
			$city = 'PRV';
		}else if($order_meta['_billing_city'][0] == 'Pasroor'){
			$city = 'PRR';
		}else if($order_meta['_billing_city'][0] == 'Pattoki'){
			$city = 'PTI';
		}else if($order_meta['_billing_city'][0] == 'Peshawar'){
			$city = 'PEW';
		}else if($order_meta['_billing_city'][0] == 'Pezo'){
			$city = 'PZO';
		}else if($order_meta['_billing_city'][0] == 'Phalia'){
			$city = 'PLA';
		}else if($order_meta['_billing_city'][0] == 'Pind dadan Khan'){
			$city = 'PDK';
		}else if($order_meta['_billing_city'][0] == 'Pindi Gheb'){
			$city = 'PGB';
		}else if($order_meta['_billing_city'][0] == 'Pindi bhattiya'){
			$city = 'PBT';
		}else if($order_meta['_billing_city'][0] == 'Piplan'){
			$city = 'PPN';
		}else if($order_meta['_billing_city'][0] == 'Pir Mahal'){
			$city = 'PML';
		}else if($order_meta['_billing_city'][0] == 'Qamber Ali Khan'){
			$city = 'QAK';
		}else if($order_meta['_billing_city'][0] == 'Qasba Gujrat'){
			$city = 'QGT';
		}else if($order_meta['_billing_city'][0] == 'Qila Deedar Singh'){
			$city = 'QDS';
		}else if($order_meta['_billing_city'][0] == 'Quaid abad'){
			$city = 'QBD';
		}else if($order_meta['_billing_city'][0] == 'Quetta'){
			$city = 'UET';
		}else if($order_meta['_billing_city'][0] == 'Rabwah'){
			$city = 'AWH';
		}else if($order_meta['_billing_city'][0] == 'Rahim yar khan'){
			$city = 'RYK';
		}else if($order_meta['_billing_city'][0] == 'Raiwind'){
			$city = 'RND';
		}else if($order_meta['_billing_city'][0] == 'Rajanpur'){
			$city = 'RJP';
		}else if($order_meta['_billing_city'][0] == 'Rangpur'){
			$city = 'RNG';
		}else if($order_meta['_billing_city'][0] == 'Ranipur'){
			$city = 'RNP';
		}else if($order_meta['_billing_city'][0] == 'Rashakae'){
			$city = 'RAS';
		}else if($order_meta['_billing_city'][0] == 'Rato Dero'){
			$city = 'RTD';
		}else if($order_meta['_billing_city'][0] == 'Rawalpindi'){
			$city = 'RWP';
		}else if($order_meta['_billing_city'][0] == 'Rawat'){
			$city = 'RWT';
		}else if($order_meta['_billing_city'][0] == 'Renala Khurd'){
			$city = 'RLK';
		}else if($order_meta['_billing_city'][0] == 'Risalpur'){
			$city = 'RSP';
		}else if($order_meta['_billing_city'][0] == 'Rohri'){
			$city = 'RHI';
		}else if($order_meta['_billing_city'][0] == 'Rojhan'){
			$city = 'RJN';
		}else if($order_meta['_billing_city'][0] == 'Rukni'){
			$city = 'RKN';
		}else if($order_meta['_billing_city'][0] == 'Rustam'){
			$city = 'RUS';
		}else if($order_meta['_billing_city'][0] == 'SHOREKOT CANTT'){
			$city = 'SKC';
		}else if($order_meta['_billing_city'][0] == 'Sadiqabad'){
			$city = 'SQD';
		}else if($order_meta['_billing_city'][0] == 'Sahiwal'){
			$city = 'SWL';
		}else if($order_meta['_billing_city'][0] == 'Saidu Shareef'){
			$city = 'SDF';
		}else if($order_meta['_billing_city'][0] == 'Sakhi Sarwar'){
			$city = 'SIS';
		}else if($order_meta['_billing_city'][0] == 'Sambrial'){
			$city = 'SBR';
		}else if($order_meta['_billing_city'][0] == 'Samundri'){
			$city = 'SAM';
		}else if($order_meta['_billing_city'][0] == 'Sanghar'){
			$city = 'SAN';
		}else if($order_meta['_billing_city'][0] == 'Sangla Hills'){
			$city = 'SHL';
		}else if($order_meta['_billing_city'][0] == 'Sanjarpur'){
			$city = 'SJP';
		}else if($order_meta['_billing_city'][0] == 'Sara-a Mahajar'){
			$city = 'SMH';
		}else if($order_meta['_billing_city'][0] == 'Sara-a-Naurang'){
			$city = 'SRG';
		}else if($order_meta['_billing_city'][0] == 'Sara-e-alamgir'){
			$city = 'SIR';
		}else if($order_meta['_billing_city'][0] == 'Sargodha'){
			$city = 'SGD';
		}else if($order_meta['_billing_city'][0] == 'Satiayana '){
			$city = 'STN';
		}else if($order_meta['_billing_city'][0] == 'Sawabi'){
			$city = 'SWB';
		}else if($order_meta['_billing_city'][0] == 'Sehwan Sharif'){
			$city = 'SHN';
		}else if($order_meta['_billing_city'][0] == 'ShahJamal'){
			$city = 'SJL';
		}else if($order_meta['_billing_city'][0] == 'Shahbaz Khel'){
			$city = 'SBZ';
		}else if($order_meta['_billing_city'][0] == 'Shahdara'){
			$city = 'SHD';
		}else if($order_meta['_billing_city'][0] == 'Shahkot'){
			$city = 'SHK';
		}else if($order_meta['_billing_city'][0] == 'Shahpur Saddar'){
			$city = 'SPS';
		}else if($order_meta['_billing_city'][0] == 'Shakargarh'){
			$city = 'SGR';
		}else if($order_meta['_billing_city'][0] == 'Shehdadpur'){
			$city = 'SHP';
		}else if($order_meta['_billing_city'][0] == 'Sheik Maltoon'){
			$city = 'SMN';
		}else if($order_meta['_billing_city'][0] == 'Sher Garh'){
			$city = 'SHG';
		}else if($order_meta['_billing_city'][0] == 'Shiekhopura'){
			$city = 'SPA';
		}else if($order_meta['_billing_city'][0] == 'Shikarpur'){
			$city = 'SIP';
		}else if($order_meta['_billing_city'][0] == 'Shinkyari'){
			$city = 'SHY';
		}else if($order_meta['_billing_city'][0] == 'Shorkot'){
			$city = 'SQT';
		}else if($order_meta['_billing_city'][0] == 'Shuja Abad'){
			$city = 'SHJ';
		}else if($order_meta['_billing_city'][0] == 'Sialkot'){
			$city = 'SKT';
		}else if($order_meta['_billing_city'][0] == 'Sillanwali'){
			$city = 'SLW';
		}else if($order_meta['_billing_city'][0] == 'Skrand'){
			$city = 'SKN';
		}else if($order_meta['_billing_city'][0] == 'Sohawa'){
			$city = 'SOW';
		}else if($order_meta['_billing_city'][0] == 'Sukkur'){
			$city = 'SKZ';
		}else if($order_meta['_billing_city'][0] == 'Sumandari'){
			$city = 'SML';
		}else if($order_meta['_billing_city'][0] == 'Sundar'){
			$city = 'SDR';
		}else if($order_meta['_billing_city'][0] == 'Swat'){
			$city = 'SWT';
		}else if($order_meta['_billing_city'][0] == 'Tabbi Qaisarani'){
			$city = 'TQS';
		}else if($order_meta['_billing_city'][0] == 'Takhatbai'){
			$city = 'TKB';
		}else if($order_meta['_billing_city'][0] == 'Talagang'){
			$city = 'TLG';
		}else if($order_meta['_billing_city'][0] == 'Tandiliyawala'){
			$city = 'TDW';
		}else if($order_meta['_billing_city'][0] == 'Tando Adam'){
			$city = 'TDA';
		}else if($order_meta['_billing_city'][0] == 'Tando Allah Yar'){
			$city = 'TDY';
		}else if($order_meta['_billing_city'][0] == 'Tando Jam'){
			$city = 'TDJ';
		}else if($order_meta['_billing_city'][0] == 'Tando Muhammad Khan'){
			$city = 'TMK';
		}else if($order_meta['_billing_city'][0] == 'Tank'){
			$city = 'TAN';
		}else if($order_meta['_billing_city'][0] == 'Tarbela'){
			$city = 'TRB';
		}else if($order_meta['_billing_city'][0] == 'Tatlay wali'){
			$city = 'TTW';
		}else if($order_meta['_billing_city'][0] == 'Taunsa Sharif'){
			$city = 'TNS';
		}else if($order_meta['_billing_city'][0] == 'Taxila'){
			$city = 'TXL';
		}else if($order_meta['_billing_city'][0] == 'Temargarh'){
			$city = 'TMG';
		}else if($order_meta['_billing_city'][0] == 'Tha Kot'){
			$city = 'TAK';
		}else if($order_meta['_billing_city'][0] == 'Thana'){
			$city = 'Thana';
		}else if($order_meta['_billing_city'][0] == 'Thatta'){
			$city = 'THT';
		}else if($order_meta['_billing_city'][0] == 'Tiba Sultanpur'){
			$city = 'TSP';
		}else if($order_meta['_billing_city'][0] == 'Toba tek singh'){
			$city = 'TTS';
		}else if($order_meta['_billing_city'][0] == 'Topi'){
			$city = 'TOP';
		}else if($order_meta['_billing_city'][0] == 'Tranda Muhammad Pannah'){
			$city = 'TMP';
		}else if($order_meta['_billing_city'][0] == 'Ubaro'){
			$city = 'UBR';
		}else if($order_meta['_billing_city'][0] == 'Ugoke'){
			$city = 'UGI';
		}else if($order_meta['_billing_city'][0] == 'Umer Kot'){
			$city = 'UMK';
		}else if($order_meta['_billing_city'][0] == 'Upper Dir'){
			$city = 'UDR';
		}else if($order_meta['_billing_city'][0] == 'Usta Muhammad'){
			$city = 'UST';
		}else if($order_meta['_billing_city'][0] == 'Vehari'){
			$city = 'VRI';
		}else if($order_meta['_billing_city'][0] == 'Wah cantt'){
			$city = 'WAH';
		}else if($order_meta['_billing_city'][0] == 'Wahn Bachran'){
			$city = 'WBN';
		}else if($order_meta['_billing_city'][0] == 'Wahowah'){
			$city = 'WOW';
		}else if($order_meta['_billing_city'][0] == 'Wazirabad'){
			$city = 'WZD';
		}else if($order_meta['_billing_city'][0] == 'Yazman'){
			$city = 'YZM';
		}else if($order_meta['_billing_city'][0] == 'Zafarwal'){
			$city = 'ZFL';
		}else if($order_meta['_billing_city'][0] == 'Zahir peer'){
			$city = 'ZFL';
		}else if($order_meta['_billing_city'][0] == 'shangla'){
			$city = 'SGL';
		}
	}else{
		if($order_meta['_shipping_city'][0] == 'Abdul Hakeem'){
			$city = 'AKM';
		}else if($order_meta['_shipping_city'][0] == 'Abottabad'){
			$city = 'ABT';
		}else if($order_meta['_shipping_city'][0] == 'Ahmedpur East'){
			$city = 'APE';
		}else if($order_meta['_shipping_city'][0] == 'Ahmedpur Lamma'){
			$city = 'APL';
		}else if($order_meta['_shipping_city'][0] == 'Akora Khattak'){
			$city = 'AKK';
		}else if($order_meta['_shipping_city'][0] == 'Alipur'){
			$city = 'APR';
		}else if($order_meta['_shipping_city'][0] == 'Alipur chatta'){
			$city = 'APC';
		}else if($order_meta['_shipping_city'][0] == 'Aman Garh'){
			$city = 'AMR';
		}else if($order_meta['_shipping_city'][0] == 'Aman Kot'){
			$city = 'ANK';
		}else if($order_meta['_shipping_city'][0] == 'Arifwala'){
			$city = 'AFA';
		}else if($order_meta['_shipping_city'][0] == 'Attock'){
			$city = 'ATT';
		}else if($order_meta['_shipping_city'][0] == 'Badin'){
			$city = 'BDN';
		}else if($order_meta['_shipping_city'][0] == 'Bahawal nagar'){
			$city = 'NGR';
		}else if($order_meta['_shipping_city'][0] == 'Balakot'){
			$city = 'BLK';
		}else if($order_meta['_shipping_city'][0] == 'Bannuu'){
			$city = 'BNP';
		}else if($order_meta['_shipping_city'][0] == 'Bari Kot'){
			$city = 'BRK';
		}else if($order_meta['_shipping_city'][0] == 'Basirpur'){
			$city = 'BSP';
		}else if($order_meta['_shipping_city'][0] == 'Batgram'){
			$city = 'BGR';
		}else if($order_meta['_shipping_city'][0] == 'Batkhela'){
			$city = 'BTK';
		}else if($order_meta['_shipping_city'][0] == 'Behal'){
			$city = 'BHL';
		}else if($order_meta['_shipping_city'][0] == 'Behrain'){
			$city = 'BHN';
		}else if($order_meta['_shipping_city'][0] == 'Besham'){
			$city = 'BSH';
		}else if($order_meta['_shipping_city'][0] == 'Bhagatwala'){
			$city = 'BTW';
		}else if($order_meta['_shipping_city'][0] == 'Bhai Pheru'){
			$city = 'BPR';
		}else if($order_meta['_shipping_city'][0] == 'Bhakkar'){
			$city = 'BKK';
		}else if($order_meta['_shipping_city'][0] == 'Bhawalpur'){
			$city = 'BHV';
		}else if($order_meta['_shipping_city'][0] == 'Bhikky'){
			$city = 'BHK';
		}else if($order_meta['_shipping_city'][0] == 'Bhimber'){
			$city = 'BMR';
		}else if($order_meta['_shipping_city'][0] == 'Bilot Shareef'){
			$city = 'BSF';
		}else if($order_meta['_shipping_city'][0] == 'Bunair'){
			$city = 'BNR';
		}else if($order_meta['_shipping_city'][0] == 'Burewala'){
			$city = 'BRW';
		}else if($order_meta['_shipping_city'][0] == 'Chak Dera'){
			$city = 'CKD';
		}else if($order_meta['_shipping_city'][0] == 'Chak Jhumra'){
			$city = 'CJM';
		}else if($order_meta['_shipping_city'][0] == 'Chakwal'){
			$city = 'CKL';
		}else if($order_meta['_shipping_city'][0] == 'Char Bagh'){
			$city = 'CHR';
		}else if($order_meta['_shipping_city'][0] == 'Charsadda'){
			$city = 'CSD';
		}else if($order_meta['_shipping_city'][0] == 'Chashma'){
			$city = 'CMA';
		}else if($order_meta['_shipping_city'][0] == 'Chawinda'){
			$city = 'CWD';
		}else if($order_meta['_shipping_city'][0] == 'Chichawatni'){
			$city = 'CCW';
		}else if($order_meta['_shipping_city'][0] == 'Chiniot'){
			$city = 'IOT';
		}else if($order_meta['_shipping_city'][0] == 'Chishtian'){
			$city = 'CHT';
		}else if($order_meta['_shipping_city'][0] == 'Choti Zareen'){
			$city = 'CZN';
		}else if($order_meta['_shipping_city'][0] == 'Chowk Azam'){
			$city = 'CKA';
		}else if($order_meta['_shipping_city'][0] == 'Chowk Munda'){
			$city = 'CKM';
		}else if($order_meta['_shipping_city'][0] == 'Daajal'){
			$city = 'DJL';
		}else if($order_meta['_shipping_city'][0] == 'Dadu'){
			$city = 'DDU';
		}else if($order_meta['_shipping_city'][0] == 'Dadyal AK'){
			$city = 'DDL';
		}else if($order_meta['_shipping_city'][0] == 'Daharki'){
			$city = 'DKI';
		}else if($order_meta['_shipping_city'][0] == 'Daraban'){
			$city = 'DRB';
		}else if($order_meta['_shipping_city'][0] == 'Dargai'){
			$city = 'DRG';
		}else if($order_meta['_shipping_city'][0] == 'Dariya Khan'){
			$city = 'DRK';
		}else if($order_meta['_shipping_city'][0] == 'Daska'){
			$city = 'DSK';
		}else if($order_meta['_shipping_city'][0] == 'Daud Khel'){
			$city = 'DDK';
		}else if($order_meta['_shipping_city'][0] == 'Daulatpur'){
			$city = 'DLT';
		}else if($order_meta['_shipping_city'][0] == 'Depalpur'){
			$city = 'DPR';
		}else if($order_meta['_shipping_city'][0] == 'Dera Allah Yar'){
			$city = 'DAY';
		}else if($order_meta['_shipping_city'][0] == 'Dera Ghazi Khan'){
			$city = 'DGK';
		}else if($order_meta['_shipping_city'][0] == 'Dera Ismail Khan'){
			$city = 'DIK';
		}else if($order_meta['_shipping_city'][0] == 'Dera Murad Jamali'){
			$city = 'DMJ';
		}else if($order_meta['_shipping_city'][0] == 'Dharanwala'){
			$city = 'DRW';
		}else if($order_meta['_shipping_city'][0] == 'Digri'){
			$city = 'DIG';
		}else if($order_meta['_shipping_city'][0] == 'Dina'){
			$city = 'DIN';
		}else if($order_meta['_shipping_city'][0] == 'Dinga'){
			$city = 'DNG';
		}else if($order_meta['_shipping_city'][0] == 'Donga Bonga'){
			$city = 'DGB';
		}else if($order_meta['_shipping_city'][0] == 'Dulywala'){
			$city = 'DYW';
		}else if($order_meta['_shipping_city'][0] == 'Dunia pur'){
			$city = 'DNP';
		}else if($order_meta['_shipping_city'][0] == 'Esa Khel'){
			$city = 'ESK';
		}else if($order_meta['_shipping_city'][0] == 'Faisalabad'){
			$city = 'LYP';
		}else if($order_meta['_shipping_city'][0] == 'Faqirwali'){
			$city = 'FRW';
		}else if($order_meta['_shipping_city'][0] == 'Fateh Jang'){
			$city = 'FTG';
		}else if($order_meta['_shipping_city'][0] == 'Fazilpur'){
			$city = 'FZP';
		}else if($order_meta['_shipping_city'][0] == 'Ferozwatowan'){
			$city = 'FTN';
		}else if($order_meta['_shipping_city'][0] == 'Fort Abbass'){
			$city = 'FAB';
		}else if($order_meta['_shipping_city'][0] == 'Fort Munnro'){
			$city = 'FTM';
		}else if($order_meta['_shipping_city'][0] == 'Gadoon Amazai'){
			$city = 'GDA';
		}else if($order_meta['_shipping_city'][0] == 'Gaggo Mandi'){
			$city = 'GMD';
		}else if($order_meta['_shipping_city'][0] == 'Gahri Kapoora'){
			$city = 'GKP';
		}else if($order_meta['_shipping_city'][0] == 'Gakhar Mandi'){
			$city = 'GKM';
		}else if($order_meta['_shipping_city'][0] == 'Gambat'){
			$city = 'GBT';
		}else if($order_meta['_shipping_city'][0] == 'Garhi Habibullah'){
			$city = 'GHU';
		}else if($order_meta['_shipping_city'][0] == 'Gharo Moor'){
			$city = 'GMR';
		}else if($order_meta['_shipping_city'][0] == 'Ghazi'){
			$city = 'GZI';
		}else if($order_meta['_shipping_city'][0] == 'Ghazi Ghat'){
			$city = 'GHZ';
		}else if($order_meta['_shipping_city'][0] == 'Ghotki'){
			$city = 'GTI';
		}else if($order_meta['_shipping_city'][0] == 'Gojra'){
			$city = 'RRA';
		}else if($order_meta['_shipping_city'][0] == 'Goth Machi'){
			$city = 'GMI';
		}else if($order_meta['_shipping_city'][0] == 'Guddu'){
			$city = 'GDN';
		}else if($order_meta['_shipping_city'][0] == 'Gujar khan'){
			$city = 'GKN';
		}else if($order_meta['_shipping_city'][0] == 'Gujranwala'){
			$city = 'GUJ';
		}else if($order_meta['_shipping_city'][0] == 'Gujrat'){
			$city = 'GRT';
		}else if($order_meta['_shipping_city'][0] == 'Hafizabad '){
			$city = 'HZD';
		}else if($order_meta['_shipping_city'][0] == 'Hangu'){
			$city = 'HNG';
		}else if($order_meta['_shipping_city'][0] == 'Harappa'){
			$city = 'HRP';
		}else if($order_meta['_shipping_city'][0] == 'Haripur'){
			$city = 'HRI';
		}else if($order_meta['_shipping_city'][0] == 'Haroonabad'){
			$city = 'HRN';
		}else if($order_meta['_shipping_city'][0] == 'Hattar'){
			$city = 'HTR';
		}else if($order_meta['_shipping_city'][0] == 'Hassan abdal'){
			$city = 'HSN';
		}else if($order_meta['_shipping_city'][0] == 'Hattian'){
			$city = 'HTN';
		}else if($order_meta['_shipping_city'][0] == 'Havali Lakhan'){
			$city = 'HLK';
		}else if($order_meta['_shipping_city'][0] == 'HawiliaN'){
			$city = 'HVL';
		}else if($order_meta['_shipping_city'][0] == 'Hazro'){
			$city = 'HAZ';
		}else if($order_meta['_shipping_city'][0] == 'Head Marala'){
			$city = 'HDM';
		}else if($order_meta['_shipping_city'][0] == 'Hujra Shah Mukeem'){
			$city = 'HHM';
		}else if($order_meta['_shipping_city'][0] == 'Hyderabad'){
			$city = 'HDD';
		}else if($order_meta['_shipping_city'][0] == 'Hyderabad Thal'){
			$city = 'HTL';
		}else if($order_meta['_shipping_city'][0] == 'Iskandarabad'){
			$city = 'ISK';
		}else if($order_meta['_shipping_city'][0] == 'Islamabad'){
			$city = 'ISB';
		}else if($order_meta['_shipping_city'][0] == 'Jacobabad'){
			$city = 'JCB';
		}else if($order_meta['_shipping_city'][0] == 'Jahangira'){
			$city = 'JNA';
		}else if($order_meta['_shipping_city'][0] == 'Jalalpur Jattan'){
			$city = 'JJT';
		}else if($order_meta['_shipping_city'][0] == 'Jalalpur pirwala'){
			$city = 'JPP';
		}else if($order_meta['_shipping_city'][0] == 'Jampur'){
			$city = 'JMR';
		}else if($order_meta['_shipping_city'][0] == 'Jamshoro'){
			$city = 'JSR';
		}else if($order_meta['_shipping_city'][0] == 'Jand'){
			$city = 'JND';
		}else if($order_meta['_shipping_city'][0] == 'Jaranwala'){
			$city = 'JWA';
		}else if($order_meta['_shipping_city'][0] == 'Jatlaan'){
			$city = 'JTN';
		}else if($order_meta['_shipping_city'][0] == 'Jatoi'){
			$city = 'JOT';
		}else if($order_meta['_shipping_city'][0] == 'Jauharabad'){
			$city = 'JRD';
		}else if($order_meta['_shipping_city'][0] == 'Jehlum'){
			$city = 'JLM';
		}else if($order_meta['_shipping_city'][0] == 'Jhang'){
			$city = 'JHG';
		}else if($order_meta['_shipping_city'][0] == 'Jhok Mehar Shah'){
			$city = 'JMS';
		}else if($order_meta['_shipping_city'][0] == 'Jin Pur'){
			$city = 'JIN';
		}else if($order_meta['_shipping_city'][0] == 'K.N. Shah'){
			$city = 'KNS';
		}else if($order_meta['_shipping_city'][0] == 'KHEBAR'){
			$city = 'KYR';
		}else if($order_meta['_shipping_city'][0] == 'Kabal'){
			$city = 'KBL';
		}else if($order_meta['_shipping_city'][0] == 'Kabirwala'){
			$city = 'KBW';
		}else if($order_meta['_shipping_city'][0] == 'Kahuta'){
			$city = 'KHT';
		}else if($order_meta['_shipping_city'][0] == 'Kala Bagh'){
			$city = 'KBG';
		}else if($order_meta['_shipping_city'][0] == 'Kala Shah Kaku'){
			$city = 'KSK';
		}else if($order_meta['_shipping_city'][0] == 'Kalam'){
			$city = 'KLM';
		}else if($order_meta['_shipping_city'][0] == 'Kallar Kahar'){
			$city = 'KKR';
		}else if($order_meta['_shipping_city'][0] == 'Kallar Saddiyian'){
			$city = 'KSD';
		}else if($order_meta['_shipping_city'][0] == 'Kaloorkot'){
			$city = 'KLK';
		}else if($order_meta['_shipping_city'][0] == 'Kamalia'){
			$city = 'KML';
		}else if($order_meta['_shipping_city'][0] == 'Kameer'){
			$city = 'KMR';
		}else if($order_meta['_shipping_city'][0] == 'Kamoke'){
			$city = 'KMK';
		}else if($order_meta['_shipping_city'][0] == 'Kamra'){
			$city = 'KAM';
		}else if($order_meta['_shipping_city'][0] == 'Kandh Kot'){
			$city = 'KKK';
		}else if($order_meta['_shipping_city'][0] == 'Kandiaro'){
			$city = 'KDR';
		}else if($order_meta['_shipping_city'][0] == 'Kanju Township'){
			$city = 'KTS';
		}else if($order_meta['_shipping_city'][0] == 'Karachi'){
			$city = 'KHI';
		}else if($order_meta['_shipping_city'][0] == 'Karak'){
			$city = 'KRK';
		}else if($order_meta['_shipping_city'][0] == 'Kashmore'){
			$city = 'KSM';
		}else if($order_meta['_shipping_city'][0] == 'Kasur'){
			$city = 'KSR';
		}else if($order_meta['_shipping_city'][0] == 'Katlang'){
			$city = 'KTL';
		}else if($order_meta['_shipping_city'][0] == 'Khaghan'){
			$city = 'KGN';
		}else if($order_meta['_shipping_city'][0] == 'Khairpur Mirs'){
			$city = 'KHM';
		}else if($order_meta['_shipping_city'][0] == 'Khan Bela'){
			$city = 'NBA';
		}else if($order_meta['_shipping_city'][0] == 'Khanewal'){
			$city = 'KWL';
		}else if($order_meta['_shipping_city'][0] == 'Khanpur'){
			$city = 'NPR';
		}else if($order_meta['_shipping_city'][0] == 'Khanpur dem'){
			$city = 'KPD';
		}else if($order_meta['_shipping_city'][0] == 'Khansar'){
			$city = 'KHS';
		}else if($order_meta['_shipping_city'][0] == 'Kharian'){
			$city = 'KRN';
		}else if($order_meta['_shipping_city'][0] == 'Khawaza Khela'){
			$city = 'KZW';
		}else if($order_meta['_shipping_city'][0] == 'Khewra'){
			$city = 'KEW';
		}else if($order_meta['_shipping_city'][0] == 'Khichiwala'){
			$city = 'KCI';
		}else if($order_meta['_shipping_city'][0] == 'Khurrianwala'){
			$city = 'KRW';
		}else if($order_meta['_shipping_city'][0] == 'Khushab'){
			$city = 'SAB';
		}else if($order_meta['_shipping_city'][0] == 'Klaske'){
			$city = 'KLS';
		}else if($order_meta['_shipping_city'][0] == 'Kohat'){
			$city = 'OHT';
		}else if($order_meta['_shipping_city'][0] == 'Kolachi'){
			$city = 'KLH';
		}else if($order_meta['_shipping_city'][0] == 'Kot Addu'){
			$city = 'KOT';
		}else if($order_meta['_shipping_city'][0] == 'Kot Chutta'){
			$city = 'KOC';
		}else if($order_meta['_shipping_city'][0] == 'Kot Mithin'){
			$city = 'KMT';
		}else if($order_meta['_shipping_city'][0] == 'Kot Momin'){
			$city = 'KTM';
		}else if($order_meta['_shipping_city'][0] == 'Kot Qaisarani'){
			$city = 'KQS';
		}else if($order_meta['_shipping_city'][0] == 'Kotla Arab Ali Khan'){
			$city = 'KRB';
		}else if($order_meta['_shipping_city'][0] == 'Kotly AK'){
			$city = 'KOL';
		}else if($order_meta['_shipping_city'][0] == 'Kotly Loharana'){
			$city = 'KLA';
		}else if($order_meta['_shipping_city'][0] == 'Kunri'){
			$city = 'KNI';
		}else if($order_meta['_shipping_city'][0] == 'Kurram Agency'){
			$city = 'KMY';
		}else if($order_meta['_shipping_city'][0] == 'Lachi'){
			$city = 'LCH';
		}else if($order_meta['_shipping_city'][0] == 'Lahore'){
			$city = 'LHE';
		}else if($order_meta['_shipping_city'][0] == 'Lakki Marwat'){
			$city = 'LKI';
		}else if($order_meta['_shipping_city'][0] == 'Lala Musa'){
			$city = 'LLM';
		}else if($order_meta['_shipping_city'][0] == 'Larkana'){
			$city = 'LAR';
		}else if($order_meta['_shipping_city'][0] == 'Layya'){
			$city = 'LYY';
		}else if($order_meta['_shipping_city'][0] == 'Liaqat Pur'){
			$city = 'LQR';
		}else if($order_meta['_shipping_city'][0] == 'Lodhran'){
			$city = 'LOD';
		}else if($order_meta['_shipping_city'][0] == 'Lower Dir'){
			$city = 'LDR';
		}else if($order_meta['_shipping_city'][0] == 'Maidain'){
			$city = 'MID';
		}else if($order_meta['_shipping_city'][0] == 'Malakand'){
			$city = 'MKN';
		}else if($order_meta['_shipping_city'][0] == 'Malam Jabba'){
			$city = 'MMJ';
		}else if($order_meta['_shipping_city'][0] == 'Malikwal'){
			$city = 'MKL';
		}else if($order_meta['_shipping_city'][0] == 'Mandi bahauddin'){
			$city = 'MBD';
		}else if($order_meta['_shipping_city'][0] == 'Mandra'){
			$city = 'MND';
		}else if($order_meta['_shipping_city'][0] == 'Manekra'){
			$city = 'MNK';
		}else if($order_meta['_shipping_city'][0] == 'Manga Mandi'){
			$city = 'MMD';
		}else if($order_meta['_shipping_city'][0] == 'Mangla'){
			$city = 'MGL';
		}else if($order_meta['_shipping_city'][0] == 'Mansahra'){
			$city = 'MNA';
		}else if($order_meta['_shipping_city'][0] == 'Mardan'){
			$city = 'MDN';
		}else if($order_meta['_shipping_city'][0] == 'Maroot'){
			$city = 'MRT';
		}else if($order_meta['_shipping_city'][0] == 'Matta'){
			$city = 'MTA';
		}else if($order_meta['_shipping_city'][0] == 'Mehar'){
			$city = 'MHR';
		}else if($order_meta['_shipping_city'][0] == 'Mehrabpur'){
			$city = 'MRB';
		}else if($order_meta['_shipping_city'][0] == 'Melsi'){
			$city = 'MLS';
		}else if($order_meta['_shipping_city'][0] == 'Mian Channu'){
			$city = 'MCN';
		}else if($order_meta['_shipping_city'][0] == 'Mian wali'){
			$city = 'MWI';
		}else if($order_meta['_shipping_city'][0] == 'Miclourd Gunj'){
			$city = 'MLG';
		}else if($order_meta['_shipping_city'][0] == 'Minchanaabad'){
			$city = 'MHN';
		}else if($order_meta['_shipping_city'][0] == 'Mingora'){
			$city = 'MNG';
		}else if($order_meta['_shipping_city'][0] == 'Mirpur Azad Kashmir'){
			$city = 'QML';
		}else if($order_meta['_shipping_city'][0] == 'Mirpur Khas'){
			$city = 'MPK';
		}else if($order_meta['_shipping_city'][0] == 'Mirpur Mathelo'){
			$city = 'MPM';
		}else if($order_meta['_shipping_city'][0] == 'Mithi'){
			$city = 'MTH';
		}else if($order_meta['_shipping_city'][0] == 'Mitiari'){
			$city = 'MIT';
		}else if($order_meta['_shipping_city'][0] == 'Moro'){
			$city = 'MRO';
		}else if($order_meta['_shipping_city'][0] == 'Muhammadpur'){
			$city = 'MDP';
		}else if($order_meta['_shipping_city'][0] == 'Multan'){
			$city = 'MUX';
		}else if($order_meta['_shipping_city'][0] == 'Muridkay'){
			$city = 'MRY';
		}else if($order_meta['_shipping_city'][0] == 'Murree'){
			$city = 'REE';
		}else if($order_meta['_shipping_city'][0] == 'Muzaffar gargh'){
			$city = 'MZG';
		}else if($order_meta['_shipping_city'][0] == 'Muzaffarabad'){
			$city = 'MAK';
		}else if($order_meta['_shipping_city'][0] == 'Naran'){
			$city = 'NRN';
		}else if($order_meta['_shipping_city'][0] == 'Narowal'){
			$city = 'NRL';
		}else if($order_meta['_shipping_city'][0] == 'Nawabshah'){
			$city = 'WNS';
		}else if($order_meta['_shipping_city'][0] == 'Nooriabad'){
			$city = 'NRD';
		}else if($order_meta['_shipping_city'][0] == 'Noshero Feroze'){
			$city = 'NFZ';
		}else if($order_meta['_shipping_city'][0] == 'Notak'){
			$city = 'NTK';
		}else if($order_meta['_shipping_city'][0] == 'Noudero'){
			$city = 'NRO';
		}else if($order_meta['_shipping_city'][0] == 'Nowshera'){
			$city = 'NOW';
		}else if($order_meta['_shipping_city'][0] == 'Nowshera virka'){
			$city = 'NOV';
		}else if($order_meta['_shipping_city'][0] == 'Oghi'){
			$city = 'OGI';
		}else if($order_meta['_shipping_city'][0] == 'Okara'){
			$city = 'OKR';
		}else if($order_meta['_shipping_city'][0] == 'Pabbi'){
			$city = 'PBI';
		}else if($order_meta['_shipping_city'][0] == 'Paharpur'){
			$city = 'PHR';
		}else if($order_meta['_shipping_city'][0] == 'Paighaan'){
			$city = 'PGN';
		}else if($order_meta['_shipping_city'][0] == 'Pak Pattan Sharif'){
			$city = 'PPS';
		}else if($order_meta['_shipping_city'][0] == 'Panj Garian'){
			$city = 'PGR';
		}else if($order_meta['_shipping_city'][0] == 'Panno aqil'){
			$city = 'PNQ';
		}else if($order_meta['_shipping_city'][0] == 'Parachanar'){
			$city = 'PCR';
		}else if($order_meta['_shipping_city'][0] == 'Paroa'){
			$city = 'PRV';
		}else if($order_meta['_shipping_city'][0] == 'Pasroor'){
			$city = 'PRR';
		}else if($order_meta['_shipping_city'][0] == 'Pattoki'){
			$city = 'PTI';
		}else if($order_meta['_shipping_city'][0] == 'Peshawar'){
			$city = 'PEW';
		}else if($order_meta['_shipping_city'][0] == 'Pezo'){
			$city = 'PZO';
		}else if($order_meta['_shipping_city'][0] == 'Phalia'){
			$city = 'PLA';
		}else if($order_meta['_shipping_city'][0] == 'Pind dadan Khan'){
			$city = 'PDK';
		}else if($order_meta['_shipping_city'][0] == 'Pindi Gheb'){
			$city = 'PGB';
		}else if($order_meta['_shipping_city'][0] == 'Pindi bhattiya'){
			$city = 'PBT';
		}else if($order_meta['_shipping_city'][0] == 'Piplan'){
			$city = 'PPN';
		}else if($order_meta['_shipping_city'][0] == 'Pir Mahal'){
			$city = 'PML';
		}else if($order_meta['_shipping_city'][0] == 'Qamber Ali Khan'){
			$city = 'QAK';
		}else if($order_meta['_shipping_city'][0] == 'Qasba Gujrat'){
			$city = 'QGT';
		}else if($order_meta['_shipping_city'][0] == 'Qila Deedar Singh'){
			$city = 'QDS';
		}else if($order_meta['_shipping_city'][0] == 'Quaid abad'){
			$city = 'QBD';
		}else if($order_meta['_shipping_city'][0] == 'Quetta'){
			$city = 'UET';
		}else if($order_meta['_shipping_city'][0] == 'Rabwah'){
			$city = 'AWH';
		}else if($order_meta['_shipping_city'][0] == 'Rahim yar khan'){
			$city = 'RYK';
		}else if($order_meta['_shipping_city'][0] == 'Raiwind'){
			$city = 'RND';
		}else if($order_meta['_shipping_city'][0] == 'Rajanpur'){
			$city = 'RJP';
		}else if($order_meta['_shipping_city'][0] == 'Rangpur'){
			$city = 'RNG';
		}else if($order_meta['_shipping_city'][0] == 'Ranipur'){
			$city = 'RNP';
		}else if($order_meta['_shipping_city'][0] == 'Rashakae'){
			$city = 'RAS';
		}else if($order_meta['_shipping_city'][0] == 'Rato Dero'){
			$city = 'RTD';
		}else if($order_meta['_shipping_city'][0] == 'Rawalpindi'){
			$city = 'RWP';
		}else if($order_meta['_shipping_city'][0] == 'Rawat'){
			$city = 'RWT';
		}else if($order_meta['_shipping_city'][0] == 'Renala Khurd'){
			$city = 'RLK';
		}else if($order_meta['_shipping_city'][0] == 'Risalpur'){
			$city = 'RSP';
		}else if($order_meta['_shipping_city'][0] == 'Rohri'){
			$city = 'RHI';
		}else if($order_meta['_shipping_city'][0] == 'Rojhan'){
			$city = 'RJN';
		}else if($order_meta['_shipping_city'][0] == 'Rukni'){
			$city = 'RKN';
		}else if($order_meta['_shipping_city'][0] == 'Rustam'){
			$city = 'RUS';
		}else if($order_meta['_shipping_city'][0] == 'SHOREKOT CANTT'){
			$city = 'SKC';
		}else if($order_meta['_shipping_city'][0] == 'Sadiqabad'){
			$city = 'SQD';
		}else if($order_meta['_shipping_city'][0] == 'Sahiwal'){
			$city = 'SWL';
		}else if($order_meta['_shipping_city'][0] == 'Saidu Shareef'){
			$city = 'SDF';
		}else if($order_meta['_shipping_city'][0] == 'Sakhi Sarwar'){
			$city = 'SIS';
		}else if($order_meta['_shipping_city'][0] == 'Sambrial'){
			$city = 'SBR';
		}else if($order_meta['_shipping_city'][0] == 'Samundri'){
			$city = 'SAM';
		}else if($order_meta['_shipping_city'][0] == 'Sanghar'){
			$city = 'SAN';
		}else if($order_meta['_shipping_city'][0] == 'Sangla Hills'){
			$city = 'SHL';
		}else if($order_meta['_shipping_city'][0] == 'Sanjarpur'){
			$city = 'SJP';
		}else if($order_meta['_shipping_city'][0] == 'Sara-a Mahajar'){
			$city = 'SMH';
		}else if($order_meta['_shipping_city'][0] == 'Sara-a-Naurang'){
			$city = 'SRG';
		}else if($order_meta['_shipping_city'][0] == 'Sara-e-alamgir'){
			$city = 'SIR';
		}else if($order_meta['_shipping_city'][0] == 'Sargodha'){
			$city = 'SGD';
		}else if($order_meta['_shipping_city'][0] == 'Satiayana '){
			$city = 'STN';
		}else if($order_meta['_shipping_city'][0] == 'Sawabi'){
			$city = 'SWB';
		}else if($order_meta['_shipping_city'][0] == 'Sehwan Sharif'){
			$city = 'SHN';
		}else if($order_meta['_shipping_city'][0] == 'ShahJamal'){
			$city = 'SJL';
		}else if($order_meta['_shipping_city'][0] == 'Shahbaz Khel'){
			$city = 'SBZ';
		}else if($order_meta['_shipping_city'][0] == 'Shahdara'){
			$city = 'SHD';
		}else if($order_meta['_shipping_city'][0] == 'Shahkot'){
			$city = 'SHK';
		}else if($order_meta['_shipping_city'][0] == 'Shahpur Saddar'){
			$city = 'SPS';
		}else if($order_meta['_shipping_city'][0] == 'Shakargarh'){
			$city = 'SGR';
		}else if($order_meta['_shipping_city'][0] == 'Shehdadpur'){
			$city = 'SHP';
		}else if($order_meta['_shipping_city'][0] == 'Sheik Maltoon'){
			$city = 'SMN';
		}else if($order_meta['_shipping_city'][0] == 'Sher Garh'){
			$city = 'SHG';
		}else if($order_meta['_shipping_city'][0] == 'Shiekhopura'){
			$city = 'SPA';
		}else if($order_meta['_shipping_city'][0] == 'Shikarpur'){
			$city = 'SIP';
		}else if($order_meta['_shipping_city'][0] == 'Shinkyari'){
			$city = 'SHY';
		}else if($order_meta['_shipping_city'][0] == 'Shorkot'){
			$city = 'SQT';
		}else if($order_meta['_shipping_city'][0] == 'Shuja Abad'){
			$city = 'SHJ';
		}else if($order_meta['_shipping_city'][0] == 'Sialkot'){
			$city = 'SKT';
		}else if($order_meta['_shipping_city'][0] == 'Sillanwali'){
			$city = 'SLW';
		}else if($order_meta['_shipping_city'][0] == 'Skrand'){
			$city = 'SKN';
		}else if($order_meta['_shipping_city'][0] == 'Sohawa'){
			$city = 'SOW';
		}else if($order_meta['_shipping_city'][0] == 'Sukkur'){
			$city = 'SKZ';
		}else if($order_meta['_shipping_city'][0] == 'Sumandari'){
			$city = 'SML';
		}else if($order_meta['_shipping_city'][0] == 'Sundar'){
			$city = 'SDR';
		}else if($order_meta['_shipping_city'][0] == 'Swat'){
			$city = 'SWT';
		}else if($order_meta['_shipping_city'][0] == 'Tabbi Qaisarani'){
			$city = 'TQS';
		}else if($order_meta['_shipping_city'][0] == 'Takhatbai'){
			$city = 'TKB';
		}else if($order_meta['_shipping_city'][0] == 'Talagang'){
			$city = 'TLG';
		}else if($order_meta['_shipping_city'][0] == 'Tandiliyawala'){
			$city = 'TDW';
		}else if($order_meta['_shipping_city'][0] == 'Tando Adam'){
			$city = 'TDA';
		}else if($order_meta['_shipping_city'][0] == 'Tando Allah Yar'){
			$city = 'TDY';
		}else if($order_meta['_shipping_city'][0] == 'Tando Jam'){
			$city = 'TDJ';
		}else if($order_meta['_shipping_city'][0] == 'Tando Muhammad Khan'){
			$city = 'TMK';
		}else if($order_meta['_shipping_city'][0] == 'Tank'){
			$city = 'TAN';
		}else if($order_meta['_shipping_city'][0] == 'Tarbela'){
			$city = 'TRB';
		}else if($order_meta['_shipping_city'][0] == 'Tatlay wali'){
			$city = 'TTW';
		}else if($order_meta['_shipping_city'][0] == 'Taunsa Sharif'){
			$city = 'TNS';
		}else if($order_meta['_shipping_city'][0] == 'Taxila'){
			$city = 'TXL';
		}else if($order_meta['_shipping_city'][0] == 'Temargarh'){
			$city = 'TMG';
		}else if($order_meta['_shipping_city'][0] == 'Tha Kot'){
			$city = 'TAK';
		}else if($order_meta['_shipping_city'][0] == 'Thana'){
			$city = 'Thana';
		}else if($order_meta['_shipping_city'][0] == 'Thatta'){
			$city = 'THT';
		}else if($order_meta['_shipping_city'][0] == 'Tiba Sultanpur'){
			$city = 'TSP';
		}else if($order_meta['_shipping_city'][0] == 'Toba tek singh'){
			$city = 'TTS';
		}else if($order_meta['_shipping_city'][0] == 'Topi'){
			$city = 'TOP';
		}else if($order_meta['_shipping_city'][0] == 'Tranda Muhammad Pannah'){
			$city = 'TMP';
		}else if($order_meta['_shipping_city'][0] == 'Ubaro'){
			$city = 'UBR';
		}else if($order_meta['_shipping_city'][0] == 'Ugoke'){
			$city = 'UGI';
		}else if($order_meta['_shipping_city'][0] == 'Umer Kot'){
			$city = 'UMK';
		}else if($order_meta['_shipping_city'][0] == 'Upper Dir'){
			$city = 'UDR';
		}else if($order_meta['_shipping_city'][0] == 'Usta Muhammad'){
			$city = 'UST';
		}else if($order_meta['_shipping_city'][0] == 'Vehari'){
			$city = 'VRI';
		}else if($order_meta['_shipping_city'][0] == 'Wah cantt'){
			$city = 'WAH';
		}else if($order_meta['_shipping_city'][0] == 'Wahn Bachran'){
			$city = 'WBN';
		}else if($order_meta['_shipping_city'][0] == 'Wahowah'){
			$city = 'WOW';
		}else if($order_meta['_shipping_city'][0] == 'Wazirabad'){
			$city = 'WZD';
		}else if($order_meta['_shipping_city'][0] == 'Yazman'){
			$city = 'YZM';
		}else if($order_meta['_shipping_city'][0] == 'Zafarwal'){
			$city = 'ZFL';
		}else if($order_meta['_shipping_city'][0] == 'Zahir peer'){
			$city = 'ZFL';
		}else if($order_meta['_shipping_city'][0] == 'shangla'){
			$city = 'SGL';
		}
	}
	//get product name and no of items in this specific order
	$order = new WC_Order( $order_id );
	$items = $order->get_items();
	foreach( $items as $item) {
    	$product_name = $item['name'];
    	$quantity = $item['quantity'];
	}
	//Blue ex api code
	define('blueEx', "http://benefitx.blue-ex.com/api/post.php");
	$xml = '<?xml version="1.0" encoding="utf-8"?>
	<BenefitDocument>
		<AccessRequest>
			<DocumentType>1</DocumentType>
			<TestTransaction></TestTransaction>
			<ShipmentDetail>
				<ShipperName></ShipperName>
				<ShipperAddress></ShipperAddress>
				<ShipperContact></ShipperContact>
				<ShipperEmail></ShipperEmail>
				<ConsigneeName>'.$fullname.'</ConsigneeName>
				<ConsigneeAddress>'.$fulladdress.'</ConsigneeAddress>
				<ConsigneeContact>'.$order_meta["_billing_phone"][0].'</ConsigneeContact>
				<ConsigneeEmail>'.$order_meta["_billing_email"][0].'</ConsigneeEmail>
				<ProductDetail>'.$product_name.'</ProductDetail>
				<ProductValue>'.$order_meta["_order_total"][0].'</ProductValue>
				<CollectionRequired>Y</CollectionRequired>
				<Peices>'.$quantity.'</Peices>
				<OriginCity>LHE</OriginCity>
				<DestinationCountry>'.$countryCode.'</DestinationCountry>
				<ServiceCode>BG</ServiceCode>
				<Fragile>N</Fragile>
				<ParcelType>P</ParcelType>
				<InsuranceRequire>N</InsuranceRequire>
				<InsuranceValue></InsuranceValue>
				<DestinationCity>'.$city.'</DestinationCity>
				<Weight>1</Weight>
				<ShipperReference>9010191</ShipperReference>
				<ShipperComment></ShipperComment>
			</ShipmentDetail>
		</AccessRequest>
	</BenefitDocument>';
	$c = curl_init();
	curl_setopt($c, CURLOPT_URL, blueEx );
	curl_setopt($c, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt($c, CURLOPT_USERPWD, $blueex_username.":".$blueex_paswword);
	//Use Blue-Ex Customer Portal Username and Password.
	curl_setopt($c, CURLOPT_POST, 1 );
	curl_setopt($c, CURLOPT_POSTFIELDS, array('xml'=>$xml));
	curl_setopt($c, CURLOPT_HTTPHEADER, array('Content-Type=application/soap+xml', 'charset=utf-8'));
	$result = curl_exec ($c);
	$response = new SimpleXMLElement($result);
	$current_user = wp_get_current_user();
	$tracking_no = $response->message;
	//store value to database
	global $wpdb; 
	$table_name = $wpdb->prefix."order_tracking_blueex";
	$wpdb->insert($table_name, array(
	                                'order_id' => $order_id,
	                                'tracking_no' => $tracking_no,
	                                ),array('%s','%s'));
	//show tracking number  on thank you page
	if(get_option('eventName_blueex_called') == 'woocommerce_thankyou'){
		echo '<h2>Order Tracking Info</h2>';
		echo $tracking_no;
	}
	
}
add_action($event_on_call, 'blueex_display_order_data_processing_status', 30 );

// show tracking no on user side on order detail page.
function blueex_display_order_view_data_user_account( $order_id ){  

	
	echo '<h2>Order Tracking Info</h2>';
   
    //store value to database
    global $wpdb;
 
	$table_name = $wpdb->prefix . 'order_tracking_blueex';
	 
	$field_name = 'tracking_no';
	 
	$prepared_statement = @$wpdb->prepare( "SELECT {$field_name} FROM {$table_name} WHERE  order_id = ". $order_id);
	$values = $wpdb->get_col( $prepared_statement );
	 _e( 'Tracking No:' );
	echo $values[0];
   	//store value to database
}
add_action( 'woocommerce_view_order', 'blueex_display_order_view_data_user_account', 30 );

//Show tracking number on admin side order detail page
function blueex_display_order_data_in_admin_order_page( $order ){
    	echo '<div class="order_data_column">';
        $order_id = $order->data['id'];
        global $wpdb;
 
	$table_name = $wpdb->prefix . 'order_tracking_blueex';
	 
	$field_name = 'tracking_no';
	 
	$prepared_statement = @$wpdb->prepare( "SELECT {$field_name} FROM {$table_name} WHERE order_id = ". $order_id);
	$values = $wpdb->get_col( $prepared_statement );
	if(!empty($values)){
		echo '<h4>Tracking  Info</h4>';
            echo '<p><strong>' . __( 'Tracking No' ) . ':'.$values[0].'</strong></p>';
    }
	echo '</div>';
}
add_action('woocommerce_admin_order_data_after_shipping_address','blueex_display_order_data_in_admin_order_page' );
?>