<?php
if(!defined('CC_INI_SET')) die('Access Denied');
unset($this->_basket['Net_30']);
httpredir('index.php?_a=basket');
