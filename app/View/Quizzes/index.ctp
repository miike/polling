<?php

//echo $this->Html->meta('title','test title');
?>


<div class="row">
  <div class="small-12 small-centered columns panel">
<?php
	echo "<h2>$title</h2>";
	echo "<table class='table'>";
	echo "<thead><tr><th>Poll name</th><th>Opened</th><th>Closes in</th><th>Leader</th>";

	if ($admin == '1'){
		echo "<th>Review</th><th>Edit</th>";
	}
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";

	foreach($quizzes as $quiza){
		echo "<tr>";
		$quiz = $quiza['Quiz'];
		echo "<td>" . $this->Html->link($quiz['name'],array('controller'=>'quizzes','action'=>'view', $quiz['id'])) . "</td>";
		echo "<td>" . $this->Time->timeAgoInWords($quiz['start_date']) . "</td>";
		echo "<td>" . $this->Time->timeAgoInWords($quiz['finish_date']) . "</td>";
		echo "<td>" . $quiz['top'] . " ";
		echo "<span class='label'>" . $quiz['top_count'] . "</span>";
		echo "</td>";
		if ($admin == '1'){
			echo "<td>" . $this->Html->link('Review', array('controller' => 'quizzes', 'action' => 'review', $quiz['id'])) . "</td>";
			echo "<td>" . $this->Html->link('Edit', array('controller' => 'quizzes', 'action' => 'edit', $quiz['id'])) . "</td>";
		}
		echo "</tr>\n";
	}
	echo "</tbody>";
	echo "</table>";
?>
	</div>
</div>

<script type="text/javascript">mixpanel.track('Index load');</script>


