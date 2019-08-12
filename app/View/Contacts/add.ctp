<div class="container">
  <?php if(isset($success)) : ?>
    <div class="alert alert-success">
      <?php echo __l('Thank you, we received your message and will get back to you as soon as possible.'); ?>
    </div>
  <?php else: ?>
    <div class="space clearfix sep-bot">
      <h3><?php echo __l('Contact Us'); ?></h3>
    </div>
  <div class="space">
    <?php
      echo $this->Form->create('Contact', array('class' => 'form-horizontal'));
	  echo $this->Form->input('contact_type_id', array('empty' => __l('Please Select'), 'label' => __l('Subject'), 'options' => $contact_types)); 
      echo $this->Form->input('first_name', array('label' => __l('First Name')));
      echo $this->Form->input('last_name', array('label' => __l('Last Name')));
      echo $this->Form->input('email', array('label' => __l('Email')));
      echo $this->Form->input('telephone', array('label' => __l('Telephone')));
      echo $this->Form->input('message', array('label' => __l('Message')));
    ?>
    <?php if(Configure::read('system.captcha_type') == "Solve Media"){?>
      <div class="input help clearfix bot-space">
        <div class="pull-left offset">
          <?php
          include_once VENDORS . DS . 'solvemedialib.php';  //include the Solve Media library
          echo solvemedia_get_html(Configure::read('captcha.challenge_key')); //outputs the widget
          ?>
        </div>
      </div>
    <?php } else { ?>
      <div class="clearfix bot-space">
        <div class="input help js-captcha-container thumbnail span captcha-block">
          <div class="pull-left">
            <?php echo $this->Html->image($this->Html->url(array('controller' => 'contacts', 'action' => 'show_captcha', md5(uniqid(time()))), true), array('alt' => __l('[Image: CAPTCHA image. You will need to recognize the text in it; audible CAPTCHA available too.]'), 'title' => __l('CAPTCHA image'), 'class' => 'captcha-img'));?>
          </div>
          <div class="input-append pull-left">
            <div class="dc">
              <?php echo $this->Html->link('<i class="icon-refresh text-16"></i> <span class="hide">' . __l('Reload CAPTCHA') . '</span>', '#', array('escape' => false, 'class' => 'js-captcha-reload js-no-pjax captcha-reload blackc no-under', 'title' => __l('Reload CAPTCHA')));?>
            </div>
            <div class="text-16">
              <div class="play-link">
                <?php echo $this->Html->link(__l('Click to play'), Router::url('/', true) . "flash/securimage/play.swf?audio=". $this->Html->url(array('controller' => 'contacts', 'action' => 'captcha_play', 'register')) ."&bgColor1=#777&bgColor2=#fff&iconColor=#000&roundedCorner=5&height=19&width=19&wmode=transparent", array('class' => 'js-captcha-play')); ?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php echo $this->Form->input('captcha', array('label' => __l('Security Code'))); ?>
    <?php } ?>
      <div class="submit-block well no-bor no-round dr clearfix">
			  <div class="submit mob-mspace">
        <?php echo $this->Form->submit(__l('Send'), array('class' => 'btn btn-large btn-warning textb text-20')); ?>
        </div>
    </div>
      <?php echo $this->Form->end(); ?>
    <?php endif; ?>
  </div>
</div>