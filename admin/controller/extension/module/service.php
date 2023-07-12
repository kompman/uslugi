<?php
class ControllerExtensionModuleService extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('extension/module/service');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/module/service');

        $this->getList();
    }

    public function add() {
        $this->load->language('extension/module/service');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/module/service');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_extension_module_service->addService($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/module/service', 'user_token=' . $this->session->data['user_token']));
        }

        $this->getForm();
    }

    public function edit() {
        $this->load->language('extension/module/service');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/module/service');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_extension_module_service->editService($this->request->get['service_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/module/service', 'user_token=' . $this->session->data['user_token']));
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('extension/module/service');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/module/service');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $service_id) {
                $this->model_extension_module_service->deleteService($service_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/module/service', 'user_token=' . $this->session->data['user_token']));
        }

        $this->getList();
    }

    protected function getList() {
        $results = $this->model_extension_module_service->getServices();

        foreach ($results as $result) {
            // Составление списка услуг для вывода на странице
            $data['services'][] = array(
                'service_id' => $result['service_id'],
                'name'       => $result['name'],
                'description'=> $result['description'],
                'price'      => $result['price'],
                'image'      => $result['image'],
                'meta_title' => $result['meta_title'],
                'meta_description' => $result['meta_description']
            );
        }

        // Загрузка представления
        $this->response->setOutput($this->load->view('extension/module/service_list', $data));
    }

    protected function getForm() {
        if (isset($this->request->get['service_id'])) {
            // Редактирование существующей услуги
            $service_info = $this->model_extension_module_service->getService($this->request->get['service_id']);
        } else {
            // Добавление новой услуги, устанавливаем значения по умолчанию
            $service_info = array(
                'name'       => '',
                'description'=> '',
                'price'      => '',
                'image'      => '',
                'meta_title' => '',
                'meta_description' => ''
            );
        }

        // Передача данных в представление
        $data['service_info'] = $service_info;

        // Загрузка представления
        $this->response->setOutput($this->load->view('extension/module/service_form', $data));
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'extension/module/service')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        // Проверка введенных данных
        if ((utf8_strlen($this->request->post['name']) < 1) || (utf8_strlen($this->request->post['name']) > 128)) {
            $this->error['name'] = $this->language->get('error_name');
        }
    
        

        // ... продолжайте проверку для других полей...

        return !$this->error;
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'extension/module/service')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        // Вы можете добавить дополнительные проверки перед удалением услуги

        return !$this->error;
    }

    public function install() {
        $this->load->model('extension/module/service');
        $this->model_extension_module_service->install();
    }

    public function uninstall() {
        $this->load->model('extension/module/service');
        $this->model_extension_module_service->uninstall();
    }
}
