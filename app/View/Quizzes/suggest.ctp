<div class="row">
	<div class="large-8 large-centered columns">

<?php
echo $title;

echo $this->Form->create('Candidate');
echo $this->Form->input('name');
echo $this->Form->input('image_url');
echo $this->Form->button('Add image', array('class' => 'uploadfile', 'type' => 'button'));
echo $this->Form->input('description');
echo $this->Form->input('url');
echo $this->Form->input('inchi');
echo $this->Form->input('smiles');
echo $this->Form->input('quiz_id', array('type'=>'select'));
echo $this->Form->submit('Suggest');

echo $this->Form->end();

?>

<?php
//script tag for filepicker
echo $this->Html->script('//api.filepicker.io/v1/filepicker.js');
$inkapikey = Configure::read('inkapikey');
?>
<script>
filepicker.setKey(<?php echo "'" . $inkapikey . "'"; ?>);
</script>

</div>
</div>

<script type="text/javascript">
mixpanel.track('Suggest');
</script>