<?php
// Clear DISABLE_GATEWAYS variable if there are multiple payment gateways available
if (isset($GLOBALS['smarty']->getTemplateVars()['GATEWAYS']) && count($GLOBALS['smarty']->getTemplateVars()['GATEWAYS']) > 1) {
	$GLOBALS['smarty']->clearAssign('DISABLE_GATEWAYS');
}
