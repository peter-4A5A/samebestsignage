<?php
/**
 * Created by PhpStorm.
 * User: Jordi
 * Date: 6-2-2018
 * Time: 13:41
 */

class Templates extends CI_Model
{
    public function get_all_entries(){
        $query = $this->db->query('SELECT * FROM mail_templates');
        return $query->result_array();
    }

    public function get_single_entry($id){
        $query = $this->db->query('SELECT * FROM mail_templates WHERE id = '.$id);
        return $query->row_array();
    }

    public function insert_entry($sub, $con){
        $query = $this->db->query('
          INSERT INTO mail_templates ( subject, content ) 
            VALUES ('.$this->db->escape($sub).',
             '.$this->db->escape($con).')');
        return $query;
    }

    public function update_entry($id, $key, $item){
        $query = $this->db->query('UPDATE mail_templates SET ' . $key . ' = '.$this->db->escape($item).' WHERE id = '.$id);
        return $query;
    }
}