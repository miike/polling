<div class="row">
	<div class="large-12 large-centered columns hero">

	<?php

		if ($admin == '1'){
			echo $this->Html->link('Edit', array('controller' => 'quizzes', 'action' => 'edit', $quiz['Quiz']['id']), array('class' => 'button small right'));
			echo $this->Html->link('Review', array('controller' => 'quizzes', 'action' => 'review', $quiz['Quiz']['id']), array('class' => 'button small right success'));
		}
		//description of the quiz
		echo "<h2>" . $quiz['Quiz']['name'] . "</h2>";
		if ($active == false){
			echo "<h3 class='text-error'>This quiz is not accepting new votes</h3>";
		}

		//add some details about the quiz here
		echo "<p><span class='smalldetail'>This quiz closes in " . $this->Time->timeAgoInWords($quiz['Quiz']['finish_date']) . "</span>";
		echo "</br>";

		echo "<span class='smalldetail'>" . $participants . " users have participated in this poll</span></p>";



		
		echo "<p>" . $quiz['Quiz']['description'] . "</p>";



		
		echo "</div>"; //end head row div
		echo "</div>"; //end row div


		//attempt to render the questions

		if ($mode == 'password'){
			echo "This quiz requires a password.";
			echo $this->Form->create('Password');
			echo $this->Form->input('password');
			echo $this->Form->submit();
			echo $this->Form->end();
		}
		else if ($mode == 'view'){
			$candidates = $quiz['Candidate'];

			//create the form
			foreach($candidates as $candidate){
				//create a form for each candidate, not sure this is necessarily the best way

				echo "<div class='row'>";
				echo "<div class='large-12 columns panel'>"; //start entire candidate part

				//wrap the entire candidate in a panel


				echo "<div class='large-7 columns'>"; //start textonly part of candidate
				echo "<h3>" . $candidate['name'] . "</h3>";


				//if the molecule was suggested then show this next to the name
				if ($candidate['suggested'] == 1){
					echo " (suggested by " . $candidate['User']['username'] . ")";

				}




				echo "<table class='table'>";
				echo "<tr><td>Description</td><td>" . $candidate['description'] . "</td></tr>";
				echo "<tr><td>InChI</td><td>" . $candidate['inchi'] . "</td></tr>";
				echo "<tr><td>SMILES</td><td>" . $candidate['smiles'] . "</td></tr>";
				echo "</table>";

				
				echo "<label>Comment</label>";

				echo "<textarea class='comment" . $candidate['id'] . "' placeholder='Make a comment'></textarea>";

				echo "<ul class='button-group right'>";
				echo "<li><a href='' class='button tiny submitcomment' data-cid='" . $candidate['id'] . "'>Submit</a></li>";
				echo "</ul>";
				echo "<ul class='button-group'>";
				echo "<li><a href='' class='button success tiny upvotebutton' data-cid='" . $candidate['id'] . "' data-qid='" . $quiz['Quiz']['id'] . "'><i class='foundicon-up-arrow'></i><span data-badgeid='" . $candidate['id'] .  "class='score label'>" . $candidate['up'] . "</span></a></li>";

				echo "<li><a href='' class='button alert tiny downvotebutton' data-cid='" . $candidate['id'] . "' data-qid='" . $quiz['Quiz']['id'] . "'><i class='foundicon-down-arrow'></i><span data-badgeid='" . $candidate['id'] .  "' class='score'>" . $candidate['down'] . "</span></a></li>";
				echo "</ul>";
				
				echo "<a href='' class='togglecomments'>Toggle discussion</a>";

				echo "<div class='commentholder commentbox" . $candidate['id'] . "'>";
				foreach($candidate['Comment'] as $comment){
					echo "<div class='comment'>";
					if ($comment['User']['id'] == $user_id){
						echo "<i class='foundicon-remove delcomment right' data-cid='" . $comment['id'] . "''></i>";
					}

					if (isset($comment['User']['url'])){
						//ensure the user URL is sanitised in case of XSS/attempts to mess up html
						$cusername = $this->Html->link($comment['User']['username'], $comment['User']['url']);
					}
					else{
						$cusername = $comment['User']['username'];
					}
					echo "<span class='postedby'>" . $cusername . "</span> <span class='ago'>" . $this->Time->timeAgoInWords($comment['created']) . "</span></h6>";
					echo "<p><span class='comtext'>" . $comment['comment'] . "</span></p>";
					

					echo "</div>";
				}
				echo "</div>"; //end commentholder div

				echo "</div>"; //end text only (7) columns


				echo "<div class='large-5 columns clearimage'>"; //start image panel
				if ($candidate['image_url'] != ""){
					echo $this->Html->image($candidate['image_url'], array('alt' => $candidate['description'], 'class' => 'candidateimage'));
				}
				echo "</div>"; //end image panel (5)

				echo "</div>"; //end candidate box (12)
				echo "</div>"; //end candidate row div

			}
		}

		

	?>
	</div>
	<?php //echo $this->Html->link('Suggest an alternate candidate', array('controller' => 'quizzes', 'action' => 'suggest')); ?>
</div>

<script type="text/javascript">
mixpanel.track('View quiz',
	{
		'Quiz id':<?php echo $quiz['Quiz']['id']; ?>
	}
);
</script>