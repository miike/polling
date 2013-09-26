<?php
App::uses('Sanitize', 'Utility');

class QuizzesController extends AppController {

	public $helpers = array('Html', 'Form', 'Text', 'Js', 'Time');
	public $components = array('Session', 'Auth');
	public $actsAs = array('Containable');

	public function index(){
		$this->set('title', 'Poll Index Page');

		//fetch currently active quizzes that users can participate in

		$quizzes = $this->Quiz->getActive();

		//modify the contents of $quizzes to add the top candidates
		foreach($quizzes as $key=>$quiz){
			$topdetails = $this->Quiz->topCandidate($quiz['Quiz']['id']);
			$quizzes[$key]['Quiz']['top'] = $topdetails[0];
			$quizzes[$key]['Quiz']['top_count'] = $topdetails[1];
		}
		$this->set('quizzes',$quizzes);


	}

	public function view($id = null){

		$this->Quiz->topCandidate(1);

		//this is the equivalent of a voting page if it is still active.
		if (!$id){
			throw new NotFoundException(__('Invalid quiz id'));
		}

		//test
		$this->recursive = 1;

		$quiz = $this->Quiz->find('first',array(
			'conditions'=>array(
				'Quiz.id' => $id
				),
			'contain'=>array(
				'Candidate' => array(
					'User'=>array(
						'fields'=>array('User.username')
					),
					'Comment'=>array(
						'User'=>array(
							'fields'=>array('User.id', 'User.username', 'User.url')
							)
						),
					'order'=>array('Candidate.order ASC')
					),
				'User' => array(
					'fields'=>'User.username'
					)
				),

			
			)
		);


		if (!$quiz){
			throw new NotFoundException(__('Failed to find quiz'));
		}

		//TODO: add authorisation in session variable
		//if the user has the correct password we store this in a session variable
		if ($quiz['Quiz']['password'] != ""){
			//this quiz requires a password
			$this->set('mode', 'password');
		}
		else{
			$this->set('mode', 'view');
		}

		//check if a vote is coming in (or a password request)

		if ($this->request->is('post')){

			if (isset($this->request->data['Vote'])){

				//do nothing (old condition code)
			}
			else if (isset($this->request->data['Password'])){
				$enterpassword = $this->request->data['Password']['password'];
				if ($enterpassword == $quiz['Quiz']['password']){
					//let them access the quiz
					$this->set('mode', 'view');
				}
				else{
					//incorrect password
					$this->Session->setFlash('Incorrect password.');
				}

			}

		}

		//modify quiz slightly to include upvotes and downvotes for each candidate
		$candidates = $quiz['Candidate'];
		foreach($candidates as $key=>$candidate){
			$down = $this->Quiz->Vote->getDownvotes($candidate['id']);
			$up = $this->Quiz->Vote->getUpvotes($candidate['id']);
			$quiz['Candidate'][$key]['down'] = $down;
			$quiz['Candidate'][$key]['up'] = $up;
		}

		//sanitise data (just in case!)
		$quiz = Sanitize::clean($quiz);
		
		$this->set('quiz',$quiz);
		$this->set('title', 'Viewing poll');
		$this->set('participants', $this->Quiz->getParticipants($quiz['Quiz']['id']));
		$this->set('active', $this->Quiz->isActive($id));


	}

	public function review($id = null){

		//restrict access to admins only

		if (!$this->Auth->user()) {
        	pr('User is not logged in');
    	}

    	//ensure that only admins can access the review page
    	if ($this->Auth->user('admin') == 1){
    		//do nothing (pass)
    	}
    	else{
    		$this->Session->setFlash(__('You are not authorised to view this page.'));
    		//redirect
    		$this->redirect(array('controller' => 'quizzes', 'action' => 'index'));
    	}

		//for reviewing the results of a quiz
		if (!$id){
			throw new NotFoundException(__('Invalid quiz id'));
		}
		$quiz = $this->Quiz->findById($id);
		if (!$quiz){
			throw new NotFoundException(__('Failed to find quiz'));
		}

		//otherwise we are good to go.

		//pr($this->Quiz->Candidate->getVotes(1));

		$this->set('title', 'Review for ' . $quiz['Quiz']['name']);

		foreach($quiz['Candidate'] as $index=>$candidate){
			$votecounts = $this->Quiz->Candidate->getVotes($candidate['id']);
			$quiz['Candidate'][$index]['upvotes'] = $votecounts[0]; //get upvote count
			$quiz['Candidate'][$index]['downvotes'] = $votecounts[1]; //downvotes
			
		}

		//get data to show votes over time
		$timedata = $this->Quiz->getVotesOverTime($id);

		$this->set('timedata', $timedata);

		$this->set('questions', $quiz['Candidate']);
		$this->set('votes', $quiz['Vote']);

		//get the votes for each candidate

	
	}

