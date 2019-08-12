<div class="extensions-plugins">
  <div class="clearfix">
    <div class="pull-right hor-space">
	  <i class="icon-upload"></i><?php echo $this->Html->link(__l(' Upload Plugin'), array('action'=>'add'),array('class' =>'blackc')); ?>
	</div>
  </div>
  <div class="clearfix">
<?php
	$pluginTree = Configure::read('pluginsTree');
	foreach($pluginTree as $key => $plugin):
?>
	<div class="ver-space">
		<div class="thumbnail">
			<div class="clearfix">
				<div class="clearfix">
				  <div class="pull-left plug-img dc space">
					<?php if (in_array($key, array_keys($image_title_icons))): ?>
					  <?php echo $this->Html->image($image_title_icons[$key]. '.png'); ?>
					<?php endif; ?>
				  </div>
				  <div class="span22 top-space">
					<h4><?php echo $key; ?></h4>
				    <?php if (in_array($key, array_keys($title_description))): ?>
					      <div class="grayc top-space">
					     <?php echo $this->Html->cText($title_description[$key]); ?>
						  </div>
				    <?php endif; ?>
					</div>
				</div>
				<div class="sep-bot bot-space bot-space"></div>
			</div>	
<?php
			echo $this->Html->getPluginChildren($plugin, 0, $image_title_icons);
?>
		</div>
	</div>
	<?php
		if($key == "Modules"){
	?>
		<div class="plugin-height-diff">
		</div>
	<?php
		}
		endforeach;
	?>
  </div>
</div>