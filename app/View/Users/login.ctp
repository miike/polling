<div class="row">
	<div class="small-4 small-centered columns">
		<div class="users form">
		<?php echo $this->Session->flash('auth'); ?>
		<?php echo $this->Form->create('User'); ?>
		    <fieldset>
		        <legend><?php echo __('Please enter your login details'); ?></legend>
		        <?php echo $this->Form->input('username', array('class'=>'form-control'));
		        echo $this->Form->input('password', array('class'=>'form-control'));
		    ?>
		    
		<?php
		$options = array(
			'label' => 'Login',
			'class' => 'button',

			);
		echo $this->Form->end($options); 

		?>
		</fieldset>
		</div>
	</div>
</div>