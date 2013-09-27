<!-- app/View/Users/add.ctp -->
<div class="row">
	<div class="small-6 small-centered columns">
		<div class="users form">
		<?php echo $this->Form->create('User'); ?>
		    <fieldset>

		    	
		        <legend><?php echo __('Register'); ?></legend>
		        <?php
		        echo "It helps if we know who you are when you vote or comment. In science you carry your reputation with you.</br></br>";
		        ?>
		        <?php
		        echo $this->Form->input('email');
		        echo $this->Form->input('username');
		        echo $this->Form->input('name');
		        echo $this->Form->input('password');


		        /*echo $this->Form->input('role', array(
		            'options' => array('admin' => 'Admin', 'author' => 'Author')
		        ));*/


		$options = array(
			'label' => 'Register',
			'class' => 'button success',

			);
		echo $this->Form->end($options); 
		?>
		</fieldset>
		</div>
	</div>
</div>