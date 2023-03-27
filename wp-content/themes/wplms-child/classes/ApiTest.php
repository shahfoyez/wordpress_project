<?php
	$filepath= realpath(dirname(__FILE__));
	// include_once ($filepath.'/Format.php');
?>
<?php
class ApiTest {
     public function insert(){
        // dd("jkhdfgv");
        // define the data to be inserted
        // $name = $_POST['name'];
        // $phone = $_POST['phone'];
        // $address = $_POST['address'];
        global $wpdb;

        $table_name = 'my_post';

        $name = "Shah Foyez";
        $phone = "78465863";
        $address = "hjdyj";

        // prepare the data to be sent via API
        $data = array(
            'name' => $name,
            'phone' => $phone,
            'address' => $address
        );
         
        // insert the data into the database
        $result = $wpdb->insert($table_name, $data);
        // dd($result);
        if( $result ){
            // send the data to Website B's API endpoint
            $response = wp_remote_post( 'http://localhost/fresh/wp-json/foy-post/data', array(
                'method' => 'POST',
                'body' => $data
            ) );
            dd( $response);
            // check if the request was successful
            if ( is_wp_error( $response ) ) {
                $error_message = $response->get_error_message();
                echo "Something went wrong: $error_message";
            } else {
                $response_body = json_decode( wp_remote_retrieve_body( $response ) );
                if ( $response_body->status == 'success' ) {
                    echo "Data inserted(WEB B) successfully!";
                } else {
                    echo "Something went wrong: $response_body->message";
                }
            }
        }else{
            echo "Error";
        }  
    }
}
?>