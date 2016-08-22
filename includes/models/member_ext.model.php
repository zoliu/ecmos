<?php

class Member_extModel extends BaseModel
{
    public $table  = 'member_ext';
    public $prikey = 'user_id';
    public $_name  = 'member_ext';

    public $_relation = array(
        'belongs_to_user'         => array(
            'model'       => 'member',
            'type'        => BELONGS_TO,
            'foreign_key' => 'user_id',
            'reverse'     => 'has_member_ext',
        ),
        'belongs_to_member_level' => array(
            'model'       => 'member_grade',
            'type'        => BELONGS_TO,
            'foreign_key' => 'grade_id',
            'reverse'     => 'has_member_level_ext',
        ),

    );
}
