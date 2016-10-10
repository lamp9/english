<?php
class English_bookModel extends RelationModel{
	protected $_link = array(
		'English_sort' => array(
			'mapping_type' => BELONGS_TO,
			'foreign_key' => 'sid',
			'as_fields' => 'title:stitle',
		),
	);
}
?>