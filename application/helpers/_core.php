<?php





# function for debugging data
if ( ! function_exists('debug')) {
	// 
	// @ data
	// 
	function debug( $data ) {
		echo '<pre style="color:white; background-color:rgb(20,20,20); padding:10px 20px; border-top:2px dashed orange; border-bottom:2px dashed orange;">';
		print_r($data);
		echo '</pre>';
	}
}
if ( ! function_exists('d')) {
	// 
	// @ data
	// 
	function d( $data ) {
		echo '<pre style="color:white; background-color:rgb(20,20,20); padding:10px 20px; border-top:2px dashed orange; border-bottom:2px dashed orange;">';
		print_r($data);
		echo '</pre>';
	}
}



# function for debugging data and stop
if ( ! function_exists('ddebug')) {
	// 
	// @ data
	// 
	function ddebug( $data ) {
		echo '<pre style="overflow:auto; color:white; background-color:rgb(20,20,20); padding:10px 20px; border-left:4px dashed orange; border-right:4px dashed orange;">';
		print_r($data);
		echo '</pre>';
		die;
	}
}
if ( ! function_exists('dd')) {
	// 
	// @ data
	// 
	function dd( $data ) {
		echo '<pre style="overflow:auto; color:white; background-color:rgb(20,20,20); padding:10px 20px; border-left:4px dashed orange; border-right:4px dashed orange;">';
		print_r($data);
		echo '</pre>';
		die;
	}
}






# function for debugging data and stop
if ( ! function_exists('images')) {
	// 
	// @ data
	// 
	function images( $image='', $state='empty' ) {
		if (empty($image)) {
			return base_url('resource/images/no-image.png');
		}else {
			return base_url( 'storage/'.$image );
		}
	}
}

