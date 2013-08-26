<?php

class CommentsController extends AppController{
	public $components = array('Session', 'Auth');
	public $actsAs = array('Containable');

	public function index(){
		//nothing to see here
	}

	public function submit(){
		//called when a user attempts to submit a comment, tries to save that comment
		if ($this->request->is('post')){
			$data = $this->request->data;

			$commobj = array('Comment'=>array());
			$commobj['Comment']['user_id'] = $this->Auth->user('id');
			$commobj['Comment']['candidate_id'] = $data['candidate_id'];
			$commobj['Comment']['comment'] = $data['comment_text'];

			$newcomment = $this->Comment;

			if ($newcomment->save($commobj, array('fieldsList'=>array('user_id', 'candidate_id', 'comment')))){
				die("1");
			}
			else{
				die("0"); //the comment didnt save
			}

			
		}
	}

	public function delete(){
		//removes a comment
		//make sure that the user owns this comment before removing it
		if ($this->request->is('post')){
			//check the comment exists
			$data = $this->request->data;
			$commid = $data['comment_id'];
			$comment = $this->Comment->find('first', 
				array('conditions' => array(
						'Comment.id' => $commid
					),
				'fields' => array(
						'Comment.id', 'Comment.user_id'
					)
				)
			);


			//check that the user owns the comment

			if (isset($comment)){
				$owner = $comment['Comment']['user_id'];
				if ($owner == $this->Auth->user('id')){
					//then delete the comment
					$comment_id = $comment['Comment']['id'];
					$this->Comment->delete($comment_id);
					die("1");
				}
				else{
					//user does not own this comment
					die("-1");
				}
			}
			else{
				die("-1");
			}


		}
		else{
			die("-1");
		}

	}
}

?>