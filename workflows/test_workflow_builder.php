<?php
namespace Podlove\Modules\Podflow\Workflows;

class Test_Workflow_Builder extends Workflow_Builder {
	public function __construct() {
		$this -> name = "Test";
	}

	protected function build_process_steps() {
		/**
		 * Order
		 */
		$this -> process_steps['fill_cart'] = new \ezcWorkflowNodeInput( array('continue_from_fill_cart' => new \ezcWorkflowConditionIsTrue(), ));
		$this -> process_steps['create_sales_order'] = new \ezcWorkflowNodeInput( array('continue_from_create_sales_order' => new \ezcWorkflowConditionIsTrue(), ));
		$this -> process_steps['create_invoice'] = new \ezcWorkflowNodeInput( array('continue_from_create_invoice' => new \ezcWorkflowConditionIsTrue(), ));

		/**
		 * Payment
		 */
		$this -> process_steps['3th_party_payment'] = new \ezcWorkflowNodeInput( array('continue_from_3th_party_payment' => new \ezcWorkflowConditionIsTrue(), 'is_payment_ok' => new \ezcWorkflowConditionIsBool(), ));
		$this -> process_steps['notify_customer'] = new \ezcWorkflowNodeInput( array('continue_from_notify_customer' => new \ezcWorkflowConditionIsTrue(), ));

		/**
		 * Ship
		 */
		$this -> process_steps['prepare_in_warehouse'] = new \ezcWorkflowNodeInput( array('continue_from_prepare_in_warehouse' => new \ezcWorkflowConditionIsTrue(), ));
		$this -> process_steps['request_shipment_service'] = new \ezcWorkflowNodeInput( array('continue_from_request_shipment_service' => new \ezcWorkflowConditionIsTrue(), ));
		$this -> process_steps['ship'] = new \ezcWorkflowNodeInput( array('continue_from_ship' => new \ezcWorkflowConditionIsTrue(), ));
	}

	protected function connect_process_steps() {
		$this -> workflow -> startNode -> addOutNode($this -> process_steps['fill_cart']);

		$this -> process_steps['fill_cart'] -> addOutNode($this -> process_steps['create_sales_order']);
		$this -> process_steps['create_sales_order'] -> addOutNode($this -> process_steps['create_invoice']);

		$merge = new \ezcWorkflowNodeSimpleMerge();
		$merge -> addInNode($this -> process_steps['create_invoice']);
		$merge -> addInNode($this -> process_steps['notify_customer']);
		$merge -> addOutNode($this -> process_steps['3th_party_payment']);

		$this -> process_steps['3th_party_payment'] -> addOutNode($is_payment_ok = new \ezcWorkflowNodeExclusiveChoice());
		$is_payment_ok -> addConditionalOutNode(new \ezcWorkflowConditionVariable('is_payment_ok', new \ezcWorkflowConditionIsTrue()), $this -> process_steps['prepare_in_warehouse'], //else
		$this -> process_steps['notify_customer']);
		$this -> process_steps['prepare_in_warehouse'] -> addOutNode($this -> process_steps['request_shipment_service']);
		$this -> process_steps['request_shipment_service'] -> addOutNode($this -> process_steps['ship']);

		$this -> workflow -> endNode -> addInNode($this -> process_steps['ship']);
	}

}
