<?php

class Api_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
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


    public function get_category_by_level($shop,$level=0)
    {
       return   $this->db
                ->select('*')
                ->where(array('parent_cat'=> $level, 'shop_id'=>shop_id($shop)))
                ->order_by('id','asc')
                ->get('categories')
                ->result();
    }

    public function get_category_by_id($cat_id){
        return   $this->db
                 ->select('*')
                 ->where('id', $cat_id)
                 ->get('categories')
                 ->row();
    }

    public function get_all_categories()
    {
       return   $this->db
                ->select('*')
                ->order_by('id','asc')
                ->get('categories')
                ->result();
    }

    public function get_cliparts($limit = 20 ,$offset = 0,$where=array())
    {
        return $this->db->select('*')->where($where)->get('clip_arts', $limit, $offset)->result();
    }

    public function get_cliparts_by($cat_id)
    {
        return $this->db->select('*')->where(array('cat_id'=>$cat_id))->get('clip_arts')->result();
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
                  WHERE clip_art LIKE '%{$search_query}%'
                  OR cat_id IN (
                    SELECT DISTINCT id
                    FROM categories
                    WHERE cat_name LIKE '%{$search_query}%'
                    AND shop_id = '{$shop_id}')
                  ORDER BY clip_art DESC
                  LIMIT {$per_page} OFFSET {$offset}";
        return $this->db->query($query)->result();
    }

    public function get_rows_count($data)
    {
        $per_page = $data["show_per_page"];
        $search_query = $data['search_query'];
        $shop_id = $data['shop_id'];

        $query = "SELECT *
                  FROM clip_arts
                  WHERE clip_art LIKE '%{$search_query}%'
                  OR cat_id IN (
                    SELECT DISTINCT id
                    FROM categories
                    WHERE cat_name LIKE '%{$search_query}%'
                    AND shop_id = '{$shop_id}')
                  ORDER BY clip_art DESC";
        return $this->db->query($query)->num_rows();
    }

    public function get_product($shop)
    {
        $query =  $this->db->select('*')->where('shop_id',shop_id($shop))->get('products')->row();
        return $query;
    }

    public function get_product_by_id($shop,$product_id)
    {
        return $this->db->select('*')->where(array('shop_id'=>shop_id($shop),'product_id'=>$product_id))->get('products')->row();
    }

    public function get_all_products($shop) {
        return $product = $this->db->select('*')->where('shop_id',shop_id($shop))->get('products')->result();
    }

    public function CheckUserExistance($email,$customer_id){
        $check_array =array(
            "customer_id"=>$customer_id,
            "customer_email"=>$email,
        );
         $query =  $this->db->select('*')->where($check_array)->get('users');
         if($query->num_rows() > 0){
            $insert_id = $query->row()->id;
         }else{
            $this->db->insert('users',$check_array);
            $insert_id = $this->db->insert_id();
         }
         return $insert_id;
    }
    public function InsertUserImages($customer_id,$file_name){
        $check_array =array(
            "customer_id"=>$customer_id,
            "image"=>$file_name,
        );
        $this->db->insert('user_images',$check_array);
    }

    public function GetUserImages($user_id){
        $check_array =array(
            "customer_id"=>$user_id,
        );
        $result = array();
        $query =  $this->db->select('*')->where($check_array)->get('user_images');
         if($query->num_rows() > 0){
            $result = $query->result();
         }
         return $result;
    }
}
?>
