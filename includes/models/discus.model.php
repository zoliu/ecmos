<?php



class discusModel extends BaseModel

{

    var $table  = 'discus';

    var $prikey = 'id';

    var $_name  = 'discus';

	var $_relation  = array(

	   'belong_to_order'  => array(

            'type'          => BELONGS_TO,

            'reverse'       => 'has_discus',

            'model'         => 'order',

			'foreign_key'   => 'order_id',

        ),

	);
     function get_discus_status(){
        return $discus_status = array(
            '1' => Lang::get('wait_store_back'),
            '2' => Lang::get('wait_admin_back'),
            '3' => Lang::get('had_admin_pass'),
            '4' => Lang::get('had_admin_money'),
        );
     }

}

?>