<?php
include('includes/init.php');
if(isset($_POST['api_key']) || true){
    $api_key = $mysql->real_escape_string($_POST['api_key']);
    $query = $mysql->query("SELECT * FROM api_keys WHERE pass='$api_key'");

    if($query->num_rows!=0 || true){
        $data = $_POST['data'];

        $query = $mysql->query("SELECT * FROM settings WHERE id='1'");
        $settings_data = $query->fetch_assoc();

        $end_time = $settings_data['end_time'];
        $ready_time = $settings_data['ready_time'];
        $dying_time = $settings_data['dying_time'];

        $current_time = time();

        $query = $mysql->query("SELECT * FROM locations WHERE id='1'");
        $location_data = $query->fetch_assoc();

        $current_tobacco = $location_data['tobacco_type'];

        switch($data){
            case "yes":
            {
                echo "asd";
                $query = $mysql->query("SELECT * FROM pipes WHERE location_id='1'");

                $pipe_active = false;

                while($row = $query->fetch_assoc()){
                    $pipe_time = strtotime($row['created']);

                    if($pipe_time + $end_time > $current_time){
                        $pipe_active = $row['id'];
                    }
                }

                if($pipe_active != false){
                    $query = $mysql->query("SELECT * FROM pipes WHERE id='$pipe_active'");
                    $pipe_data = $query->fetch_assoc();

                    $new_created = $current_time - $ready_time;

                    $new_created = date("Y-m-d H:i:s",$new_created);

                    $mysql->query("UPDATE pipes SET created='$new_created' WHERE id='$pipe_active'");
                }else{
                    $new_created = $current_time - $ready_time;

                    $new_created = date("Y-m-d H:i:s",$new_created);

                    $mysql->query("INSERT INTO pipes (type,created,created_by,location_id,created_static) VALUES ('$current_tobacco','$new_created','1020','1',NOW())");
                }
            }
                break;

            case "maybe":
            {
                $query = $mysql->query("SELECT * FROM pipes WHERE location_id='1'");

                $pipe_active = false;

                while($row = $query->fetch_assoc()){
                    $pipe_time = strtotime($row['created']);

                    if($pipe_time + $end_time > $current_time){
                        $pipe_active = $row['id'];
                    }
                }

                var_dump($pipe_active);

                if($pipe_active != false){
                    $query = $mysql->query("SELECT * FROM pipes WHERE id='$pipe_active'");
                    $pipe_data = $query->fetch_assoc();

                    $new_created = $current_time - $dying_time;

                    $new_created = date("Y-m-d H:i:s",$new_created);

                    $mysql->query("UPDATE pipes SET created='$new_created' WHERE id='$pipe_active'");
                }else{
                    $mysql->query("INSERT INTO pipes (type,created,created_by,location_id,created_static) VALUES ('$current_tobacco',NOW(),'1020','1',NOW())");
                }
            }
                break;

            case "no":
            {
                $query = $mysql->query("SELECT * FROM pipes WHERE location_id='1'");

                $pipe_active = false;

                while($row = $query->fetch_assoc()){
                    $pipe_time = strtotime($row['created']);

                    if($pipe_time + $end_time > $current_time){
                        $pipe_active = $row['id'];
                    }
                }

                if($pipe_active != false){
                    $new_time = $current_time - $end_time;

                    $new_time = date("Y-m-d H:i:s",$new_time);

                    $query = $mysql->query("UPDATE pipes SET created='$new_time' WHERE id='$pipe_active'");

                }
            }
                break;
        }
    }
}
