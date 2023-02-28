<?php

class Global_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    /*********start comman function for all*******************/
    public function check_ShopExist($shop = NULL)
    {
        $query = $this->db->query("SELECT * FROM `shopify_stores` where  shop='" . $shop . "'");
        $rows  = $query->num_rows();
        if ($rows > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function get_shop_details($shop = NULL)
    {
        $shop_details = $this->db->select('charge_id')->where('shop', $shop)->get('shopify_stores');
        if ($shop_details->num_rows() > 0) {
            return $shop_details->row();
        } else {
            return false;
        }
    }

    public function UpdateShopDetails($where = array(), $data = array())
    {
        $this->db->where($where)->update('shopify_stores', $data);
        return $this->db->affected_rows();
    }
    public function InsertShopDetails($data = array())
    {
        $this->db->insert('plans',$data);
    }

    public function Update_row($data=array(),$where=array(),$table)
    {
        $this->db->where($where)->update($table,$data);
    }

    public function update_Shop($data, $accessToken)
    {
        if ($accessToken) {
            $sql = "update  shopify_stores set code='" . $data['code'] . "', hmac='" . $data['code'] . "', token='" . $accessToken . "' where  shop='" . $data['shop'] . "' ";
            $this->db->query($sql);
        }
    }
    public function add_newShop($data, $accessToken)
    {
        $sql = "insert into shopify_stores set code='" . $data['code'] . "', hmac='" . $data['code'] . "', domain='" . $data['shop'] . "',shop='" . $data['shop'] . "', token='" . $accessToken . "' ";
        $this->db->query($sql);
    }

    public function update_shop_accessToken($shop, $accessToken)
    {
        $sql = "update shopify_stores set token='" . $accessToken . "' where shop='" . $shop . "'";
        $this->db->query($sql);

    }

    public function checkStorActive($cartData)
    {
        $sql   = "select * from shopify_stores where shop='" . $cartData['shopify_domain'] . "'";
        $query = $this->db->query($sql);
        return $query->row();
    }

     public function GetSingleDataRecord($data)
    {
        $this->db->select($data['fields']);
        $this->db->from($data['table']);
        if(isset($data['search'])){
          $this->db->where($data['search']);
        }

        if(isset($data['order'])){
          $this->db->order_by('id', 'desc');
            // $this->db->order_by($data['title'], $data['order']);
        }
        else{
            $this->db->order_by('id', 'desc');
        }
        $this->db->limit($data['offset'],$data['myll']);
        $query = $this->db->get();

        $row = $query->result();

        $this->db->select($data['fields']);
        $this->db->from($data['table']);
        $this->db->where('shop_id', $data['shop_id']);
        if(isset($data['search'])){
        $this->db->where($data['search']);
        }
        $count = $this->db->count_all_results();
        $row['count'] = $count;

        return $row;

    }

    public function getRow($where=array(),$table='')
    {
        return $this->db->select('*')->where($where)->get($table)->row();
    }

    public function insert_data($table,$data)
    {
        $this->db->insert($table,$data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }

    public function check_product($title='', $shop_id) {
        return $this->db->select('title')->where('title',$title)->where('shop_id',$shop_id)->get('products')->num_rows();
    }

    public function update_data($where=array(),$table='',$data=array())
    {
        $this->db->where($where)->update($table,$data);
        $a = $this->db->affected_rows();
        return $a;
    }

    public function delete_row($table='',$where = array())
    {
        $this->db->where($where)->delete($table);
        $this->db->affected_rows();
    }


    public function get_categories(){

        $this->db->select('*');
        $this->db->from('categories');
        $this->db->where('parent_cat', 0);

        $parent = $this->db->get();

        $categories = $parent->result();
        $i=0;
        foreach($categories as $p_cat){

            $categories[$i]->sub = $this->sub_categories($p_cat->id);
            $i++;
        }
        return $categories;
    }

    public function sub_categories($id){

        $this->db->select('*');
        $this->db->from('categories');
        $this->db->where('parent_cat', $id);

        $child = $this->db->get();
        $categories = $child->result();
        $i=0;
        foreach($categories as $p_cat){

            $categories[$i]->sub = $this->sub_categories($p_cat->id);
            $i++;
        }
        return $categories;
    }

    public function if_exists($where = array(), $table='')
    {
        return $this->db->select('*')->where($where)->get($table)->num_rows();
    }

     public function add_category($data)
    {
        $this->db->insert('categories',$data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }

    public function update_category($data)
    {
        $update_data = array();
        foreach ($data as $key => $value) {
            if ($key == 'cat_id' || $key == 'shop_id') continue;
            if (isset($data[$key])) $update_data[$key] = $value;
        }

        $this->db->where('id',$data['cat_id'])->update('categories',$update_data);
        return $this->db->affected_rows();
    }


    public function get_category_by_level($level = 0, $shop_id) {
      $categories = $this->db->select('*')
                        ->where('parent_cat', $level)
                        ->where('shop_id', $shop_id)
                        ->order_by('id','asc')
                        ->get('categories')
                        ->result();
      return $categories;
    }

    public function get_category_by_id($cat_id){
        return   $this->db
                 ->select('*')
                 ->where('id', $cat_id)
                 ->get('categories')
                 ->row();
    }

    public function get_all_categories($shop_id = '')
    {
       return   $this->db
                ->select('*')
                ->where('shop_id', $shop_id)
                ->order_by('id','asc')
                ->get('categories')
                ->result();
    }

    public function get_cliparts($limit = 20, $offset = 0, $where = array()) {
      return $this->db->select('*')->where($where)->get('clip_arts', $limit, $offset)->result();
    }

    public function get_num_rows($table='',$where=array())
    {
        return $this->db->select('*')->where($where)->get($table)->num_rows();
    }

    public function search_clipart($data)
    {
        $per_page = $data["show_per_page"];
        $search_query = $data['search_query'];
        $offset = $data['offset'];
        $shop_id = $data['shop_id'];

        $query = "SELECT *
                  FROM clip_arts
                  WHERE (clip_art LIKE '%{$search_query}%' AND shop_id = '{$shop_id}')
                  OR cat_id IN (
                    SELECT DISTINCT id
                    FROM categories
                    WHERE cat_name LIKE '%{$search_query}%'
                    AND shop_id = '{$shop_id}')
                  ORDER BY clip_art DESC
                  LIMIT {$per_page} OFFSET {$offset}";
        $result = $this->db->query($query)->result();
        return $result;
    }

    public function get_rows_count($data)
    {
        $per_page = $data["show_per_page"];
        $search_query = $data['search_query'];
        $shop_id = $data['shop_id'];

        $query = "SELECT *
                  FROM clip_arts
                  WHERE (clip_art LIKE '%{$search_query}%' AND shop_id = '{$shop_id}')
                  OR cat_id IN (
                    SELECT DISTINCT id
                    FROM categories
                    WHERE cat_name LIKE '%{$search_query}%'
                    AND shop_id = '{$shop_id}')
                  ORDER BY clip_art DESC";
        return $this->db->query($query)->num_rows();
    }
    public function check_pid_exist($product_id,$shop_id)
    {
      $data = $this->db->select('*')
                       ->where('product_id',$product_id)
                       ->where('shop_id',$shop_id)
                       ->from('products')
                       ->get()->result();
      return $data;
    }
    public function InsertSyncProduct($table,$data=array())
    {
      $data = $this->db->insert($table,$data);
      return $data;
    }
    public function asyncProduct($product_id,$shop_id)
    {
      $data = $this->db->where('product_id',$product_id)
                       ->where('shop_id',$shop_id)
                       ->delete('products');
      return $data;
    }
}
?>
