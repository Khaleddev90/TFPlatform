<div class="extensions-plugins space">
   <?php
    echo $this->Form->create('Plugin', array(
      'class' => 'space form-horizontal',
      'url' => array(
        'controller' => 'extensions_plugins',
        'action' => 'add',
      ),
      'type' => 'file',
    ));
  ?>
  <fieldset>
  
	<ul class="breadcrumb no-round top-mspace ver-space">
	  <li class="left-space"><?php echo $this->Html->link(__l('Plugins'), array('action' => 'index'), array('class' => 'bluec','title'=>__l('Upload Plugin'))); ?> <span class="divider graydarkerc ">/</span></li>
	  <li class="active graydarkerc">Upload Plugin</li>
	</ul>
 
  <div class="panel-container">
    <div id="add_form" class="tab-pane fade in active">
  <?php
    echo $this->Form->input('Plugin.file', array('label' => __l('Upload'), 'type' => 'file',));
  ?>
  </div>
  </div>
  </fieldset>
  <div class="clearfix">
    <div class="pull-left">
    <?php echo $this->Form->submit(__l('Upload'), array('class' => 'btn btn-primary')); ?></div>
    <div class = "hor-mspace hor-space pull-left" >
        <?php
        echo $this->Html->link(__l('Cancel'), array(
          'action' => 'index',
        ), array(
          'class' => 'btn',
        ));
        ?>
    </div>
  </div>
     <?php echo $this->Form->end(); ?>
</div>