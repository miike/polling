<div class="row">
	<div class="large-8 large-centered columns panel">
		<h2>Edit your profile</h2>

<?php
echo "<p>";
if ($accounttype == 'administrator'){
	echo "You currently have an <b>" . $accounttype . "</b> account.";
}
else{
	echo "You currently have a <b>" . $accounttype . "</b> account.";
}
echo "</p>";

echo $this->Form->create('User');
echo $this->Form->input('name');
echo $this->Form->input('url', array('label'=>'URL to online profile (Twitter, G+)'));
$options = array('label' => 'Update', 'class' => 'button');
echo $this->Form->end($options);
?>

	</div>

	<div class="large-8 large-centered columns panel">
		Your activity
		<?php
			echo "<table>";
			foreach($voteaction as $vote){
				echo "<tr>";
				if ($vote['Vote']['vote_type'] == 1){
					echo '<td><span class="label date">  ' . $this->Time->timeAgoInWords($vote['Vote']['created']) . '</span></td><td>You upvoted ' . $vote['Candidate']['name'] . ' in poll ' . $this->Html->link($vote['Quiz']['name'], array('controller' => 'quizzes', 'action' => 'view', $vote['Quiz']['id'])) . '</td>';
				}
				else{
					echo '<td><span class="label date">  ' . $this->Time->timeAgoInWords($vote['Vote']['created']) . '</span></td><td>You downvoted ' . $vote['Candidate']['name'] . ' in poll ' . $this->Html->link($vote['Quiz']['name'], array('controller' => 'quizzes', 'action' => 'view', $vote['Quiz']['id'])) . '</td>';
				}
				echo "</tr>";
			}
			echo "</table>";
		?>	
	</div>
</div>

<script type="text/javascript">
mixpanel.track('Profile edit');
</script>