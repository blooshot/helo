<?php

/*include("./class/first_class.php");

$checker = new first_oops();

$checker->select
*/

$arr1 = array(); 
ob_start();
for($a=1; $a <= 20; $a++){
	$arr1[$a] = rand(1,50);
}

echo"ladeya";

$arr = ob_get_contents();

ob_end_clean();
//unset($arr1[0]);

//$arr = $arr1;
//$arr = array(3,6,4,9,2,1,8);

$len = count($arr);

echo "\n\n\n First array";
print_r($arr);

echo "\n\n\n";

for($i=0; $i < $len; $i++){

	$temp = $arr[$i];
	echo "Temp for {$i} time ".$temp."\n";

	$j = $i-1;
	echo "j-> ".$j."\n";

	while($j >= 0 && $arr[$j] > $temp){

		$arr[$j+1] = $arr[$j];

		echo "while arr[j+1]-> ".$arr[$j+1]."\n";

		$j--;

	}

	$arr[$j+1] = $temp; 

	echo "for arr[j+1]-> ".$arr[$j+1]."\n";

}

echo "\n\n\n Final array";
print_r($arr);


---------------------------------------------------

function variable_disK($price_arr,$product_id){

    $sums = array();

    $sell_price = $price_arr['sale_price'];
   
    $reg_price = $price_arr['regular_price'];

    foreach (array_keys($sell_price + $reg_price) as $key) {
    
         $regular = $reg_price[$key];
    
         $sale    = $sell_price[$key];
        
         $sums[$key] = round(100 - ($sale / $regular * 100) );
    
    }
    
    $percentage = max( array_values($sums) );

    //techo "percentage-> ".$percentage. "proid-> ".$product_id;

    if($percentage != 0 && $percentage != 100){
           
       $check = update_post_meta( $product_id, '_discount_amount', $percentage, $percentage );
        
        //echo "type-> ".gettype($check)." -> ".$check; 
    }
}//end function variable_disK

function simple_disK($_product){

	$regular = $_product->get_regular_price();

	$sale = $_product->get_sale_price();
    
    $discount = round( 100 - ( $sale / $regular * 100) );
    
    //echo "Regular Price {$regular} sale price {$sale} discount price {$discount} and product Id = ".$_product->get_id();

    if($discount != 0 && $discount != 100){

	    update_post_meta( $_product->get_id(), '_discount_amount', $discount, $discount );
    
    }


}//END simple_disK()



add_action('woocommerce_product_bulk_edit_save', 'my_bulk_edit_option');

function my_bulk_edit_option(){
    
    $id_arr = $_GET['post']; // getting ID's
     
    for($i=0; $i < count($id_arr); $i++ ){
        
       $_product = wc_get_product( $id_arr[$i] ); //getting product by id's 
       
       if($_product->get_type() == "variable"){
           
            $price_arr  = $_product->get_variation_prices();
            
            variable_disK($price_arr,$_product->get_id());

       }//end variable type checking

       
       if($_product->get_type() == "simple"){
           
            simple_disK($_product);
            
       }//end simple type checking
       
    }//end for loop    
    
}//end my_bulk_edit_option





// proccessing amount
add_action('woocommerce_process_product_meta', 'woo_calc_my_discount');
function woo_calc_my_discount( $product_id ) {

    $_product = wc_get_product( $product_id );
    
    if($_product->get_type() == "variable"){
         
       $price_arr  = $_product->get_variation_prices();//return price array for sale and regular
       
       variable_disK($price_arr,$_product->get_id());
       
     }//end if variable check  
    
    if($_product->get_type() == "simple"){
        
   		simple_disK($_product);
    
    }//end if simple

}//end woo_calc_my_discount

//saving meta data on quick save/edit
add_action('woocommerce_product_quick_edit_save', 'sv_woo_calc_my_discount_quickedit');
function sv_woo_calc_my_discount_quickedit( $post ) {

    $_product = wc_get_product( $post );
    
    //echo "process_quick_edit_save :-> <pre>";
    
 if($_product->get_type() == "variable"){
     
   $price_arr  = $_product->get_variation_prices();//return price array for sale and regular
   
   variable_disK($price_arr,$_product->get_id());
    
 }//end if variable check    
    

    if($_product->get_type() == "simple"){
    
    	simple_disK($_product);

    }//end if 

}// end sv_woo_calc_my_discount_quickedit