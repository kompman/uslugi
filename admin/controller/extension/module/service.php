<?php
class ControllerExtensionModuleService extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('extension/module/service');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('extension/module/service');
        $this->getList();
    }

    public function install() {
        $this->load->model('extension/module/service');
        $this->model_extension_module_service->install();
    }

    public function uninstall() {
        $this->load->model('extension/module/service');
        $this->model_extension_module_service->uninstall();
    }

    public function add() {
        $this->load->language('extension/module/service');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('extension/module/service');

           if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
        if ($this->request->post['image'] == '') {
            $this->request->post['image'] = 'no_image.png';  // Установка изображения по умолчанию, если изображение не было загружено
        }
            
            if (!empty($this->request->files['file']['name'])) {
    $filename = basename(html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8'));
    $allowed = array(
        'application/pdf',
        'application/msword',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'image/jpeg',
        'image/pjpeg',
        'image/png',
        'image/x-png',
        'image/gif'
    );

    if (in_array($this->request->files['file']['type'], $allowed)) {
$upload_directory = '/var/www/fastuser/data/www/school.navinetx.ru/public_html/files/' . $filename;

        if (is_uploaded_file($this->request->files['file']['tmp_name'])) {
            if (move_uploaded_file($this->request->files['file']['tmp_name'], $upload_directory)) {
                $this->request->post['file'] = $filename;
            } else {
                $this->error['warning'] = $this->language->get('error_upload');
            }
        } else {
            $this->error['warning'] = $this->language->get('error_upload');
        }
    } else {
        $this->error['warning'] = $this->language->get('error_filetype');
    }
}
$data['action'] = $this->url->link('extension/module/service/add', 'user_token=' . $this->session->data['user_token'], true);

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
        if ($this->request->post['image'] == '') {
            $this->request->post['image'] = 'no_image.png';  // Установка изображения по умолчанию, если изображение не было загружено
        }

           if (!empty($this->request->files['file']['name'])) {
    $filename = basename(html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8'));
    $allowed = array(
        'application/pdf',
        'application/msword',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'image/jpeg',
        'image/pjpeg',
        'image/png',
        'image/x-png',
        'image/gif'
    );

    if (in_array($this->request->files['file']['type'], $allowed)) {
$root_path = realpath(DIR_APPLICATION . '../');
$upload_directory = $root_path . '/files/' . $filename;

        if (is_uploaded_file($this->request->files['file']['tmp_name'])) {
            if (move_uploaded_file($this->request->files['file']['tmp_name'], $upload_directory)) {
                $this->request->post['file'] = $filename;
            } else {
                $this->error['warning'] = $this->language->get('error_upload');
            }
        } else {
            $this->error['warning'] = $this->language->get('error_upload');
        }
    } else {
        $this->error['warning'] = $this->language->get('error_filetype');
    }
}
$data['action'] = $this->url->link('extension/module/service/edit', 'user_token=' . $this->session->data['user_token'] . '&service_id=' . $this->request->get['service_id'], true);

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

        if (isset($this->request->get['service_id']) && $this->validateDelete()) {
            $this->model_extension_module_service->deleteService($this->request->get['service_id']);
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
            'meta_description' => $result['meta_description'],
            'edit'       => $this->url->link('extension/module/service/edit', 'user_token=' . $this->session->data['user_token'] . '&service_id=' . $result['service_id']),
            'delete'     => $this->url->link('extension/module/service/delete', 'user_token=' . $this->session->data['user_token'] . '&service_id=' . $result['service_id'])
        );
    }
$data['base'] = HTTP_SERVER;
    $data['add'] = $this->url->link('extension/module/service/add', 'user_token=' . $this->session->data['user_token']);
     $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');
    // Загрузка представления
    $this->response->setOutput($this->load->view('extension/module/service_list', $data));
}

    protected function getForm() {
        $data = array();  // Инициализируем переменную $data

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
                'meta_description' => '',
                'file'       => ''
            );
        }
$this->load->model('tool/image');
if (isset($this->request->post['image'])) {
    $data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
} else if (!empty($service_info)) {
    $data['thumb'] = $this->model_tool_image->resize($service_info['image'], 100, 100);
} else {
    $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
}

$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        // Передача данных в представление
        $data['service_info'] = $service_info;
$data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');
        $data['token'] = $this->session->data['user_token'];
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
public function sendFeedback() {
    $json = array();

    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
        if (isset($this->request->post['name']) && isset($this->request->post['phone'])) {
            $name = $this->request->post['name'];
            $phone = $this->request->post['phone'];
            $service_name = "Название услуги"; 
            $date = date('Y-m-d H:i:s');

            $to = $this->config->get('config_email'); 
            $from = 'info@' . parse_url($this->config->get('config_url'), PHP_URL_HOST);

            $subject = 'Заявка на услугу';
            $message = "Имя: " . $name . "\nТелефон: " . $phone . "\nУслуга: " . $service_name . "\nДата обращения: " . $date;
            $headers = 'From: ' . $from . "\r\n" .
                'Reply-To: ' . $from . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

            if (mail($to, $subject, $message, $headers)) {
                $json['success'] = 'Ваша заявка успешно отправлена!';
            } else {
                $json['error'] = 'Ошибка при отправке заявки!';
            }

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

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'extension/module/service')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        // Вы можете добавить дополнительные проверки перед удалением услуги

        return !$this->error;
    }

}
