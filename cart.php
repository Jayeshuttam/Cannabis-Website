<?php

require_once 'db_pdo.php';
require_once 'catalogue.php';
$DB = new DB_PDO();
 $cart_array=[];
class Cart{
    
public function  displayCart()
{
    global $cart_array;
    $wep_page=new Webpage();
    $wep_page->title='Your Cart';
    $wep_page->extraCss = 'css\cart.css';
    $wep_page->content='';
    $wep_page->content='<h1>My Cart</h1>';
   $wep_page->content.='<div style="background-color: rgb(252, 248, 244); width:100%;">';
   print(isset($_SESSION['cart']));
    if(isset($_SESSION['cart']))
    {
        $cart_array=$_SESSION['cart']; 
        $wep_page->content.=$this->arrayToCart($cart_array);
    }
    else
    {
        $wep_page->content.='Cart is empty';
    }
    $wep_page->content.='</div>';
    $btn_disable='';
    if(!isset($_SESSION['cart']))
        $btn_disable='disabled';
    $wep_page->content.='<form action="./index.php?op=126" method="post" class="checkout-form">
    <input type="submit" value="Checkout" class="checkout-btn"'.$btn_disable.' >
    </form>';
    $wep_page->render();
}
public function addToCart()
{
    global $DB;
    global $cart_array;
    if(isset($_SESSION['user_name'])){
        $product_id=$_POST['id'];
        $product=$DB->querySelect('select * from products where id='.$product_id);
        if(isset($_SESSION['cart']))
        {
            $cart_array=$_SESSION['cart'];
        }
        array_push($cart_array,$product[0]);
    $_SESSION['cart']=$cart_array;
    $product = new Catalogue();
    $product->displayProductDetail("Product added to cart successfully",$_POST);
    }
    else
    {
        $webpage=new Users();
        $webpage->LoginPage("You need to login into your Account");
    }
    

    
}
public function arrayToCart($array)
    {
        $output = '';
        $output = '<br>';
        
        foreach ($array as $item) {
            $output.='<div class="main-wrapper">
            <img src="./img/'.$item['image'].'" alt="img">
            <div class="product-block">
                <h3>'.$item['name'].'</h3>
                <p>'.$item['category'].'</p>
            </div>
            <div class="product-block">
                <p>Quantity :1</p>
            </div>
            <div class="product-block">
                <p>Price:'.$item['price'].'</p>
            </div>
    
        </div>';
        }
       

        return $output;
    }
    public function checkout()
    {
        global $cart_array;
        global $DB;
        
        $date=date("Y/m/d");
        $user_id=$_SESSION['user_id'];
        $cart_array=$_SESSION['cart'];
        foreach($cart_array as $item)
        {
            print_r("insert into orders (user_id,product_id,created_at,order_qty) values(".$user_id.",".$item['id'].",".$date.",1)");
            $DB->queryInsert("insert into orders (user_id,product_id,created_at,order_qty) values(".$user_id.",".$item['id'].",'".$date."',1)");
        }
        unset($_SESSION['cart']);
        header("Location:./index.php?op=125");
    }
   

}