<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/MY_REST_Controller.php';
require  'vendor/autoload.php';

class Product extends MY_REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('products_model');
    }

    public function index_get()
    {
        $data = $this->products_model->get_products();
        return $this->set_response(
            $data,
            'Show products success',
			REST_Controller::HTTP_OK
		);
    }

    public function single_get($id)
    {
		$data = $this->products_model->get_products($id);
		$msg = $data == null ? "Product not found" : 'Show product success';
        return $this->set_response(
            $data,
            $msg,
			REST_Controller::HTTP_OK
		);
    }

    public function index_post()
    {
        $this->load->library('form_validation');

        // Set validations
        $this->form_validation->set_rules('name', 'name', 'required|trim|min_length[5]|max_length[100]');
        $this->form_validation->set_rules('description', 'description', 'required|trim|min_length[5]|max_length[255]');
        $this->form_validation->set_rules('price', 'price', 'required|is_natural');

        // Set data to validate
        $this->form_validation->set_data($this->post());
		
		// Run Validations
		if ($this->form_validation->run() == FALSE) {
			return $this->set_response(
				array(),
				$this->lang->line('text_invalid_params'),
				REST_Controller::HTTP_BAD_REQUEST
			);
        }

        $product_id = $this->products_model->create_products($this->post());

        if(!$product_id){
			return $this->set_response(
				array(),
				$this->lang->line('text_server_error'),
				REST_Controller::HTTP_INTERNAL_SERVER_ERROR
			);
		}
			
		return $this->set_response(
			array(),
			'Create product success',
			REST_Controller::HTTP_CREATED
		);
    }

    public function single_put($id)
    {
		if ($this->products_model->get_products($id) == null) {
			return $this->set_response(
				$data,
				"Product not found",
				REST_Controller::HTTP_OK
			);
		}

        $this->load->library('form_validation');

        // Set validations
        $this->form_validation->set_rules('name', 'name', 'required|trim|min_length[5]|max_length[100]');
        $this->form_validation->set_rules('description', 'description', 'required|trim|min_length[5]|max_length[255]');
        $this->form_validation->set_rules('price', 'price', 'required|is_natural');

        // Set data to validate
        $this->form_validation->set_data($this->put());
		
		// Run Validations
		if ($this->form_validation->run() == FALSE) {
			return $this->set_response(
				array(),
				$this->lang->line('text_invalid_params'),
				REST_Controller::HTTP_BAD_REQUEST
			);
        }

        $product_id = $this->products_model->update_products($id, $this->put());

        if(!$product_id){
			return $this->set_response(
				array(),
				$this->lang->line('text_server_error'),
				REST_Controller::HTTP_INTERNAL_SERVER_ERROR
			);
		}
			
		return $this->set_response(
			array(),
			'Update product success',
			REST_Controller::HTTP_NO_CONTENT
		);
    }

    public function single_delete($id)
    {
		if ($this->products_model->get_products($id) == null) {
			return $this->set_response(
				$data,
				"Product not found",
				REST_Controller::HTTP_OK
			);
		}

        $product_id = $this->products_model->delete_products($id);

        if(!$product_id){
			return $this->set_response(
				array(),
				$this->lang->line('text_server_error'),
				REST_Controller::HTTP_INTERNAL_SERVER_ERROR
			);
		}
			
		return $this->set_response(
			array(),
			'Delete product success',
			REST_Controller::HTTP_NO_CONTENT
		);
    }
}