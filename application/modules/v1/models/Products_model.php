<?php
class Products_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
        //Table Name
		$this->table_name = "products";
    }

    public function get_products($id = false)
    {
        if($id === false){
            $query = $this->db->get($this->table_name);
            return $query->result_array();
        }

        $query = $this->db->get_where($this->table_name, array('id' => $id));
        return $query->row_array();
    }

    public function create_products($product_data = array())
    {
        // Saving the product
		$query = $this->db->insert($this->table_name, $product_data);
		
		if(!$query){
			return false;
		}

		return true;
    }

    public function update_products($id, $product_data = array())
    {
        $query = $this->db->update($this->table_name, $product_data, array('id' => $id));

        if(!$query){
			return false;
		}

		return true;
    }

    public function delete_products($id)
    {
        $query = $this->db->delete($this->table_name, array('id' => $id));

        if(!$query){
			return false;
		}

		return true;
    }
}