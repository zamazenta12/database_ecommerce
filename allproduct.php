<?php
    include_once 'dbconnect.php';
    $stat = $conn -> prepare ("SELECT id, name, price, promo, description, images, stock, vendors, category FROM product_items;");
    
    if (!$stat) {
        die(json_encode(array("error" => "Prepare failed: " . $conn->error)));
    }

    if (!$stat -> execute()) {
        die(json_encode(array("error" => "Execute failed: " . $stat->error)));
    }
    $stat -> bind_result($id, $name, $price, $promo, $description, $images, $stock, $vendors, $category);
    $arrayproduct = array();

    while ($stat -> fetch()){
        $data = array();
        $data['id'] = $id;
        $data['name'] = $name;
        $data['price'] = $price;
        $data['promo'] = $promo;
        $data['description'] = $description;
        $data['images'] = $images;
        $data['stock'] = $stock;
        $data['vendors'] = $vendors;
        $data['category'] = $category;

        array_push($arrayproduct, $data);
    }
    
    $json_output = json_encode($arrayproduct);
    
    if ($json_output === false) {
        die(json_encode(array(
            "error" => "JSON Encode Failed", 
            "message" => json_last_error_msg(),
            "count" => count($arrayproduct)
        )));
    }
    
    echo $json_output;
?>