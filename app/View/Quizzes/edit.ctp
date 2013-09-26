<div class="row">
	<div class="large-8 large-centered columns">

<?php

$options = array(
	'dateFormat' => 'DMY',
	'minYear' => date('Y'),
	'maxYear' => date('Y') + 5,
	'type'=>'datetime',
	'separator' => ''
	);

echo "<div class='panel'>";
echo $this->Form->create('Quiz');
echo $this->Form->input('Quiz.name');
echo $this->Form->input('Quiz.description');
echo $this->Form->input('Quiz.start_date', array('dateFormat' => 'DMY', 'timeFormat' => null, 'separator' => ''));
echo $this->Form->input('Quiz.finish_date', array('dateFormat' => 'DMY', 'timeFormat' => null, 'separator' => ''));
echo "</div>";
echo "<hr>";
echo "<div class='candidates'>"; //start candidate div
$candidateindex = 0;
$candidates = $this->request->data['Candidate'];
foreach($candidates as $candidate){
	echo "<div class='panel'>";
	$prefix = 'Candidate.' . (string)$candidateindex . '.';
	echo $this->Form->input($prefix . 'id', array('hiddenField' => true));
	echo $this->Form->input($prefix . 'name');
	echo $this->Form->input($prefix . 'order');
	echo $this->Form->input($prefix . 'image_url');
	echo $this->Form->button('Add image', array('class' => 'uploadfile button small', 'type' => 'button'));
	echo $this->Form->input($prefix . 'description');
	echo $this->Form->input($prefix . 'url');
	echo $this->Form->input($prefix . 'inchi');
	echo $this->Form->input($prefix . 'smiles');
	echo "</div>";

	$candidateindex += 1;
}

echo "</div>"; //end candidate div

//echo $this->Form->button('Add another candidate', array('type' => 'button', 'class' => 'addsilentcandidate small'));

echo $this->Form->submit('Save changes', array('class' => 'button success right'));

?>

<?php
//script tag for filepicker
echo $this->Html->script('//api.filepicker.io/v1/filepicker.js');
$inkapikey = Configure::read('inkapikey');
?>
<script>
filepicker.setKey(<?php echo "'" . $inkapikey . "'"; ?>);
<script type="text/javascript">
mixpanel.track('Edit quiz');
</script>
</script>

</div>
</div>