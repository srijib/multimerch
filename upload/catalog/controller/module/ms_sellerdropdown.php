<?php  
class ControllerModuleMsSellerdropdown extends Controller {
	protected function index($setting) {
		require_once(DIR_SYSTEM . 'library/ms-seller.php');
		require_once(DIR_SYSTEM . 'library/ms-image.php');
		$this->msImage = new MsImage($this->registry);			
		$this->msSeller = new MsSeller($this->registry);		
		
		static $module = 0;
		$this->load->model('tool/image');
		
		$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/multiseller.css');
		$this->data = array_merge($this->data, $this->load->language('module/multiseller'));
		
		if (isset($setting['limit']))
			$this->data['limit'] = (int)$setting['limit'];
		else
			$this->data['limit'] = 3;		

		if (!isset($setting['width']) || (int)$setting['width'] <= 0)
			$setting['width'] = $this->config->get('config_image_category_width');
		
		if (!isset($setting['height']) || (int)$setting['height'] <= 0)
			$setting['height'] = $this->config->get('config_image_category_height');
			
		$this->data['sellers_href'] = $this->url->link('product/seller');
				
		$data = array(
			'order_by'               => 'date_added',
			'order_way'              => 'DESC',
			'page'              => 1,
			'limit'              => $this->data['limit']
		);
		
		$results = $this->msSeller->getSellers($data, TRUE);

		$this->data['sellers'] = array();
		foreach ($results as $result) {
			$this->data['sellers'][] = array(
				'nickname'        => $result['nickname'],
				'href'        => $this->url->link('product/seller/profile','seller_id=' . $result['seller_id']),
			);
		}
		$this->data['module'] = $module++; 
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/multiseller/module/ms-sellerdropdown.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/multiseller/module/ms-sellerdropdown.tpl';
		} else {
			$this->template = 'default/template/module/multiseller/ms-sellerdropdown.tpl';
		}
		
		$this->render(); 
	}
}
?>