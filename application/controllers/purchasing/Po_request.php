<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Po_request extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('model_mst_location', '', true);
        $this->load->model('model_login', '', true);
        $this->load->model('model_config', '', true);
        $this->load->model('purchasing/model_config_po', 'model_config_po', true);
        $this->load->model('purchasing/Model_tsc_purchase_request_doc_h', 'Model_purchase_h', true);
        $this->load->model('purchasing/Model_tsc_purchase_request_doc_d', 'Model_purchase_d', true);
        $this->load->model('model_admin', '', true);
        $this->load->model('purchasing/model_item', 'model_item', true); //modelo para obtener item en lista
        $this->load->model('model_zlog', '', true);
        $this->load->model('purchasing/Model_tsc_po_history', 'model_tsc_po_history', true);
    }

    public function index()
    {
        $this->load->view('templates/navigation');

        if (!isset($_SESSION['menus_list_user'][$this->config->item('purchasing_folder').'po_request'])) {
            $this->load->view('view_home');
        } else {
            $this->model_config->name = 'putaway_depart';
            $result_config = $this->model_config->get_value_by_setting_name();
            $depart = explode('|', $result_config);
            $data['var_location'] = assign_data($this->model_mst_location->get_data2());
            $result = $this->model_login->get_user_list_by_department($depart);
            $data['var_item'] = assign_data($this->model_item->get_data());
            $data['user_list'] = assign_data($result);
            $result = $this->model_admin->list_department();

            foreach ($result as $row) {
                $data['v_list_department'][] = [
                                    'depart_code' => $row['depart_code'],
                                    'depart_name' => $row['depart_name'],
                                    'depart_text' => $row['depart_text'],
                                    ];
            }
            $this->load->view('purchasing/view_request_po', $data);
        }
    }
    public function upload_img()
    {
       
        // CARGAR IMAGEN Y RUTA
        
        $data_img = $_POST['file_name'];

        $src = $_FILES['file']['tmp_name'];
        ($data_img); 

        $target_file = $this->config->item('po_folder_img');
        $temp = explode('.', $_FILES['file']['name']);
        $newfilename = $data_img.'.jpg';
        sleep(1);
        
        // GUARGAR IMAGEN
        $targ = $target_file.$newfilename;
        $result['status'] = move_uploaded_file($src, $targ);
    
        if ($result['status'] == 1) {
            $result['filename'] = $newfilename;
            $result['target_file'] = $target_file;
            $result['data_img'] = $data_img;

        echo json_encode($result);
        }
    }

    public function add_line_img()
    {
        $data_img = $_POST['data_img'];
         
    }
    public function insert_tsc_po_img_data($line_img,$doc_no_h,$data){
        foreach ($data as $row) {
            $this->Model_purchase_d->doc_no = $doc_no_h;
            $this->Model_purchase_d->line = $line_img;
            $this->Model_purchase_d->request_img = $row['request_img'];
            $result = $this->Model_purchase_d->update_img();
        }
    }

    public function create_new()
    {
        $this->model_zlog->insert('Create New P Order'); // insert log

        $item = json_decode(stripslashes($_POST['item']));
        $desc = json_decode(stripslashes($_POST['desc']));
        $qty = json_decode(stripslashes($_POST['qty']));
        $uom = json_decode(stripslashes($_POST['uom']));
        $loc = json_decode(stripslashes($_POST['loc']));
        $src_img = json_decode(stripslashes($_POST['src_img']));
        $line_img = json_decode(stripslashes($_POST['line_img']));
        $src_link = json_decode(stripslashes($_POST['src_link']));
        $src_remaks = json_decode(stripslashes($_POST['src_remaks']));

        $doc_date = $_POST['doc_date'];
        $h_loc = $_POST['h_loc'];
        $delivery_to = $_POST['delivery_to'];
        $shopping_pur = $_POST['shopping_pur'];
        $urgent_bol = $_POST['urgent'];
        $delivery_deadline = $_POST['delivery_deadline'];
        $choosen_depart = $_POST['choosen_depart'];
        $h_remarks = $_POST['message'];

        if ($urgent_bol == 'true') {
            $urgent = '1';
        } else {
            $urgent = '0';
        } // statuss

        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $created_user = $session_data['z_tpimx_user_id'];


        $doc_no = $this->insert_tsc_purchase_request_doc_h($created_user, $doc_date, $h_loc, $delivery_to, $delivery_deadline, $urgent, $choosen_depart, $shopping_pur, $h_remarks); // insert header
        unset($data);
        for ($i = 0; $i < count($item); ++$i) {
            $src_no_temp = $doc_no;
            $data[] = [
        'item_code' => $item[$i],
        'description' => $desc[$i],
        'qty' => $qty[$i],
        'uom' => $uom[$i],
        'src_loc' => $loc[$i],
        'request_img' => $doc_no."_".$line_img[$i].".jpg",
        'request_link' => $src_link[$i],
        'remarks' => $src_remaks[$i],
      ];
        }
        for ($i = 0; $i < count($item); ++$i) {
            
            $data_img[] = [
        'request_img' => $doc_no."_".$line_img[$i],
      ];
        }


        $this->insert_tsc_purchase_request_doc_d($doc_no, $data);
        if ($doc_no) {
            $response['status'] = '1';
            $response['doc_no_h'] = $doc_no;
            $response['img_data'] = $data_img;
            $response['msg'] = 'New Purchase Order has been created with     No = '.$doc_no;
            echo json_encode($response);
        } else {
            $response['status'] = '0';
            $response['msg'] = 'Error';
            echo json_encode($response);
        }
    }

    // funcion para generar el encabezado
    public function insert_tsc_purchase_request_doc_h($created_user, $doc_date, $h_loc, $delivery_to, $delivery_deadline, $urgent, $choosen_depart, $shopping_pur, $h_remarks)
    {
        // se obtiene el prefijo
        $this->model_config_po->name = 'new_purchase_doc_pref';
        $prefix = $this->model_config_po->get_value_by_setting_name();
        // se obtiene el No de documento anterior
        $this->model_config_po->name = 'new_purchase_doc_no';
        $last_doc_no = $this->model_config_po->get_value_by_setting_name();
        // se asigan la nueva numeracion
        $new_doc_no = $last_doc_no + 1;
        // se realiza la actualizacion del No en la tabla conf
        $this->model_config_po->name = 'new_purchase_doc_no';
        $this->model_config_po->valuee = $new_doc_no;
        $this->model_config_po->update_value();
        // calculo de No.de documento, con prefijo
        $this->model_config_po->name = 'new_purchase_doc_digit';
        $digit = $this->model_config_po->get_value_by_setting_name();

        $doc_no = $prefix.sprintf('%0'.$digit.'d', $new_doc_no);
        $id_statuss = '1';
        // obtencion de datos para ingresar en la tabla
        $datetime = get_datetime_now();
        $date = get_date_now();
        $this->Model_purchase_h->doc_no = $doc_no;
        $this->Model_purchase_h->doc_creation_datetime = $datetime;
        $this->Model_purchase_h->doc_datetime = $doc_date;
        $this->Model_purchase_h->request_by = $created_user;
        $this->Model_purchase_h->delivery_to = $delivery_to;
        $this->Model_purchase_h->shopping_purpose = $shopping_pur;
        $this->Model_purchase_h->urgent = $urgent;
        $this->Model_purchase_h->delivery_deadline = $delivery_deadline;
        $this->Model_purchase_h->id_statuss = $id_statuss;
        $this->Model_purchase_h->doc_location_code = $h_loc;
        $this->Model_purchase_h->from_department = $choosen_depart;
        $this->Model_purchase_h->remarks = $h_remarks;
        $result = $this->Model_purchase_h->insert_h();

        $this->model_tsc_po_history->insert($doc_no, $id_statuss, $h_remarks, '', '');

        if ($result) {
            return $doc_no;
        } else {
            return false;
        }
    }

    public function insert_tsc_purchase_request_doc_d($doc_no, $data)
    {
        $datetime = get_datetime_now();
        //  $date = get_date_now();

        $line_no_add = 1000;
        $line_no = 1000;
        foreach ($data as $row) {
            $this->Model_purchase_d->doc_no = $doc_no;
            $this->Model_purchase_d->line_no = $line_no;
            $this->Model_purchase_d->item_code = $row['item_code'];
            $this->Model_purchase_d->description = $row['description'];
            $this->Model_purchase_d->qty = $row['qty'];
            $this->Model_purchase_d->uom = $row['uom'];
            $this->Model_purchase_d->src_loc = $row['src_loc'];
            $this->Model_purchase_d->request_img = $row['request_img'];
            $this->Model_purchase_d->request_link = $row['request_link'];
            $this->Model_purchase_d->remarks = $row['remarks'];
            $this->Model_purchase_d->src_creation_datetime = $datetime;
            $result = $this->Model_purchase_d->insert_d();
            $line_no += $line_no_add;
        }
    }
}
?>