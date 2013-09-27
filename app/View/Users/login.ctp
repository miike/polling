<div class="row">
	<div class="small-6 small-centered columns">
		<div class="users form">
		<?php echo $this->Session->flash('auth'); ?>
		<?php echo $this->Form->create('User'); ?>
		    <fieldset>
		        <legend><?php echo __('Please enter your identity details'); ?></legend>
		        <?php
		        echo "It helps if we know who you are when you vote or comment. In science you carry your reputation with you.";
		        ?>
		        <?php 
		        echo "</br></br>";
		        echo $this->Form->input('username', array('class'=>'form-control'));
		        echo $this->Form->input('password', array('class'=>'form-control'));
		    	?>
		    
		<?php
		$options = array(
			'label' => 'Identify',
			'class' => 'button',

			);
		echo $this->Form->end($options); 

		?>
		</fieldset>
		</div>
	</div>
</div>