<div class="row">
  <div class="small-9 small-centered columns panel">
<?php
echo "<h3>" . $title . "</h3>";
?>



    <?php echo $this->Form->create('Candidate'); ?>
        <fieldset class='newquizform'>
            <?php
            echo $this->Form->input('Quiz.name');
            echo $this->Form->input('Quiz.description');
            echo $this->Form->input('Quiz.start_date', array('dateFormat' => 'DMY', 'timeFormat' => null, 'separator' => ''));
            echo $this->Form->input('Quiz.finish_date', array('dateFormat' => 'DMY', 'timeFormat' => null, 'separator' => ''));
        ?>
        </fieldset>
        <hr>
        <div class='candidates'>
            <div class='well createwell'>
                <?php echo $this->Form->button('&times;', array('type' => 'button', 'class' => 'close tiny button right secondary removecandidate')); ?>
            <fieldset>
                <?php
                echo $this->Form->input('Candidate.0.name');
                echo $this->Form->input('Candidate.0.image_url');
                echo $this->Form->button('Add image', array('class' => 'uploadfile small', 'type' => 'button'));
                echo $this->Form->input('Candidate.0.description');
                echo $this->Form->input('Candidate.0.url');
                echo $this->Form->input('Candidate.0.inchi');
                echo $this->Form->input('Candidate.0.smiles');
                ?>

            </fieldset>
            </div>
        </div>

        <?php
            echo $this->Form->button('Add another candidate', array('type' => 'button', 'class' => 'addcandidate small'));
            echo $this->Form->submit('Create new poll', array('class' => 'button success')); 

        ?>

</div>
</div>

<?php
//script tag for filepicker
echo $this->Html->script('//api.filepicker.io/v1/filepicker.js');
$inkapikey = Configure::read('inkapikey');
?>
<script>
filepicker.setKey(<?php echo "'" . $inkapikey . "'"; ?>);
</script>
<script type="text/javascript">
mixpanel.track('Create quiz');
</script>



