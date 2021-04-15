<?php

require_once 'db_pdo.php';
$DB = new DB_PDO();
class Catalogue
{
    public function displayCatalogue()
    {
        $displayCatalogue = new Webpage();
        $displayCatalogue->title = 'Catalogue';
        $signupPage->activeMenu = 'catalogue';
        $displayCatalogue->extraCss = 'css\catalog.css';
        $displayCatalogue->content = <<<HTML
        <div class="wrapper">
            
            <div class="section-contact">
            
                <!-- sets the entire page background -->
                <ul class="products">
                    <li><img src="./img/be_alert_pen_1.png" alt="be alert pen" />
                        <span class="price"><span class="currency-symbol">$</span>80</span>
                        <h4><a href="./product.php">Be Alert Vape Pen</a></h4>
                        <span class="product-type">CBD Luxe</span>
                    </li>
                    <li><img src="./img/be_alert_tincture.png" alt="Be Alert Tincture" />
                        <span class="price"><span class="currency-symbol">$</span>75</span>
                        <h4><a href="./product.php">Be Alert Tincture</a></h4>
                        <span class="product-type">Pure Ratios</span>
                    </li>
                    <li><img src="./img/unwind_1.png" alt="Unwind Hemp Extract" />
                        <span class="price"><span class="currency-symbol">$</span>45</span>
                        <h4><a href="./product.php">Unwind Hemp Extract</a></h4>
                        <span class="product-type">Altru Fuel</span>
                    </li>
                    <li><img src="./img/cali-daze-kickback_1.jpeg" alt="OG Cali Daze" />
                        <span class="price"><span class="currency-symbol">$</span>26</span>
                        <h4><a href="./product.php">OG Cali Daze</a></h4>
                        <span class="product-type">Quanta</span>
                    </li>
                    <li><img src="./img/raleigh-k.jpg" alt="Nooks + Crannies - Cucumber Melon" />
                        <span class="price"><span class="currency-symbol">$</span>22</span>
                        <h4><a href="./product.php">Nooks + Crannies - Cucumber Melon</a></h4>
                        <span class="product-type">Kiskanu</span>
                    </li>
                    <li><img src="./img/wild-crafted-cbd-oil.jpeg" alt="Wild Crafted CBD Skin Oil" />
                        <span class="price"><span class="currency-symbol">$</span>35</span>
                        <h4><a href="./product.php">Wild Crafted CBD Skin Oil</a></h4>
                        <span class="product-type">LEEF Organics</span>
                    </li>
                    <!-- add more list items -->
                </ul>
            </div>
        </div>
        HTML;
        $displayCatalogue->render();
    }

    public function displayAllProducts($message='',$prev_values=[])
    {
        global $DB;
        $search = '';
        if (isset($_POST['search'])) {
            $search = $_POST['search'];
        }
        $products = $products = $DB->selectLikeColumn('products', 'description', $search);

        $displayCatalogue = new Webpage();
        $displayCatalogue->title = 'Catalogue';
        $displayCatalogue->activeMenu = 'catalogue';
        $displayCatalogue->extraCss = 'css\catalog.css';
        if (isset($_SESSION['user_level'])) {
            if (($_SESSION['user_level']) == 'A') {
                $displayCatalogue->content = <<<HTML
                <div class="clearfix">
                    <a href="./index.php?op=118" class="btn" title="Click here to add new product" >Add Product</a>
                    <div>$message</div>
                </div>
                HTML;
            }
        }
        $displayCatalogue->content .= <<<HTML
        
         <div class="wrapper">
         
            <div class='search-form'>
                <form class="search-form form-inline md-form mr-auto mb-4" action="index.php?op=1" method="POST">
                    <input class="form-control mr-sm-2" type="text" value="{$search}" name="search" placeholder="Search.." aria-label="Search">
                    <button class="btn btn-primary aqua-gradient btn-rounded btn-sm my-0" type="submit">Search</button>
                </form>
            </div>
            <div class="section-contact">
              
        HTML;
        $displayCatalogue->content .= $this->arrayToTable($products);
        $displayCatalogue->content .= <<<HTML
            </div>
        </div>
        HTML;
        $displayCatalogue->render();
    }

