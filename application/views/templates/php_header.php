<?php

$session_data = $this->session->userdata('z_tpimx_logged_in');
if(!isset($session_data['z_tpimx_user_id'])){
    header("Location:".base_url());
}

?>
