<?php

class Quiz extends AppModel{
	public $hasMany = array('Candidate', 'Vote');
	public $name = 'Quiz';
	public $actsAs = array('Containable');
	public $belongsTo = array('User');

	public $validate = array(
		'name' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'A quiz name is required'
			)
		)
	);


	function getVotes($id){
		//produce a list of questions and the number of votes each has recieved

		$quiz = $this->findById($id);
		foreach($quiz['Candidate'] as $candidate){
			pr($candidate['name']);
			pr($candidate);
		}
	}

	function userVoted($quiz_id, $user_id, $candidate_id){
		//check if a user has voted in a quiz
		$votecount = $this->Vote->find('count', array('conditions' => array('Vote.quiz_id' => $quiz_id, 'Vote.user_id' => $user_id, 'Vote.candidate_id' => $candidate_id)));
		if ($votecount > 0){
			return 1;
		}
		else{
			return 0;
		}
	}

	function getActive(){
		//gets all quizzes that are available
		//TODO: ensure it only gets quizzes within a date range

		$date = date("Y-m-d H:i:s");
		$conditions = array(
			'and'=> array(
				array('Quiz.start_date <= ' => $date,
					'Quiz.finish_date >= ' => $date
				))
		);


		$active = $this->find('all', array('conditions'=>$conditions));

		return $active;
	}

	function isActive($id){
		//determines if a quiz is active or not
		$date = date("Y-m-d H:i:s");
		$conditions = array(
			'and'=> array(
				array('Quiz.start_date <= ' => $date,
					'Quiz.finish_date >= ' => $date,
					'Quiz.id' => $id
				))
		);


		$active = $this->find('count', array('conditions'=>$conditions));
		
		if ($active > 0){
			return true;
		}
		else{
			return false;
		}

	}

	function topCandidate($id){
		//calculates the forerunning candidate in a quiz
		$this->recursive = -1;
		$candidates = $this->Candidate->find('list', array(
				'conditions' => array(
					'Candidate.quiz_id' => $id,
					),
				'fields' => array(
					'Candidate.id', 'Candidate.name'
					)
			)
		);

		$topcandidate_name = '';
		$topscore = -999;
		foreach($candidates as $key=>$name){
			$score = $this->Vote->calculateScore($key);
			if ($score > $topscore){
				$topscore = $score;
				$topcandidate_name = $name;
			}
		}

		return array($topcandidate_name, $topscore);
	}

	function getVotesOverTime($id){
		//returns the number of votes per day, for a given quiz
		$this->recursive = -1;
		$d = $this->Vote->find('all',
			array(
				'conditions' => array(
						'Vote.quiz_id' => $id
					),
				'group' => array(
						'DAY(Vote.created)', 'MONTH(Vote.created)', 'YEAR(Vote.created)'
					),
				'fields' => array(
						'COUNT(*) as vote_count', 'DATE_FORMAT(Vote.created, "%d-%m-%Y") AS fdate'
					),
				'order' => array(
						'Vote.created ASC'
					)

				)
		);

		//try and format it into a list of sorts
		$data = array();
		foreach($d as $bit){
			$data[$bit[0]['fdate']] = $bit[0]['vote_count'];
		}

		return $data;


	}

	function getActiveList(){
		//returns a list of active quizzes for the select box
		$date = date("Y-m-d H:i:s");
		$conditions = array(
			'and'=> array(
				array('Quiz.start_date <= ' => $date,
					'Quiz.finish_date >= ' => $date
				))
		);


		$active = $this->find('list', array('conditions'=>$conditions));
		return $active;
	}

	function getParticipants($quiz_id){
		//return the number of users who have participated in a quiz (by voting)
		$count = $this->Vote->find('all',
			array('conditions' => array(
				'Vote.quiz_id' => $quiz_id,
				),
			'fields' => array('DISTINCT Vote.user_id')
			)
		);

		return sizeof($count);
	}
}

?>