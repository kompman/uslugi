<?php
class ControllerExtensionModuleService extends Controller {
    public function index() {
        $this->load->language('extension/module/service');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/module/service');

        $data['services'] = [];

        $results = $this->model_extension_module_service->getServices();

        foreach ($results as $result) {
            $data['services'][] = [
                'name' => $result['name'],
                'description' => $result['description'],
                'price' => $result['price'],
                'image' => $result['image'],
                'meta_title' => $result['meta_title'],
                'meta_description' => $result['meta_description'],
                'href'  => $this->url->link('extension/module/service/info', 'service_id=' . $result['service_id'])
            ];
        }

        $this->response->setOutput($this->load->view('extension/module/service', $data));
    }

    public function info() {
        $this->load->language('extension/module/service');

        if (isset($this->request->get['service_id'])) {
            $service_id = (int)$this->request->get['service_id'];
        } else {
            $service_id = 0;
        }

        $this->load->model('extension/module/service');

        $service_info = $this->model_extension_module_service->getService($service_id);

        if ($service_info) {
            $this->document->setTitle($service_info['meta_title']);
            $this->document->setDescription($service_info['meta_description']);

            $data['service_info'] = $service_info;

            $this->response->setOutput($this->load->view('extension/module/service_info', $data));
        } else {
            return new Action('error/not_found');
        }
    }
}
