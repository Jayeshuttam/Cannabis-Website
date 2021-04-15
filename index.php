<?php

session_start();
require_once 'webpage.php';
require_once 'gallery.php';
require_once 'catalogue.php';
require_once 'contact.php';
require_once 'users.php';
require_once 'tools.php';
require_once 'cart.php';
require_once 'global_defines.php';
$op = isset($_GET['op']) ? $_GET['op'] : 0;

switch ($op) {
        case 0:
            HomePage();
            break;
        case 1:
            //Displays catalogue
            $catalogue = new Catalogue();
            $catalogue->displayAllProducts();
            break;
        case 2:
            //renders gallery page
             $gallery = new Gallery();
             $gallery->GalleryPage();

             break;
        case 3:
            //REnders Signup page
            $user = new Users();
            $user->SignupPage();
            break;
        case 4:
            //REnders Login page
            $user = new Users();
            $user->LoginPage();
            break;
        case 5:
            //Renders Contact page
            $contact = new Contact();
            $contact->displayContactPage();
            break;
        case 6:
            //Process and verify data submitted from Login page
            $user = new Users();
            $user->LoginPageVerify();
            break;
        case 7:
            //Process and submits data to database submitted from Signup page and creates a new user
            $user = new Users();
            $user->SignupVerify();
            break;
        case 8:
            //logouts the users and ends the session of the corresponding user
            $user = new Users();
            $user->Logout();
            break;
        case 15:
            $user = new User();
            $user->UserWebService();
            break;
        case 115:
            $product = new Catalogue();
            $product->ProductsWebService();
            break;
        case 116:
            //renders edit product page from where we can edit a existing product
            $product = new Catalogue();
            $product->editProductForm();
            break;
        case 117:
            //process data submitted from editProductForm()
            $product = new Catalogue();
            $product->updateProduct();
            break;
            default:
            crash(400, 'Invalid op code in index.php');
            break;
        case 118:
            //Renders add product form on the screen
            $product = new Catalogue();
            $product->addProductForm();
            break;
        case 119:
            //processes and saves data submitted from addProductForm()
            $product = new Catalogue();
            $product->saveProduct();
            break;
        case 120:
            //deletes a product from database
            $product = new Catalogue();
            $product->deleteProduct();
            break;

        case 51:
            //displays all the users registered in the system
        $DB = new DB_PDO();
        $users = $DB->querySelect('select * from users');
        $HTML_Table = array_to_table($users);
        $page = new Webpage();
        $page->activeMenu = 'users';
        $page->title = 'All Users';
        $page->content = $HTML_Table;
        $page->render();
        break;

        case 50:
            //Displays all the errors log occured in the system
        if (isset($_SESSION['user_email']) && $_SESSION['user_level'] == 'A') {
            DisplayServerErrorLogs();
        } else {
            crash(403, 'Unauthorized user access');
        }
        break;
        case 121:
            //Renders product detail page
            $product = new Catalogue();
            $product->displayProductDetail();
            break;
        case 122:
            $cart=new Cart();
            $cart->addToCart();
            break;
        case 123:
            $cart=new Cart();
            $cart->displayCart();
            break;
        case 124:
            $product=new Catalogue();
            $product->saveContactForm();
            break;
        case 125:
            $product=new Catalogue();
            $product->myOrder();
        
            break;
        case 126:
            $cart=new Cart();
            $cart->checkout();
}

function HomePage()
{
    $home_page = new Webpage();
    $home_page->title = PAGE_DEFAULT_TITLE;
    $home_page->content = <<<HTML
     <!--    Banner HTML Starts Here   -->
     <div class="banner-container row">
            <div class="col-md-6 col-sm-12 banner-left">
                <h1 class="banner-heading">Discover top cannabis brands and products, curated just for you and delivered
                    to your door.Cannabis stands out for two reasons: It’s an incredibly best quality product, and it devotes enormous attention to the customer.</h1>
                <p class="banner-desc"> Delivering the modern cannabis consumer a personalized experience that is
                    tailored just for you – from pain relief, to cooking, and enhanced sensuality. Through
                    personalization and curation, this allows consumers to connect to products that speak directly to
                    them</p>
            </div>
        </div>
        <div>
            <h3>Featured Products</h3>
            <p>Best-sellers and staff favorites.</p><br>
            <div>
                <div style="padding-bottom: 10px;">
                    <a href="./index.php?op=1"><img src="img/content-cbd-101.jpg" alt="CBD" title="CBD"
                            class="features"></a>
                    <a href="./index.php?op=121&id=7"><img src="img/potli_1.jpg" alt="Potli" title="Potli" class="features"></a>
                    <a href="./index.php?op=121&id=8"><img src="img/brand-artet_1.jpg" alt="Brand" title="Brand"
                            class="features"></a>
                </div>
            </div>
        </div>
        <!--    Banner HTML Ends Here   -->
        <!--    Section 2 HTML Starts Here   -->
        <div class="section2 row">
            <div class="sec2-right col-md-12 col-lg-6">
                <div class="quote">
                    <div class="sprite quote-img"></div>
                </div>
                <h2 class="sec2-heading">Hemp Infused Raw Honey</h2>
                <p class="sec2-desc"> Use whenever tranquility of the body and mind is required. Potli's Hemp Honey may
                    help you de-stress, reduce anxiety and nausea, and benefit from anti-inflammatory properties. Your
                    calm focus is only a spoonful away!.</p>
            </div>
        </div>
        <!--    Section 2 Banner HTML Ends Here   -->
        <div class="push"></div>
    </div>
    HTML;
    $home_page->render();
}
function DisplayServerErrorLogs()
{
    $page = new Webpage();
    $page->title = 'Server error logs';
    $page->content = '';
    $page->activeMenu = 'logs';
    //get file content
    $page->extraCss = 'css\serverlog.css';
    $page->content = file_get_contents(LOG_FILE_PATH);
    $page->render();
}
