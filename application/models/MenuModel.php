<?php

class MenuModel extends CI_Model {

    public function createMenu($MenuName, $isTopMenu){
        $sql = 'INSERT INTO menu (men_name, men_is_top_menu) VALUES (?, ?)';
        return $this->db->query($sql, array($MenuName, $isTopMenu));
    }

    public function updateMenuName($menId, $menName){
        $sql = 'UPDATE menu SET men_name = ? WHERE men_id = ?';
        return $this->db->query($sql, array($menName, $menId));
    }

    public function disableOldTopMenu($newMenuId){
        $sql = 'UPDATE menu SET men_is_top_menu = ? WHERE men_id <> ?';
        return $this->db->query($sql, array(0, $newMenuId));
    }

    public function getAllMenus(){
        $sql = "SELECT * FROM menu";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function createMenuCategoryLink($menId, $catId){
        $sql = 'INSERT INTO menu_category_link (men_id, cat_id) VALUES (?, ?)';
        return $this->db->query($sql, array($menId, $catId));
    }

    public function getMenuById($menId){
        $sql = "SELECT * FROM menu WHERE men_id = ? ";
        $query = $this->db->query($sql, array($menId));
        return $query->row_array();
    }

    public function getTopMenu(){
        $sql = "SELECT * FROM menu WHERE men_is_top_menu = ? ";
        $query = $this->db->query($sql, array(1));
        return $query->row_array();
    }

    public function getMenuCategories($menId){
        $sql = "SELECT * FROM menu_category_link 
                LEFT JOIN category ON menu_category_link.cat_id = category.cat_id 
                WHERE menu_category_link.men_id = ? ORDER BY menu_category_link.mcl_index, category.cat_name";
        $query = $this->db->query($sql, array($menId));
        return $query->result_array();
    }

    public function setMenuAsMainMenu($menId){
        $sql = 'UPDATE menu SET men_is_top_menu = ? WHERE men_id = ?';
        return $this->db->query($sql, array(1, $menId));
    }

    public function updateCategoryMenuIndex($menId, $catId, $mclIndex){
        $sql = 'UPDATE menu_category_link SET mcl_index = ? WHERE men_id = ? and cat_id = ?';
        return $this->db->query($sql, array($mclIndex, $menId, $catId));
    }

    public function deleteMenuCategoryLinkByMenuId($menId){
        $sql = 'DELETE FROM menu_category_link WHERE men_id = ?';
        return $this->db->query($sql, array($menId));
    }

    public function deleteMenu($menId){
        $sql = 'DELETE FROM menu WHERE men_id = ?';
        return $this->db->query($sql, array($menId));
    }

    public function getMenusByCategoryId($catId){
        $sql = "SELECT * FROM menu 
                LEFT JOIN menu_category_link ON menu.men_id = menu_category_link.men_id 
                WHERE menu_category_link.cat_id = ?";
        $query = $this->db->query($sql, array($catId));
        return $query->result_array();
    }

    public function deleteMenuCategoryLinkByCategoryId($catId){
        $sql = 'DELETE FROM menu_category_link WHERE cat_id = ?';
        return $this->db->query($sql, array($catId));
    }
}
