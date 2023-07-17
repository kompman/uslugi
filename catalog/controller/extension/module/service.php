<?php
class ControllerExtensionModuleService extends Controller {
    public function index() {
        $this->load->language('extension/module/service');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/module/service');
        $this->load->model('tool/image');

        $data['services'] = [];

        $results = $this->model_extension_module_service->getServices();

        foreach ($results as $result) {
            if (is_file(DIR_IMAGE . $result['image'])) {
                $image = $this->model_tool_image->resize($result['image'], 500, 500);
            } else {
                $image = $this->model_tool_image->resize('no_image.png', 500, 500);
            }

            $data['services'][] = [
                'name' => $result['name'],
                'description' => html_entity_decode($result['description']),
                'price' => $result['price'],
                'image' => $image,
                'file' => $this->url->link('download/file', 'filename=' . $result['file']),
                'meta_title' => $result['meta_title'],
                'meta_description' => $result['meta_description'],
                'href'  => $this->url->link('extension/module/service/info', 'service_id=' . $result['service_id'])
            ];
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');

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
    $this->load->model('tool/image');

    $service_info = $this->model_extension_module_service->getService($service_id);

    if ($service_info) {
        $this->document->setTitle($service_info['meta_title']);
        $this->document->setDescription($service_info['meta_description']);

        if (is_file(DIR_IMAGE . $service_info['image'])) {
            $image = $this->model_tool_image->resize($service_info['image'], 500, 500);
        } else {
            $image = $this->model_tool_image->resize('no_image.png', 500, 500);
        }

        
        $service_info['description'] = html_entity_decode($service_info['description'], ENT_QUOTES, 'UTF-8');

        $data['service_info'] = array_merge($service_info, ['image' => $image]);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/service_info', $data));
    } else {
        return new Action('error/not_found');
    }
}
    public function sendFeedback() {
    $json = array();

 if ($this->request->server['REQUEST_METHOD'] == 'POST') {
    // Проверяем наличие данных в $_POST
    if (isset($this->request->post['name']) && isset($this->request->post['phone']) && isset($this->request->post['service_name'])) {
        $name = $this->request->post['name'];
        $phone = $this->request->post['phone'];
        $service_name = $this->request->post['service_name'];  // получаем имя услуги
            $date = date('Y-m-d H:i:s');

            // Отправляем письмо администратору
            $to = 'kompman1@yandex.ru'; // Здесь нужно заменить на почту администратора
            $subject = 'Заявка на услугу';
            $message = "Имя: " . $name . "\nТелефон: " . $phone . "\nУслуга: " . $service_name . "\nДата обращения: " . $date;
            $headers = 'From: webmaster@example.com' . "\r\n" .
                'Reply-To: webmaster@example.com' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

            if (mail($to, $subject, $message, $headers)) {
                $json['success'] = 'Ваша заявка успешно отправлена!';
            } else {
                $json['error'] = 'Ошибка при отправке заявки!';
            }

            // Сохраняем заявку в базе данных
            $this->load->model('extension/module/service');
            $this->model_extension_module_service->addServiceRequest(array(
                'name' => $name,
                'phone' => $phone,
                'service_name' => $service_name,
                'date' => $date
            ));
        } else {
            $json['error'] = 'Пожалуйста, заполните все поля формы!';
        }
    } else {
        $json['error'] = 'Данный метод недоступен!';
    }

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
}
}
