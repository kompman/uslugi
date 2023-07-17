<?php
class ControllerExtensionModuleServiceRequest extends Controller {
    public function index() {
        // Загружаем список заявок
        $this->load->model('extension/module/service');
        $data['requests'] = $this->model_extension_module_service->getRequests();

        // Загружаем данные шапки, левой колонки и подвала
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        // Загружаем шаблон
        $this->response->setOutput($this->load->view('extension/module/service_request', $data));
    }
}
