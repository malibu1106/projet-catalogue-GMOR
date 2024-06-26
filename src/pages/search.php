<?php
    if(!isset($_GET["search"])) {
        header("Location: index.php");
        exit;
    }

    $search = "%".trim($_GET["search"])."%";
    
    search = $db->prepere("SELECT * FROM products WHERE search LIKE :search ");
    `id`
 `ref`
`brand`
`size` 
`color`
 `pattern`
`material`
 `gender
`stock`
 `price`
 `discount`
 `category`
 `content`
`image_1`
`image_2`
`image_3`
`image_4`

?>