<?php
class Gateway {
	private $_module;
	private $_basket;

	public function __construct($module = false, $basket = false) {
		$this->_module  =  $module;
		$this->_basket  =& $GLOBALS['cart']->basket;
		$this->_session =& $GLOBALS['user'];
	}

	public function transfer() {
		$transfer	= array(
			'action'	=> $GLOBALS['storeURL'].'/index.php?_g=rm&amp;type=gateway&amp;cmd=call&amp;module=Net_30',
			'method'	=> 'post',
			'target'	=> '_self',
			'submit'	=> 'manual',
		);
		return $transfer;
	}

	public function repeatVariables() {
		return false;
	}

	public function fixedVariables() {
		// Change 'Make Payment' button text to something more appropriate
		$GLOBALS['smarty']->assign('BTN_PROCEED', $GLOBALS['language']->net_30['button_confirm']);
		return array(
			'amount'  => $this->_basket['total'],
			'invoice' => $this->_basket['cart_order_id'],
		);
	}

	public function call() {
		$cart_order_id = filter_input(INPUT_POST, 'invoice');
		if (!empty($cart_order_id)) {
			$order         = Order::getInstance();
			$order_summary = $order->getSummary($cart_order_id);
			// Confirm group membership before further processing
			$allowed = false;
			$memberships = $GLOBALS['user']->getMemberships($GLOBALS['user']->getId());
			if (is_array($memberships)) {
				foreach ($memberships as $membership) {
					if (in_array($membership['group_id'], $this->_module['groups'])) {
						$allowed = true;
						break;
					}
				}
			}
			$return = 'confirm';
			if ($allowed) {
				$return = 'complete';
				$order->paymentStatus(Order::PAYMENT_PENDING, $cart_order_id);
				// Set to processing to indicate that payment terms have been accepted
				$order->orderStatus(Order::ORDER_PROCESS, $cart_order_id);
				$GLOBALS['gui']->setNotify($GLOBALS['language']->net_30['customer_group_accepted']);
			} else {
				$order->paymentStatus(Order::PAYMENT_DECLINE, $cart_order_id);
				$order->orderStatus(Order::ORDER_DECLINED, $cart_order_id);
				$GLOBALS['gui']->setError($GLOBALS['language']->net_30['customer_group_declined']);
			}
			// Build the transaction log data
			$transaction = array(
				'trans_id'    => 'N/A',
				'gateway'     => $_GET['module'],
				'customer_id' => $order_summary['customer_id'],
				'order_id'    => $cart_order_id,
				'status'      => ($allowed ? 'Accepted' : 'Declined'),
				'amount'      => '0.00', // did not charge anything
				'notes'       => ($allowed ? 'Amount to bill: ' . Tax::getInstance()->priceFormat($_POST['amount'], true) : '<br>Customer is not a member of any Net-30 enabled group.'),
			);
			$order->logTransaction($transaction);
			httpredir(currentPage(array('_g', 'type', 'cmd', 'module'), array('_a' => $return)));
		}
		return false;
	}

	public function process() {
		return false;
	}

	public function form() {
		return '<p>The amount due will be invoiced to the billing address on file with payment expected within 30 days unless other terms have been arranged.</p>';
	}
}
