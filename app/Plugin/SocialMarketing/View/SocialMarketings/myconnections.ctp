<section class="sep-bot top-smspace">
	<div class="container clearfix bot-space">
		<div class="label label-info show text-18 clearfix no-round ver-mspace">
			<div class="span smspace"><?php echo __l('Social'); ?></div>
			<?php echo $this->element('settings-menu', array('cache' => array('config' => 'sec', 'key' => $this->Auth->user('id')))); ?>
		</div>
	</div>
</section>
<div class="container top-space clearfix">          
		<div class=" space ver-mspace">
  <?php $user = $this->Html->getCurrUserInfo($this->Auth->user('id')); ?>
  <div class="row page-header space-bottom">
    <div class="span2 text-42"><i class="icon-facebook-sign facebookc"></i></div>
    <?php if (!empty($user['User']['is_facebook_connected'])) { ?>
      <?php
        $width = Configure::read('thumb_size.medium_thumb.width');
        $height = Configure::read('thumb_size.medium_thumb.height');
        $user_image = $this->Html->getFacebookAvatar($user['User']['facebook_user_id'], $height, $width);
      ?>
      <div class="span13 space well"><?php echo $user_image . ' ' . __l('You have already connected to Facebook.')?></div>
      <?php if (empty($user['User']['is_facebook_register'])): ?>
        <div class="span5 offset1">
          <?php echo $this->Html->link(sprintf(__l('Disconnect from %s'), __l('Facebook')), array('controller' => 'social_marketings', 'action' => 'myconnections', 'facebook'), array('title' => sprintf(__l('Disconnect from %s'), __l('Facebook')) ,'class' => 'span4 btn js-confirm')); ?>
        </div>
      <?php endif; ?>
    <?php } else { ?>
      <div class="span13 space well"><?php echo __l('Increase your reputation by showing Facebook friends count.'); ?></div>
      <?php
        $connect_url = Router::url(array(
          'controller' => 'social_marketings',
          'action' => 'import_friends',
          'type' =>'facebook',
          'import' => 'facebook',
          'from' => 'social'
        ), true);
      ?>
      <div class="span5 offset1"><?php echo $this->Html->link(sprintf(__l('Connect with %s'), __l('Facebook')), $connect_url, array('title' => sprintf(__l('Connect with %s'),__l('Facebook')) , 'class' => 'js-connect js-no-pjax span4 btn {"url":"'.$connect_url.'"}')); ?></div>
    <?php } ?>
  </div>
  <div class="row page-header space-bottom">
    <div class="span2 text-42"><i class="icon-twitter-sign twitterc"></i></div>
    <?php if (!empty($user['User']['is_twitter_connected'])) { ?>
      <?php
        $width = Configure::read('thumb_size.medium_thumb.width');
        $height = Configure::read('thumb_size.medium_thumb.height');
		$user_image = '';
        if (!empty($user['User']['twitter_avatar_url'])):
          $user_image = $this->Html->image($user['User']['twitter_avatar_url'], array(
            'title' => $this->Html->cText($user['User']['username'], false) ,
            'width' => $width,
            'height' => $height
          ));
        endif;
      ?>
      <div class="span13 space well"><span class="space-left"><?php echo $user_image . ' ' . __l('You have already connected to Twitter.'); ?></span></div>
      <?php if (empty($user['User']['is_twitter_register'])): ?>
        <div class="span5 offset1">
          <?php echo $this->Html->link(sprintf(__l('Disconnect from %s'), __l('Twitter')), array('controller' => 'social_marketings', 'action' => 'myconnections', 'twitter'), array('title' => sprintf(__l('Disconnect from %s'), __l('Twitter')),'class' => 'span4 btn js-confirm')); ?>
        </div>
      <?php endif; ?>
    <?php } else { ?>
      <div class="span13 space well"><?php echo __l('Increase your reputation by showing Twitter followers count.')?></div>
      <?php
        $connect_url = Router::url(array(
          'controller' => 'social_marketings',
          'action' => 'import_friends',
          'type' =>'twitter',
          'import' => 'twitter',
          'from' => 'social'
        ), true);
      ?>
      <div class="span5 offset1"><?php echo $this->Html->link(sprintf(__l('Connect with %s'), __l('Twitter')), $connect_url, array('title' => sprintf(__l('Connect with %s'), __l('Twitter')),'class' => 'js-connect js-no-pjax span4 btn {"url":"'.$connect_url.'"}')); ?></div>
    <?php } ?>
  </div>
  <div class="row page-header space-bottom">
    <div class="span2 text-42"><i class="icon-google-sign googlec"></i></div>
    <?php if (!empty($user['User']['is_google_connected'])) { ?>
					<?php
        $width = Configure::read('thumb_size.medium_thumb.width');
        $height = Configure::read('thumb_size.medium_thumb.height');
		$user_image = '';
        if (!empty($user['User']['google_avatar_url'])):
          $user_image = $this->Html->image($user['User']['google_avatar_url'], array(
            'title' => $this->Html->cText($user['User']['username'], false) ,
            'width' => $width,
            'height' => $height
          ));
        endif;
      ?>
      <div class="span13 space well"><?php  echo $user_image . ' ' . __l('You have already connected to Gmail.')?></div>
      <?php if (empty($user['User']['is_google_register'])): ?>
        <div class="span5 offset1">
          <?php echo $this->Html->link(sprintf(__l('Disconnect from %s'), __l('Gmail')), array('controller' => 'social_marketings', 'action' => 'myconnections', 'google'), array('title' => sprintf(__l('Disconnect from %s'), __l('Gmail')),'class' => 'span4 btn js-confirm')); ?>
        </div>
      <?php endif; ?>
    <?php } else { ?>
      <div class="span13 space well"><?php echo __l('Increase your reputation by showing Google contacts count.')?></div>
      <?php
        $connect_url = Router::url(array(
          'controller' => 'social_marketings',
          'action' => 'import_friends',
          'type' =>'google',
          'import' => 'google',
          'from' => 'social'
        ), true);
      ?>
      <div class="span5 offset1"><?php echo $this->Html->link(sprintf(__l('Connect with %s'), __l('Gmail')), $connect_url, array('title' => sprintf(__l('Connect with %s'), __l('Gmail')),'class' => 'js-connect js-no-pjax span4 btn {"url":"'.$connect_url.'"}'));
      ?></div>
    <?php } ?>
  </div>
  <div class="row page-header space-bottom">
    <div class="span2 text-42"><i class="icon-google-plus-sign googlec"></i></div>
    <?php if (!empty($user['User']['is_googleplus_connected'])) { ?>
      <div class="span13 space well"><?php echo __l('You have already connected to Google+.')?></div>
      <?php if (empty($user['User']['is_googleplus_register'])): ?>
        <div class="span5 offset1">
          <?php echo $this->Html->link(sprintf(__l('Disconnect from %s'), __l('Google+')), array('controller' => 'social_marketings', 'action' => 'myconnections', 'googleplus'), array('title' => sprintf(__l('Disconnect from %s'), __l('Google+')),'class' => 'span4 btn js-confirm')); ?>
        </div>
      <?php endif; ?>
    <?php } else { ?>
      <div class="span13 space well"><?php echo __l('Increase your reputation by showing Google+ contacts count.')?></div>
      <?php
        $connect_url = Router::url(array(
          'controller' => 'social_marketings',
          'action' => 'import_friends',
          'type' =>'googleplus',
          'import' => 'googleplus',
          'from' => 'social'
        ), true);
      ?>
      <div class="span5 offset1"><?php echo $this->Html->link(sprintf(__l('Connect with %s'), __l('Google+')), $connect_url, array('title' =>sprintf(__l('Connect with %s'), __l('Google+')),'class' => 'js-connect js-no-pjax span4 btn {"url":"'.$connect_url.'"}'));
      ?></div>
    <?php } ?>
  </div>
  <div class="row page-header space-bottom">
    <div class="span2 text-42"><i class="icon-yahoo yahooc"></i></div>
    <?php if (!empty($user['User']['is_yahoo_connected'])) { ?>
      <div class="span13 space well"><?php  echo __l('You have already connected to Yahoo!.')?></div>
      <?php if (empty($user['User']['is_yahoo_register'])): ?>
        <div class="span5 offset1">
          <?php echo $this->Html->link(sprintf(__l('Disconnect from %s'), 'Yahoo'), array('controller' => 'social_marketings', 'action' => 'myconnections', 'yahoo'), array('title' => sprintf(__l('Disconnect from %s'), __l('Yahoo')),'class' => 'span4 btn js-confirm')); ?>
        </div>
      <?php endif; ?>
    <?php } else { ?>
      <?php
        $connect_url = Router::url(array(
          'controller' => 'social_marketings',
          'action' => 'import_friends',
          'type' =>'yahoo',
          'import' => 'yahoo',
          'from' => 'social'
        ), true);
      ?>
      <div class="span13 space well"><?php echo __l('Increase your reputation by showing Yahoo! contacts count.')?></div>
      <div class="span5 offset1"><?php echo $this->Html->link(sprintf(__l('Connect with %s'), __l('Yahoo!')), $connect_url, array('title' => sprintf(__l('Connect with %s'), __l('Yahoo!')), 'class' => 'js-connect js-no-pjax span4 btn {"url":"'.$connect_url.'"}'));
      ?></div>
    <?php } ?>
  </div>
  <div class="row page-header space-bottom">
    <div class="span2 text-42"><i class="icon-linkedin-sign linkedc"></i></div>
    <?php if (!empty($user['User']['is_linkedin_connected'])) { ?>
	<?php
        $width = Configure::read('thumb_size.medium_thumb.width');
        $height = Configure::read('thumb_size.medium_thumb.height');
		$user_image = '';
        if (!empty($user['User']['linkedin_avatar_url'])):
          $user_image = $this->Html->image($user['User']['linkedin_avatar_url'], array(
            'title' => $this->Html->cText($user['User']['username'], false) ,
            'width' => $width,
            'height' => $height
          ));
        endif;
    ?>
      <div class="span13 space well"><?php  echo $user_image . ' ' . __l('You have already connected to LinkedIn.')?></div>
      <?php if (empty($user['User']['is_linkedin_register'])): ?>
        <div class="span5 offset1">
          <?php echo $this->Html->link(sprintf(__l('Disconnect from %s'), __l('Linkedin')), array('controller' => 'social_marketings', 'action' => 'myconnections', 'linkedin'), array('title' =>sprintf(__l('Disconnect from %s'), __l('Linkedin')),'class' => 'span4 btn js-confirm')); ?>
        </div>
      <?php endif; ?>
    <?php } else { ?>
      <div class="span13 space well"><?php echo __l('Increase your reputation by showing LinkedIn connections count.')?></div>
      <?php
        $connect_url = Router::url(array(
          'controller' => 'social_marketings',
          'action' => 'import_friends',
          'type' =>'linkedin',
          'import' => 'linkedin',
          'from' => 'social'
        ), true);
      ?>
      <div class="span5 offset1"><?php echo $this->Html->link(sprintf(__l('Connect with %s'), __l('Linkedin')), $connect_url, array('title' => sprintf(__l('Connect with %s'), __l('Linkedin')) ,'class' => 'js-connect js-no-pjax span4 btn {"url":"'.$connect_url.'"}'));
      ?></div>
    <?php } ?>
  </div>
</div>
</div>