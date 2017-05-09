<?php


function getListBrand()
{
    $sql = "SELECT `id`, `brand_name` FROM `brand` ORDER BY `id` DESC";
    return sql_select($sql);
}

function getListProduct($idBrand)
{
    $idBrand = (int)$idBrand;
    $sql = "SELECT * FROM `products` WHERE `id_brand` = '" . $idBrand . "'";
    return sql_select($sql);
}

function product_sort($idBrand, $typeSort, $sort)
{  
    $sql = "";
    if($sort == "price") {
        if($typeSort === "ASC") {
            $sql = "SELECT * FROM `products` WHERE `id_brand` = '$idBrand' ORDER BY `price` ASC";          
        } else {
            $sql = "SELECT * FROM `products` WHERE `id_brand` = '$idBrand' ORDER BY `price` DESC";         
        } 
    } else if($sort == "name") {
        if($typeSort === "ASC") {
            $sql = "SELECT * FROM `products` WHERE `id_brand` = '$idBrand' ORDER BY `product_name` ASC";   
        } else {
            $sql = "SELECT * FROM `products` WHERE `id_brand` = '$idBrand' ORDER BY `product_name` DESC";  
        } 
    }
    return sql_select($sql);
           
    
} 

function brand_remove($id)
{
    $id = (int)$id;
    $sqlBrandRemove = "DELETE FROM `brand` WHERE `id` = '" . $id . "'";
    if(sql_query($sqlBrandRemove)) {
        $sqlImageLink = "SELECT `image_link` FROM `products` WHERE `id_brand` = '" . $id . "'";
        $arrayLink = sql_select($sqlImageLink);        
        if(count($arrayLink) !== 0) {
            for ($i=0; $i < count($arrayLink); $i++) { 
                $currentPath = $arrayLink[$i]["image_link"];                
                unlink($currentPath);            
            }
        }        
        $sqlProductRemove = "DELETE FROM `products` WHERE `id_brand` = '" . $id . "'";
        return sql_query($sqlProductRemove);
    }
}

function brand_add($name) {    
    $sql = "INSERT INTO `brand` (`brand_name`) VALUES ('%s')";
    $query = sprintf($sql, sql_escape($name));   
    if(sql_query($query)) {
        return mysqli_insert_id(sql_connect());
    } else {
        return false;
    }    
}

function product_add($name, $description, $price,$image_link,$idBrand) { 
    $idBrand = (int)$idBrand;
    $price = (float)$price;   
    $sql = "INSERT INTO `products` (`id_brand`,`product_name`,`description`,`price`,`image_link`) VALUES ('%s','%s','%s','%s','%s')";
    $query = sprintf($sql, $idBrand, sql_escape($name), sql_escape($description), $price, $image_link);   
    if(sql_query($query)) {
        return mysqli_insert_id(sql_connect());
    } else {
        return false;
    }    
}

function product_remove($id, $image_link)
{
    $id = (int)$id;
    $sql = "DELETE FROM `products` WHERE `id` = '" . $id . "'";
    if(unlink($image_link)) {
        return sql_query($sql);
    } else {
        return false;
    }   
} 

function product_edit($id, $name, $description, $price, $newPhotoLink, $curPhotoLink) {
    $id = (int)$id;
    $price = (float)$price;
    if(empty($newPhotoLink)) {
        $sql = "UPDATE `products` SET `product_name`='%s', `description`='%s', `price`='%s' WHERE  `id`='%s'";
        $query = sprintf($sql, sql_escape($name), sql_escape($description), $price, $id);
        return sql_query($query);
    } else {
        $sql = "UPDATE `products` SET `product_name`='%s', `description`='%s', `price`='%s', `image_link`='%s' WHERE  `id`='%s'";
        $query = sprintf($sql, sql_escape($name), sql_escape($description), $price, $newPhotoLink, $id);
        if(unlink($curPhotoLink)) {
            return sql_query($query);
        } else {
            return false;
        }        
    }        
}

function brand_update($id, $name)
{
    $id = (int)$id;
    $sql = "UPDATE `brand` SET `brand_name`='%s' WHERE  `id`='%s'";
    $query = sprintf($sql, sql_escape($name), $id);
    return sql_query($query);
}