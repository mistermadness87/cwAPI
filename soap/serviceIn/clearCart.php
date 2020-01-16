<?php 
/* очистка корзины */
//регистрация метода
$server->register("clearCart",
    array("response" => "tns:reqCart"),
    array("return" => "tns:resp"),
    "urn:API",
    "urn:API#clearCart",
    "rpc",
    "encoded",
    "clear cart"
);
?>