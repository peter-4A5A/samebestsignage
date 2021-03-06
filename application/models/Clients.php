<?php
/**
 * Created by PhpStorm.
 * User: Jordi
 * Date: 6-2-2018
 * Time: 13:16
 */

class Clients extends CI_Model
{
    public function get_all_entries(){
        $query = $this->db->query('SELECT client_id, client_name, client_email FROM clients ');
        return $query->result_array();
    }

    public function get_all_entries_active(){
        $query = $this->db->query('SELECT client_id, client_name, client_email FROM clients WHERE client_active = 1 ');
        return $query->result_array();
    }

    public function get_all_entries_full(){
        $query = $this->db->query('SELECT * FROM clients ');
        return $query->result_array();
    }

    public function get_single_entry($id){
        $query = $this->db->query('SELECT * FROM clients WHERE client_id = '.$this->db->escape($id));
        return $query->row_array();
    }

    public function get_single_entry_mail($id){
        $query = $this->db->query('SELECT client_email FROM clients WHERE client_id = '.$this->db->escape($id));
        return $query->row_array();
    }

    public function insert_entry($nam, $tel , $ema, $cou, $sta, $tow, $str, $num, $zip){
        $query = $this->db->query('
          INSERT INTO clients ( client_name, client_tel, client_email, client_country, client_state, client_city, client_street, client_street_number, client_zipcode ) 
            VALUES (
            '.$this->db->escape($nam).',
            '.$this->db->escape($tel).',
            '.$this->db->escape($ema).',
            '.$this->db->escape($cou).',
            '.$this->db->escape($sta).',
            '.$this->db->escape($tow).',
            '.$this->db->escape($str).',
            '.$this->db->escape($num).',
            '.$this->db->escape($zip).'
            )
            ');

        if($query){
            $this->logs->insert_entry("INSERT", "Client created", ($this->session->userdata('DX_user_id') != null)? $this->session->userdata('DX_user_id') : $this->input->ip_address());
        }

        return $query;
    }

    public function update_entry($id, $nam, $tel , $ema, $cou, $sta, $tow, $str, $num, $zip){
        $query = $this->db->query('
          UPDATE clients SET 
           client_name = '.$this->db->escape($nam).',
           client_tel = '.$this->db->escape($tel).', 
           client_email = '.$this->db->escape($ema).',
           client_country = '.$this->db->escape($cou).', 
           client_state = '.$this->db->escape($sta).', 
           client_city = '.$this->db->escape($tow).', 
           client_street = '.$this->db->escape($str).', 
           client_street_number = '.$this->db->escape($num).', 
           client_zipcode = '.$this->db->escape($zip).'
          WHERE client_id =  '. $id );

        if($query){
            $this->logs->insert_entry("UPDATE", "Client updated", ($this->session->userdata('DX_user_id') != null)? $this->session->userdata('DX_user_id') : $this->input->ip_address());
        }

        return $query;
    }



    public function toggle_client($id){
        $client = $this->get_single_entry($id);

        $bool = $client['client_active'];

        switch ($bool) {
            case 0:
                $bool = 1;
                $msg = 'on';
                break;
            case 1:
                $bool = 0;
                $msg = 'off';
                break;
        }

        $query = $this->db->query('UPDATE clients
            SET 
             client_active = '.$bool.'
            WHERE client_id = '.$this->db->escape($id));

        if($query){
            $this->logs->insert_entry("UPDATE", "Client no.".$id." is turned ". $msg, ($this->session->userdata('DX_user_id') != null)? $this->session->userdata('DX_user_id') : $this->input->ip_address());
            return $msg;
        } else{
            return false;
        }
    }
}