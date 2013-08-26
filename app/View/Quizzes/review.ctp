<div class="row">
	<div class="large-10 large-centered columns panel">
<?php


//include google charts
echo $this->Html->script('http://www.google.com/jsapi');

echo "<h1>" . $title . "</h1>";


//flatten the data arrays
//$labels = implode(",", $labels);
//$votecount = implode(",", $votecount);

?>

<div id="votes_chart_div"></div>
<div id="time_chart_div"></div>


	<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
	        var data = google.visualization.arrayToDataTable([
	          ['Candidate Name', 'Upvotes', 'Downvotes'],
	          <?php
	          //TODO: write a helper to generate the google charts
	          	foreach($questions as $question){
	          		echo "['" . $question['name'] . "', " . $question['upvotes'] . "," . $question['downvotes'] . "],";
	          	}
	          ?>
	        ]);

	        var options = {
	          title: 'Candidate votes',
	          vAxis: {title: 'Number of votes',  titleTextStyle: {color: 'blue'}}
	        };

	        var chart = new google.visualization.BarChart(document.getElementById('votes_chart_div'));
	        chart.draw(data, options);

	        //draw the second chart

	        var data2 = google.visualization.arrayToDataTable([
	          ['Date', 'Votes'],
	          <?php
		          foreach($timedata as $date=>$vote_count){
		          	echo "['" . $date . "', " . $vote_count . "],";
		          }
	          ?>
	        ]);

	        var options2 = {
	          title: 'Votes over time'
	        };

	        var chart2 = new google.visualization.LineChart(document.getElementById('time_chart_div'));
	        chart2.draw(data2, options2);


      }

    </script>

</div>
</div>