<?php
App::import('Sanitize');

class UsersController extends AppController{
	public $helpers = array('Html', 'Form', 'Js', 'Time');
	public $components = array('Session', 'Auth', 'Security');
	

	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('add');
	}

	public function login() {
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				$this->redirect($this->Auth->redirect());
			} else {
				$this->Session->setFlash(__('Invalid username or password, try again'));
			}
		}
	}

	public function index(){
		//nothing to see here
	}

	public function add(){
		if ($this->request->is('post')){
			$this->User->create();

			$exists = $this->User->userExist($this->request->data['User']['username']);

			if ($exists){
				$this->Session->setFlash(__('Please choose another username.'));
				$this->redirect(array('controller'=>'users', 'action'=>'add'));
			}
			else{
				if ($this->User->save($this->request->data, array('fieldList'=>array('User'=>array('username', 'password', 'name', 'email'))))){ //this line should be shorted/cleaner
					$this->Session->setFlash(__('The user has been created.'));
					$this->Auth->login();
					$this->redirect(array('controller'=>'quizzes', 'action'=>'index'));
				}
				else{
					$this->Session->setFlash(__('The user could not be created.'));
				}
			}

		}
	}

	public function logout(){
		$this->redirect($this->Auth->logout());
	}

	public function view($id = null){
		//TODO: a function to output the public profile of a user
		$this->set('about', 'nothing to see here.');
	}

	public function edit(){
		//allow a user to edit their own profile by adding or modifying their URL
		$user_id = $this->Auth->user('id');

		$user_details = $this->User->findById($user_id);

		if ($this->request->is('post') || $this->request->is('put')){
			//then attempt to save the new details
			//pr($this->request->data);
			$this->User->id = $user_id;
			if ($this->User->save($this->request->data, array('fieldList'=>array('User'=>array('url', 'name'))))){
				$this->Session->setFlash(__('Your profile has been updated'));
				$this->redirect(array('controller' => 'quizzes', 'action' => 'index'));
			}
			else{
				$this->Session->setFlash(__('Failed to update profile'));
				$this->redirect(array('controller' => 'quizzes', 'action' => 'index'));
			}
		}
		else{
			//render the form
			$this->request->data = $user_details;
			if ($this->Auth->user('admin') == 1){
    			$accounttype = 'administrator';
    		}
    		else{
    			$accounttype = 'standard';
    		}
			$this->set('accounttype', $accounttype);
			$this->set('voteaction', $this->User->getUserVotes($this->Auth->user('id')));

			//get some history for the user as well

		}
	}
}

?>