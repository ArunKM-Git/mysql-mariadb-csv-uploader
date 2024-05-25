<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

$data = array(
    'status' => false,
    'msg' => null
);

try {
    $obj = json_decode(file_get_contents('php://input'), true);

    $server_name = "localhost";
    $user_name = "medicine_billing_admin";
    $password = "]N.%@@0Q)!%8";
    $database = "medicine_billing";
    $table_name = "product";

    $conn = new mysqli($server_name, $user_name, $password, $database);

    if ($conn->connect_error) {
        throw new Exception("Database connection failed" . $conn->connect_error);
    }

    foreach ($obj as $key => $value) {
        $key_arr[] = $conn->real_escape_string($key);
        $value_arr[] = $conn->real_escape_string($value);
    }

    $all_key = implode("`,`", $key_arr);
    $all_value = implode("','", $value_arr);

    $query = "INSERT 
    INTO `$table_name`
    (`$all_key`) 
    VALUES 
    ('$all_value')";

    $run = $conn->query($query);
    if ($run) {
        $data['status'] = true;
        $id = $conn->insert_id;
        $data['msg'] = "$id inserted successfully";
    } else {
        $data['msg'] = "Data insert failed";
    }

    $conn->close();
} catch (Exception $e) {
    $data['msg'] = '<span style="color:red;">'.$e->getMessage(). "<br>Problem - $conn->error(). <br>SQL query - ($query)".'</span>';
}

echo json_encode($data);