    public function arrayToTable($array)
    {
        $output = '';
        $output = '<br><br><br>';
        $output .= '<ul class="products">';
        foreach ($array as $item) {
            $output .= '<a href=".\index.php?op=121&id='.$item['id'].'"><li>';

            $output .= '<img src="./img/'.$item['image'].'" alt="'.$item['name'].'" title="'.$item['name'].'"/>';
            $output .= '<span class="price"><span class="currency-symbol">$</span>'.$item['price'].'</span>';
            $output .= '<h4><a href="">'.$item['name'].'</a></h4>';
            $output .= '<span class="product-type">'.$item['category'].'</span></a>';
            if (isset($_SESSION['user_email']) && $_SESSION['user_level'] == 'A') {
                $output .= "<div class='product_modify_links'><ul class='product_modify_ul'>".
                "<a class='edit' href='index.php?op=116&id=".$item['id']."'><li class='product_modify_li'>Edit</li></a>";
                $output .= "<a class='delete'  href='index.php?op=120&id=".$item['id']." '><li class='product_modify_li'>Delete</li></a></ul></div>";
            }
            $output .= '</li>';
        }
        $output .= '</ul>';

        return $output;
    }

    public function ProductsWebService()
    {
        global $DB;
        $products = $DB->table('products');
        $productJson = json_encode($products, JSON_PRETTY_PRINT);

        $content_type = 'Content-Type:application/json;charset=UTF-8';
        header($content_type);
        http_response_code(200);
        echo $productJson;
    }

    public function editProductForm($err_msg = '', $prev_values = [], $productId = '')
    {
        if (isset($_SESSION['user_email']) && $_SESSION['user_level'] == 'A') {
            global $DB;
            if ($productId != '') {
                $_GET['id'] = $productId;
            }
            if (isset($_GET['id'])) {
                if (empty($prev_values)) {
                    $result = $DB->selectAll('products', ['and' => ['id' => $_GET['id']]]);
                    if (!empty($result)) {
                        $product = $result[0];
                    }
                } else {
                    $product = $prev_values;
                }
                $page = new Webpage();
                $page->extraCss = "css\tools.css";
                $page->title = 'Edit Product '.$product['name'];
                $page->description = 'Update the basic informations of product';
                $page->content = addEditProductForm(true, 117, $product, $err_msg);
                $page->activeMenu = 'catalouge';
                $page->render();
            } else {
                crash(404, 'Please select a valid product');
            }
        } else {
            crash(401, 'You have not authorized user to update product detail');
        }
    }

    public function addProductForm($err_msg = '', $prev_values = [])
    {
        if (isset($_SESSION['user_email']) && $_SESSION['user_level'] == 'A') {
            $product = [];
            if (!empty($prev_values)) {
                $product = $prev_values;
            } else {
                $product = [
                    'name' => '',
                    'description' => '',
                    'price' => '',
                    'qty' => '',
                    'category' => '',
                    'ingredients' => '',
                ];
            }
            $page = new Webpage();
            $page->extraCss = 'css\tools.css';
            $page->title = 'Add New Product';
            $page->description = 'Save new product with the basic informations';
            $page->content = addEditProductForm(false, 119, $product, $err_msg);
            $page->activeMenu = 'catalouge';
            $page->render();
        } else {
            crash(401, 'You have not authorized user to add new product');
        }
    }

    public function saveProduct()
    {
        if (isset($_SESSION['user_email']) && $_SESSION['user_level'] == 'A') {
            global $DB;
            addUpdateProduct($DB, $_POST, $_FILES);
        } else {
            crash(401, 'You have not authorized user to add new product');
        }
    }

    public function updateProduct()
    {
        if (isset($_SESSION['user_email']) && $_SESSION['user_level'] == 'A') {
            global $DB;
            addUpdateProduct($DB, $_POST, $_FILES);
        } else {
            crash(401, 'You have not authorized user to update product detail');
        }
    }

