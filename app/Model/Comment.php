<?php

class Comment extends AppModel{
	public $name = 'Comment';
	public $actsAs = array('Containable');
	public $belongsTo = array('User', 'Candidate');

}

?>