	public function suggest(){
		$this->set('title', 'Suggest a new molecule');

		//ensure it only finds actives
		$quizzes = $this->Quiz->getActiveList();
		$this->set(compact('quizzes'));

		if ($this->request->is('post')){
			if (isset($this->request->data['Candidate'])){


				$data = $this->request->data['Candidate'];
				pr($data);
				$data['suggested'] = 1;
				$data['user_id'] = $this->Auth->user('id');

				//save the candidate to the database

				$newcandidate = $this->Quiz->Candidate;
				
				$newcandidate->create();

				//save the vote
				$newcandidate->save($data, array('fieldList' => array('Candidate.name', 'Candidate.image_url', 'Candidate.description', 'Candidate.url', 'Candidate.quiz_id', 'Candidate.inchi', 'Candidate.smiles'))); //add fieldList here for security

				$this->Session->setFlash('Thankyou for suggesting a candidate.');
				$this->redirect(array('controller'=>'quizzes', 'action'=>'view', $data['quiz_id']));
			}
		}


	}

	public function create(){
		//function to create a new quiz
		//ensure that only admins can access the review page
    	if ($this->Auth->user('admin') == 1){
    		//do nothing (pass)
    	}
    	else{
    		$this->Session->setFlash(__('You are not authorised to view this page.'));
    		//redirect
    		$this->redirect(array('controller' => 'quizzes', 'action' => 'index'));
    	}

		$this->set('title', 'Create a new quiz');

		//also need to create a new quiz object in here as well.



		if ($this->request->is('post')){
			//attempt to save the new candidates somehow

			//we need to ensure that data contains a user
			$this->request->data['Quiz']['user_id'] = $this->Auth->user('id');
			//pr($this->request->data);
			$this->Quiz->saveAll($this->request->data);
			
			//redirect to the quiz index page
			$this->Session->setFlash(__('The new quiz was added successfully.'));
			$this->redirect(array('controller' => 'quizzes', 'action' => 'index'));
		}


	}

	public function edit($id = null){

		//permissions check
		if ($this->Auth->user('admin') == 1){
    		//do nothing (pass)
    	}
    	else{
    		$this->Session->setFlash(__('You are not authorised to view this page.'));
    		//redirect
    		$this->redirect(array('controller' => 'quizzes', 'action' => 'index'));
    	}

    	//for updating changes in a quiz
		if (!$id){
			throw new NotFoundException(__('Invalid quiz id'));
		}
		$quiz = $this->Quiz->findById($id);
		if (!$quiz){
			throw new NotFoundException(__('Failed to find quiz'));
		}


		if ($this->request->is('post') || $this->request->is('put')){
			//attempt to save the data

			//pr($this->request->data);
			$this->Quiz->id = $id;

			$quiz_save = $this->Quiz->save($this->request->data);

			//attempt to save the candidates
			$candidates = $this->request->data['Candidate'];
			foreach($candidates as $candidate){
				$this->Quiz->Candidate->id = $candidate['id'];
				$this->Quiz->Candidate->save($candidate);
			}

			if ($quiz_save){
				//pr($this->request->data);
				$this->Session->setFlash(__('Quiz changes have saved successfully.'));
			}
			else{
				$this->Session->setFlash(__('Quiz changes failed to save'));
			}
		}
		else{
			//attempt to render the view
			$this->request->data = $quiz;
		}
	}

	public function vote($id){
		//check that candidate exists
		if ($this->request->is('post') || $this->request->is('put')){
			//then find out what kind of vote it is and save it
			$data = $this->request->data;
			//pr($data);


			//check that the candidate and quiz exist
			if (!$id){
				die("3"); //no id specified
			}
			$quiz = $this->Quiz->findById($id);
			if (!$quiz){
				die("4"); //quiz does not exist
			}

			
			$votedetails = array();
			$votedetails['quiz_id'] = $id;

			//check if the candidate exists
			$candidate = $this->Quiz->Candidate->findById($data['candidate_id']);
			if (!$candidate){
				die("5"); //candidate does not exist
			}


			$votedetails['candidate_id'] = $data['candidate_id'];

			//add in vote author details, check that the user is logged in
			$votedetails['ip'] = $_SERVER['REMOTE_ADDR'];

			if (!$this->Auth->user('id')){
				die("6"); //user is not logged in
			}
			$votedetails['user_id'] = $this->Auth->user('id');


			//determine vote type
			if ($data['vote_type'] == 0){
				$votedetails['vote_type'] = '0';
			}
			else{
				$votedetails['vote_type'] = '1';
			}

			if ($this->Quiz->Candidate->allowCandidateVote($votedetails['candidate_id'])){
				$newvote = $this->Quiz->Vote;
				$newvote->create();
				$newvote->save($votedetails, array('fieldList' => array('quiz_id', 'candidate_id', 'user_id', 'vote_type')));
				die("1"); //everything went okay
			}
			else{
				die("7"); //you've already voted for this candidate
			}
		}
		else{
			//nothing to see here
		}
	}




}

?>