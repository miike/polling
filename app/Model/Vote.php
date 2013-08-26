<?php

class Vote extends AppModel{
	public $name = 'Vote';
	public $belongsTo = array('Candidate' =>array(
		'counterCache'=>true),
		'Quiz');

	function getUpvotes($candidateid){
		$count = $this->find('count', array(
				'conditions' => array(
					'Vote.candidate_id' => $candidateid,
					'Vote.vote_type' => '0'
					)
			)
		);

		return $count;
	}

	function getDownvotes($candidateid){
		$count = $this->find('count', array(
				'conditions' => array(
					'Vote.candidate_id' => $candidateid,
					'Vote.vote_type' => '1'
					)
			)
		);

		return $count;
	}

	function calculateScore($candidateid){
		return $this->getUpvotes($candidateid) - $this->getDownvotes($candidateid);
	}




}

?>