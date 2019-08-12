<div class="label label-success tab-head show no-round clearfix">
  <h5 class="pull-left whitec textn ver-smspace text-16"><i class="icon-user hor-smspace text-16"></i> <span><?php echo __l('Recently Registered Users'); ?></span></h5>
</div>
<section class="sep">
  <div class="space left-mspace">
	<?php
		if (!empty($recentUsers)):
			$users = '';
			foreach ($recentUsers as $user):
				$users .= sprintf('%s, ',$this->Html->link($this->Html->cText($user['User']['username'], false), array('controller'=> 'users', 'action' => 'view', $user['User']['username'], 'admin' => false)));
			endforeach;
			echo substr($users, 0, -2);
		else:
	?>
		<p class="notice"><?php echo __l('Recently no users registered');?></p>
	<?php
		endif;
	?>
  </div>
</section>