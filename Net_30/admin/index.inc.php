<?php
if(!defined('CC_INI_SET')) die('Access Denied');
// Delay module->fetch() to allow assigning additional SMARTY variables
$module = new Module(__FILE__, $_GET['module'], 'admin/index.tpl', true, false);
if (($groups = $GLOBALS['db']->select('CubeCart_customer_group', false, false, array('group_name' => 'ASC'))) !== false) {
	foreach ($GLOBALS['hooks']->load('admin.customer.group_list') as $hook) include $hook;
	$GLOBALS['smarty']->assign('CUSTOMER_GROUPS', $groups);
	$enabled_groups = (isset($module->_settings['groups']) ? $module->_settings['groups'] : array());
	if (!empty($enabled_groups)) {
		$enabled = array();
		foreach ($groups as $group) {
			if (in_array($group['group_id'], $enabled_groups)) {
				$enabled[] = $group;
			}
		}
		$GLOBALS['smarty']->assign('ENABLED_GROUPS', $enabled);
	}
}
$module->fetch();
$page_content = $module->display();
