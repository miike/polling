<?php

class User extends AppModel{
	public $name = 'User';
	public $hasMany = array('Candidate', 'Quiz', 'Vote'); //added vote


	public $validate = array(
		'username' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'A username is required'
			)
		),
		'password' => array(
			'required' => array(
				'rule' => array('minLength', '6'),
				'message' => 'A password is required (minimum 6 characters)'
			)
		),
		'email' => 'email'
	);

	public function beforeSave($options = array()){
		if (isset($this->data[$this->alias]['password'])){
			$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
		}
		return true;
	}

	function getUsername($user_id){
		$username = $this->find('first', array(
			'conditions'=>array(
				'User.id' => $user_id
				),
			'fields'=>array(
				'User.username'
				)
			));

		return $username;

	}

	function userExist($username){
		//checks if a user with $username already exists
		$count = $this->find('count', array(
				'conditions' => array(
					'User.username' => $username
					)
			)
		);

		if ($count > 0){
			return true;
		}
		else{
			return false;
		}
	}

	function getUserVotes($user_id){

		$activity = $this->Vote->find('all', array(
				'conditions' => array(
					'Vote.user_id' => $user_id
					),
				'order' => array('Vote.created DESC'),
				'fields' => array(
					'Vote.vote_type', 'Vote.created', 'Candidate.name', 'Quiz.name', 'Quiz.id'
					)
			)
		);

		return $activity;
	}






}

?>