    public function deleteProduct()
    {
        if (isset($_SESSION['user_email']) && $_SESSION['user_level'] == 'A') {
            global $DB;
            $sql_str = 'DELETE FROM products WHERE id =:id';
            $products = $DB->queryParam($sql_str, ['id' => $_GET['id']]);
            header('Location: index.php?op=111');
        }
    }

    public function displayProductDetail($message='',$prev_values=[])
    {global $DB;
        if($prev_values!=null)
        {
            $product_id = $_POST['id'];
        }
        else{
        
        if (isset($_GET['id'])) {
            $product_id = $_GET['id'];
        } else {
            crash(505, 'Product id not found');
        }
        }
        $products = $DB->selectLikeColumn('products', 'id', $product_id);
        $webpage = new Webpage();
        $webpage->title = $products[0]['name'];
        $webpage->extraCss = 'css\productDetail.css';
        $webpage->content=<<<HTML
        <div style="margin:auto;text-align:center">$message</div>
        HTML;
        $webpage->content .= $this->productToWebpage($products);
        $webpage->render();
    }

    public function saveContactForm(){
        $to = "khush22786@gmail.com";//"admin@cannabis.com";
        $subject = $_POST['subject'];
        
        $message = "<b>Hello Admin</b>,<br>";
        $message .="New contact us form filled by ".$_POST['name']."<br>";
        $message .= "<h1>".$_POST['subject'].".</h1><br>";
        $message .="Email :".$_POST['email']."<br>";
        $message .="Gender :".$_POST['gender']."<br>";
        $message .="Province :".$_POST['province']."<br>";
        $message .=$_POST['message']."<br>";
        
        $header = "From:cannabis.com \r\n";
        $header .= "MIME-Version: 1.0\r\n";
        $header .= "Content-type: text/html\r\n";
        
        $retval = mail ($to,$subject,$message,$header);
        
        if( $retval == true ) {
           echo "Message sent successfully...";
        }else {
           echo "Message could not be sent...";
        }
        header("location: ./index.php");
    }

    public function productToWebpage($product)
    {
        $output = '';
        $output .= '<div class="product-titles">';
        $output .= '<h2>'.$product[0]['name'].'</h2></div>';
        $output .= '<div class="product-block">
        <img src="img/'.$product[0]['image'].'" alt="image" class="product-img" />
        <div class="product-content">';
        $output .= '<p id="price">$'.$product[0]['price'].'</p>
        <p id="description">'.$product[0]['description'].'</p>
        <form action="./index.php?op=122" method="post">
        <input type="hidden" name="id" value='.$product[0]['id'].'>
            <input type="submit" id="AddToCart" value="Add to Cart" />
        </form>
    </div>
</div>';

        return $output;
    }


    public function myOrder(){
        global $DB;
        $query="select orders.*,products.* from orders left join products on products.id=orders.product_id left join users on users.id=orders.user_id  where user_id=:user_id";
        $params[':user_id']=$_SESSION['user_id'];
        $results= $DB->querySelectParam($query,$params);
        $wep_page=new Webpage();
        $wep_page->title='My Orders';
        
        $wep_page->extraCss = 'css\cart.css';
        $wep_page->content='';
        $wep_page->content.='<h1>My Orders</h1>';
        $wep_page->content.='<div style="background-color: rgb(252, 248, 244); width:100%;">';
        if(!empty($results)){
            $wep_page->content.=$this->arrayToCart($results);
        }else{
            $wep_page->content='Orders list is empty';
        }
        $wep_page->content.='</div>';
        $wep_page->render();
    } 

    public function arrayToCart($array)
    {
        $output = '';
        $output = '<br>';
        $total=0;
        foreach ($array as $item) {
            $total +=$item['order_qty']*$item['price'];
            $output.='<div class="main-wrapper">
            <img src="./img/'.$item['image'].'" alt="img">
            <div class="product-block">
                <h3>'.$item['name'].'</h3>
                <p>'.$item['description'].'</p>
            </div>
            <div class="product-block">
                <p>'.$item['order_qty'].'</p>
            </div>
            <div class="product-block">
                <p>$'.$item['price'].'</p>
                
                
            </div>
    
        </div>';
        }
//<p>$'.$item['order_qty']*$item['price'].'</p>
        return $output;
    }
}
