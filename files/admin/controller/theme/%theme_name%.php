<?php
namespace Opencart\Admin\Controller\Extension\%ThemeName%\Theme;
/**
 * Class %ThemeName%
 *
 * @package Opencart\Admin\Controller\Extension\Opencart\Theme
 */
class %ThemeName% extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		$this->load->language('extension/%theme_name%/theme/%theme_name%');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['store_id'])) {
			$store_id = (int)$this->request->get['store_id'];
		} else {
			$store_id = 0;
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=theme')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/%theme_name%/theme/%theme_name%', 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $store_id)
		];

		$data['save'] = $this->url->link('extension/%theme_name%/theme/%theme_name%.save', 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $store_id);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=theme');

		if (isset($this->request->get['store_id'])) {
			$this->load->model('setting/setting');

			$setting_info = $this->model_setting_setting->getSetting('%theme_name%', $this->request->get['store_id']);
		}

		if (isset($setting_info['%theme_name%_status'])) {
			$data['%theme_name%_status'] = $setting_info['%theme_name%_status'];
		} else {
			$data['%theme_name%_status'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/%theme_name%/theme/%theme_name%', $data));
	}

	/**
	 * @return void
	 */
	public function save(): void {
		$this->load->language('extension/%theme_name%/theme/%theme_name%');

		if (isset($this->request->get['store_id'])) {
			$store_id = (int)$this->request->get['store_id'];
		} else {
			$store_id = 0;
		}

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/%theme_name%/theme/%theme_name%')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('%theme_name%', $this->request->post, $store_id);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function install(): void {
		if ($this->user->hasPermission('modify', 'extension/theme')) {
			// Add startup to catalog
			$startup_data = [
				'code'        => '%theme_name%',
				'description' => '%Name% theme extension',
				'action'      => 'catalog/controller/startup/%theme_name%',
				'status'      => 1,
				'sort_order'  => 2
			];

			// Add startup for admin
			$this->load->model('setting/startup');

			$this->model_setting_startup->addStartup($startup_data);
		}
	}

	public function uninstall(): void {
		if ($this->user->hasPermission('modify', 'extension/theme')) {
			$this->load->model('setting/startup');

			$this->model_setting_startup->deleteStartupByCode('%theme_name%');
		}
	}
}
