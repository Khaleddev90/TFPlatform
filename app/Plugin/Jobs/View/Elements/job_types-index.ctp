<?php
	if(!empty($this->request->params['named']['category'])) :
		echo $this->requestAction(array('controller' => 'job_types', 'action' => 'index','category' => $this->request->params['named']['category'], 'display' => $display), array('return'));
	else:
		echo $this->requestAction(array('controller' => 'job_types', 'action' => 'index', 'display' => $display), array('return'));
	endif
	
?>