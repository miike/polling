<?php

class Candidate extends AppModel{
	public $actsAs = array('Containable');
	public $name = 'Candidate';
	public $hasMany = array('Vote', 'Comment');
	public $belongsTo = array('Quiz', 'User');
	


	function getVotes($id){
		$candidate = $this->findById($id);

		if (!$candidate){ //check if the candidate exists
			return 0;
		}
		else{
			//count the number of votes
			$upvotes = $this->Vote->find('count', 
				array(
					'conditions'=>array(
						'candidate_id'=>$id,
						'vote_type' => 0)
					)
			);

			$downvotes = $this->Vote->find('count', array('conditions' => array(
				'candidate_id' => $id,
				'vote_type' => 1)
			)
			);
			return array($upvotes, $downvotes);
		}
	}

	function getVotesOverTime($quiz_id){
		//$quiz = $this->Quiz->findById()
		//SELECT * , COUNT( * ) 
	//FROM  `votes` 
//WHERE 1 
//GROUP BY YEAR(  `created` ) , MONTH(  `created` ) , DAY(  `created` ) 
//		pass
	}

	function userVoted($id){
		//returns true if user has voted on candidate, false otherwise
		$votes = $this->Vote->find('count', array('conditions'=>
			array(
				'Vote.user_id' => CakeSession::read("Auth.User.id"),
				'Vote.candidate_id' => $id
				)
			)
		);

		if ($votes > 0){
			return True;
		}
		else{
			return False;
		}

	}

	function allowCandidateVote($id){
		//checks 2 things, if the quiz is active and if the user is allowed to vote
		$this->recursive = -1;
		$quiz_id = $this->find('first', array(
			'conditions'=>array(
				'Candidate.id' => $id,
				),
			'fields'=>array(
				'Candidate.quiz_id'
				),
			)
		);
		$quiz_active = $this->Quiz->isActive($quiz_id['Candidate']['quiz_id']);
		if ($quiz_active == true){
			$quiz_active = 1;
		}
		else{
			$quiz_active = 0;
		}

		$votedalready = $this->userVoted($id);

		if ($votedalready === false and $quiz_active === 1){
			//if the user hasn't already voted AND the quiz is active then allow vote
			return true;
		}
		else{
			return false;
		}
	}
}

?>