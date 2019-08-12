<?php /* SVN: $Id: $ */ ?>
<div class="sudopayIpnLogs index">
<section class="space clearfix">
<div class="pull-left hor-space">
<?php echo $this->element('paging_counter');?>
</section>
<section class="space">
<table class="table table-striped table-bordered table-condensed table-hover">
  <tr>
    <th class="dc"><?php echo $this->Paginator->sort('created', __l('Added On'));?></th>
    <th><?php echo $this->Paginator->sort('ip', __l('IP'));?></th>
    <th><?php echo $this->Paginator->sort('post_variable', __l('Post Variable'));?></th>
  </tr>
<?php
if (!empty($sudopayIpnLogs)):
foreach ($sudopayIpnLogs as $sudopayIpnLog):
?>
  <tr>
    <td class="dc"><?php echo $this->Html->cDateTimeHighlight($sudopayIpnLog['SudopayIpnLog']['created']);?></td>
    <td><?php echo $this->Html->cText($sudopayIpnLog['SudopayIpnLog']['ip']);?></td>
    <td><?php echo $this->Html->cText($sudopayIpnLog['SudopayIpnLog']['post_variable']);?></td>
  </tr>
<?php
  endforeach;
else:
?>
  <tr>
    <td colspan="6" class="errorc space"><i class="icon-warning-sign errorc"></i> <?php echo sprintf(__l('No %s available'), __l('SudoPay IPN Logs'));?></td>
  </tr>
<?php
endif;
?>
</table>
</section>
<?php if (!empty($sudopayIpnLogs)) { ?>
<section class="clearfix hor-mspace bot-space">
<div class="pull-right">
<?php echo $this->element('paging_links');?>
</div>
</section>
<?php } ?>
</div>
