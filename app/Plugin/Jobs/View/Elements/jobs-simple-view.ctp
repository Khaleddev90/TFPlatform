<?php
	echo $this->requestAction(array('controller' => 'jobs', 'action' => 'view', $slug, 'simple-view', 'order_id' => $order_id, 'admin' => false), array('return'));
?>