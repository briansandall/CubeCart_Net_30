<?php
// Enable Net-30 gateway if customer belongs to an associated group
$settings = $GLOBALS['config']->get('Net_30');
if ($settings['status']) {
	$allowed = false;
	$memberships = ($GLOBALS['user']->is() ? $GLOBALS['user']->getMemberships($GLOBALS['user']->getId()) : false);
	if (is_array($memberships)) {
		foreach ($memberships as $membership) {
			$allowed = in_array($membership['group_id'], $settings['groups']);
			if ($allowed) {
				break;
			}
		}
	}
	if ($allowed) {
		$_module_data = array(
				'plugin'      => true,
				'base_folder' => 'Net_30',
				'folder'      => 'Net_30',
				'desc'        => (empty($settings['desc']) ? $GLOBALS['language']->net_30['gateway_description_default'] : $settings['desc'])
			);
		$added = false;
		if (isset($gateways) && is_array($gateways)) {
			foreach ($gateways as $key => $gateway) {
				if ($gateway['folder'] == 'Net_30') {
					$gateways[$key] = array_merge($_module_data, $gateway);
					$added = true;
					break;
				}
			}
		}
		if (!$added) {
			$gateways[] = $_module_data;
		}
	}
}
