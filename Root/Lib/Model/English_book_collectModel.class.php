<?php
class English_book_collectModel extends RelationModel{
	protected $_link = array(
		'English_book' => array(
			'mapping_type' => BELONGS_TO,
			'foreign_key' => 'bid',
			//'as_fields' => 'title:btitle',
			'mapping_name' => 'book',
		),
	);
}
?>