<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $title_for_layout; ?>
	</title>
	<?php

		//bootstrap
		//echo $this->Html->css('bootstrap');
		//echo $this->Html->script('bootstrap');

		echo $this->Html->meta('icon');

		//echo $this->Html->css('cake.generic');

		echo $this->fetch('meta');
		//echo $this->fetch('css');
		echo $this->fetch('script');

		

		//foundation css
		echo $this->Html->css('foundation');
		echo $this->Html->css('general_foundicons');
		//base (customised) CSS
		echo $this->Html->css('quiz');


		//include modernizr, jquery
		echo $this->Html->script('custom.modernizr');
		echo $this->Html->script('jquery');

		//echo $this->Html->script('jquery-1.10.2.min');
		echo $this->Html->script('voting'); //responsible for upvoting
		echo $this->Html->script('foundation.min');
		echo $this->Html->script('zepto');
		

	?>

<!-- start Mixpanel -->
<script type="text/javascript">(function(e,b){if(!b.__SV){var a,f,i,g;window.mixpanel=b;a=e.createElement("script");a.type="text/javascript";a.async=!0;a.src=("https:"===e.location.protocol?"https:":"http:")+'//cdn.mxpnl.com/libs/mixpanel-2.2.min.js';f=e.getElementsByTagName("script")[0];f.parentNode.insertBefore(a,f);b._i=[];b.init=function(a,e,d){function f(b,h){var a=h.split(".");2==a.length&&(b=b[a[0]],h=a[1]);b[h]=function(){b.push([h].concat(Array.prototype.slice.call(arguments,0)))}}var c=b;"undefined"!==
typeof d?c=b[d]=[]:d="mixpanel";c.people=c.people||[];c.toString=function(b){var a="mixpanel";"mixpanel"!==d&&(a+="."+d);b||(a+=" (stub)");return a};c.people.toString=function(){return c.toString(1)+".people (stub)"};i="disable track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config people.set people.set_once people.increment people.append people.track_charge people.clear_charges people.delete_user".split(" ");for(g=0;g<i.length;g++)f(c,i[g]);
b._i.push([a,e,d])};b.__SV=1.2}})(document,window.mixpanel||[]);
mixpanel.init(<?php echo '"' . Configure::read('mixpanelhash') . '"'; ?>);</script>
<!-- end Mixpanel -->


</head>
<body>

	<nav class="top-bar">
  <ul class="title-area">
    <!-- Title Area -->
    <li class="name">
      <h1><?php echo $this->Html->link('Home', array('controller' => 'quizzes', 'action' => 'index'), array('class'=>'brand')); ?></h1>
    </li>
    <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
    <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
  </ul>

  <section class="top-bar-section">
    <!-- Left Nav Section -->
    <ul class="left">

        <?php 
			if ($admin == "1"){
				echo "<li>" . $this->Html->link('Create poll', array('controller' => 'quizzes', 'action' => 'create')) . "</li>";
			}
		?>

	<li class="divider"></li>
      
      <?php
		if (!$username){
			echo "<li>" . $this->Html->link('Who are you?', array('controller' => 'users', 'action' => 'login')) . "</li>";
			echo "<li>" . $this->Html->link('Register', array('controller' => 'users', 'action' => 'add')) . "</li>";
		}
		else{
			echo "<li>" . $this->Html->link('Edit profile', array('controller' => 'users', 'action' => 'edit')) . "</li>";
		}

		?>




    </ul>

    <ul class="right">

      <li class="divider show-for-small"></li>
      <li class="has-form">
      	<?php
      	if (!$username){
        	echo $this->Html->link('Who are you?', array('controller' => 'users', 'action' => 'login'), array('class' => 'button'));
        }
        else{
        	echo "<li>" . $this->Html->link('Logout', array('controller'=>'users', 'action'=>'logout'), array('class' => 'button alert')) . "</li>"; 
        }
        ?>
      </li>
    </ul>
  </section>
</nav>

	<div id="container">

		<div id="content">

			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
		
	</div>
	<?php //echo $this->element('sql_dump'); ?>


<script>
  $(document).foundation();
</script>
</body>
</html>