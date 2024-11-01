<?php namespace Design_Approval_System;
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class Payments_List_Table {
	public function __construct() {
		//Filter for DAS Payment Columns
        add_filter('manage_das_payments_posts_columns', array($this,'das_payments_table_headers'));
        //Add Data to each Column
        add_action( 'manage_das_payments_posts_custom_column', array($this,'das_payments_table_content'), 10, 2 );

    }
    //**************************************************
	//Create Columns
	//**************************************************
	public function das_payments_table_headers($columns) {
			//Unset Default Columns
			unset(
				$columns['title'],
				$columns['date']
			);
		    $columns['payment_id']  	= 'Payment ID';
		    $columns['email'] 			= 'Email';
		    $columns['details']  		= 'Details';
		    $columns['total']  			= 'Total';
		    //$columns['blank']  			= '';
		    $columns['date']  			= 'Date';
			$columns['name'] 	        = 'Name';
		    $columns['status']    		= 'Status';
		    $columns['download_link']   = 'Download Link';
		        
	    return $columns;
	}
	//**************************************************
	//Add Data To Each Columns
	//**************************************************
	public function das_payments_table_content($column_name,$post_id) {
		
		$payment_class = new DAS_Payments();
		
		$client_info = $payment_class->get_payment_client_info($post_id);
		
		switch ($column_name) {
			case 'payment_id':
				$data = '<a href="'.get_edit_post_link($post_id) .'">#'.$post_id.'</a>';
					break;
			case 'email':
				$data = isset($client_info['email']) && !empty($client_info['email']) ? $client_info['email'] : '';
					break;
			case 'name':
				$data = isset($client_info['name']) && !empty($client_info['name']) ? $client_info['name'] : '';
					break;
			case 'details':
				$data = '<a href="'.get_edit_post_link($post_id) .'">View Payment Details</a>';
					break;
			case 'total':
				$price = get_post_meta($post_id, 'das_payment_price', true);
				$data = !empty($price) ? $price : '0.00';
					break;
			//case 'blank':
				//$data = '';
					//break;
			case 'date':
				$data = '';
					break;
			
			case 'status':
				$status = get_post_meta($post_id, 'das_payment_status', true);
				$data = !empty($status) ? $status : 'Pending';
					break;
			case 'download_link':
					 // Our Custom DAS Payments Complete Check then get download link from original product meta.
							$das_payment_id = get_post_meta($post_id, 'das_payment_id_ForProjectBoard', true);
							$custom_download_link = get_post_meta($das_payment_id, 'custom_download_link', true);
							$das_payment_status = get_post_meta( $post_id, 'das_payment_status', true);
							if($das_payment_status == 'complete' && $custom_download_link !== ''){
							$data = '<a href="'.$custom_download_link.'">'. __('Download', 'design-approval-system') .'</a>';
							}
							else {
								$data = _e('N/A', 'design-approval-system');
							}
			
					break;
		}	
		echo $data;	
	}
}
?>