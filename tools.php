<?php

function crash($code, $message)
{
    http_response_code($code);
    //here email to IT Admin
    //  mail(ADMIN_EMAIL, COMPANY_NAME.' Server crashed code='.$code, $message);

    //write in log file
    $file = fopen(LOG_FILE_PATH, 'a+');
    $t = time();
    fwrite($file, date(DATE_RFC2822, $t).':'.$message.'<br>'.PHP_EOL);
    fclose($file);
    exit($message);
}

function Photo_Uploaded_Is_Valid($file_input, $Max_Size = 500000)
{
    //Must havein HTML <form enctype="multipart/form-data" ..
    //otherwise $_FILE is undefined
    // $file_input is the fileinput name on the HTML form
    if (!isset($_FILES[$file_input])) {
        return 'No image uploaded';
    }

    if ($_FILES[$file_input]['error'] != UPLOAD_ERR_OK) {
        return 'Error picture upload: code='.$_FILES[$file_input]['error'];
    }

    // Check image size
    if ($_FILES[$file_input]['size'] > $Max_Size) {
        return 'Image too big, max file size is '.$Max_Size.' Kb';
    }

    // Check that file actually contains an image
    $check = getimagesize($_FILES[$file_input]['tmp_name']);
    if ($check === false) {
        return 'This file is not an image';
    }
    // Check extension is jpg,JPG,gif,png

    $imageFileType = pathinfo(basename($_FILES[$file_input]['name']), PATHINFO_EXTENSION);
    if ($imageFileType != 'jpg' && $imageFileType != 'JPG' && $imageFileType != 'gif' && $imageFileType != 'png') {
        return 'Invalid image file type, valid extensions are: .jpg .JPG .gif .png';
    }

    return 'OK';
}

/**
 * Function to save uploaded image in folder
 * (and display image for testing).
 * $file_input is the file input name on the HTML form.
 * */
function Picture_Save_File($file_input, $target_dir)
{
    $result = ['msg' => '', 'status' => 0];
    $message = Photo_Uploaded_Is_Valid($file_input); // voir fonction
    if ($message === 'OK') {
        // Check that there is no file with the same name
        // already exists in the target folder
        // using file_exists()
        $target_file = $target_dir.basename($_FILES[$file_input]['name']);
        if (file_exists($target_file)) {
            $result['msg'] = 'This file already exists';
        }
        // Create the file with move_uploaded_file()
        if (move_uploaded_file($_FILES[$file_input]['tmp_name'], $target_file)) {
            // ALL OK display image for testing
            // echo '<img src="'.$target_file.'">';
            $result = ['msg' => 'Image has been uploaded successfully', 'status' => 1];
        } else {
            $result['msg'] = 'Error in move_upload_file';
        }
    } else {
        // upload error, invalid image or file too big
        $result['msg'] = $message;
    }

    return $result;
}

function addEditProductForm($isProductEdit = false, $op, $product, $err_msg)
{
    $btName = 'Save';
    if ($isProductEdit) {
        $btName = 'Update';
    }
    $pageContent = <<<HTML
    <div class="alert alert-danger">{$err_msg}</div>
    <form  enctype="multipart/form-data" class="edit-product-form md-form mr-auto mb-4" action="index.php?op={$op}" method="POST">
        <div class="form-content">    
            <div class="clearfix"></div>
            <br>
    HTML;
    if ($isProductEdit) {
        if (empty($product['image'])) {
            $product['image'] = PRODUCT_DEFAULT_IMAGE;
        }
        $productImg = PRODUCTS_UPLOAD_FOLDER.$product['image'];
        $pageContent .= <<<HTML
                <input type="hidden" name="id" value="{$product['id']}">
                <div class="form-group">
                    <img src="{$productImg}" class='update-product-img'>    
                </div>
                <br>
                HTML;
    }

    $pageContent .= <<<HTML
    <div class="productform">
        <h2>Fill Form to Add a New Product:</h2>
            <div class="form-group">
                <label class="">Upload new product image (Max 500kb jpg, jpeg, gif or png)</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                    </div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="inputGroupFile01"
                        aria-describedby="inputGroupFileAddon01" name="product_pic">
                        <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                    </div>    
                </div>
            </div>
            <br>
            
            <div class="form-group">
                <label class="form-control-lb">Product Name</label>
                <input class="form-control-lg"  type="text" placeholder="" name="name" value='{$product['name']}' maxlength="50" required>
            </div><br>
            <div class="form-group">
                <label class="form-control-lb">Product Description</label>
                <textarea class="form-control-lg" aria-label="With textarea" name="description"maxlength="255">{$product['description']}</textarea>
            </div><br><br>
            <div class="form-group">
                <label class="form-control-lb">Product Price</label>
                <input class="form-control-lg"  type="number" step="0.01" placeholder="Product Price" name="price" value='{$product['price']}'min=0 required>
            </div><br>
            <div class="form-group">
                <label class="form-control-lb">Product Category</label>
                <input class="form-control-lg"  type="text" placeholder="Product Category" name="category" value='{$product['category']}'>
            </div><br>
            <div class="form-group">
                <label class="form-control-lb">Product Ingredients</label>
                <input class="form-control-lg"  type="text" placeholder="Product Ingredients" name="ingredients" value='{$product['ingredients']}'>
            </div><br>
            <div class="form-group">
                <label class="form-control-lb">Product Quantity</label>
                <input class="form-control-lg"  type="number" step="1" placeholder="Product Quantity in Stock" name="qty" value='{$product['qty']}'min=0 required>
            </div><br><br>
            <div class="form-group">
                <button class="btn btn-primary aqua-gradient btn-rounded" type="submit">{$btName}</button>
            </div>
            <br>
        </div>
        </div>
    </form>
    HTML;

    return $pageContent;
}

function addUpdateProduct($DB, $_post, $_files)
{
    $err_msg = '';
    if (!isset($_post['name'])) {
        crash(500, 'Product name not found in form update, class product.php');
    }
    if (trim($_post['name']) == '') {
        $err_msg .= 'Product name must be atleast 1 character<br>';
    } elseif (strlen($_post['name']) > 50) {
        $err_msg .= 'Product name should be less than 50 characters<br>';
    }

    if (!isset($_post['description'])) {
        crash(500, 'Product description not found in form update, class product.php');
    }

    if (strlen($_post['description']) > 255) {
        $err_msg .= 'Product description should be less than 255 characters<br>';
    }

    if (!isset($_post['price'])) {
        crash(500, 'Product price not found in form update, class product.php');
    }

    if (trim($_post['price']) < 0) {
        $err_msg .= 'Product price should be more than 0<br>';
    }
    if (!isset($_post['qty'])) {
        crash(500, 'Product qty not found in form update, class product.php');
    }

    if (trim($_post['qty']) < 0) {
        $err_msg .= 'Product qty should be more than 0<br>';
    }

    $queryInit = 'insert into';
    if (isset($_post['id'])) {
        $queryInit = 'update';
    }
    $product = new Catalogue();
    //data problem
    if ($err_msg != '') {
        $err_msg .= 'Please try again!<br>';
        if (isset($_post['id'])) {
            $product->editProductForm($err_msg, $_post);
        } else {
            $product->addProductForm($err_msg, $_post);
        }
    } else {
        if (!empty($_files['product_pic']['name'])) {
            $result = Picture_Save_File('product_pic', PRODUCTS_UPLOAD_FOLDER);
        } else {
            $result['status'] = 1;
        }
        if ($result['status'] == 1) {
            $product_update_query = $queryInit.' products set name=:name, description=:description, price=:price, qty=:qty, category=:category, ingredients=:ingredients, status=:status';
            $params = ['name' => $_post['name'], 'description' => $_post['description'], 'price' => $_post['price'], 'qty' => $_post['qty'], 'category' => $_post['category'], 'ingredients' => $_post['ingredients'], 'status' => 'E'];
            if (!empty($_files['product_pic']['name'])) {
                $picName = (isset($_files['product_pic']) && !empty($_files['product_pic']['name']) ? $_files['product_pic']['name'] : '');
                $params['image'] = $picName;
                $product_update_query .= ', image=:image';
            }
            if (isset($_post['id'])) {
                $product_update_query .= ' where id='.$_post['id'];
            }
            $DB->queryParam($product_update_query, $params);
            header('Location: index.php?op=1');
        } else {
            $product->editProductForm($result['msg'], $_post, $productId = $_post['id']);
        }
    }
}
function array_to_table($product)
{
    $ret = '';
    $ret .= '';
    $ret .= '<h1 style="text-color:#46403d;text-align : center;">List Of All Users:</h1>';
    $ret .= '<br>';
    $ret .= '<table border=1 style="background:#D6A88F;text-align:center; margin-left: auto;
    margin-right: auto;width:80%">';

    $key = array_keys($product[0]);
    $ret .= '<tr >';
    foreach ($key as $value => $value2) {
        $ret .= '<td style="padding: 10px;text-transform: uppercase;">'.$value2.'</td>';
    }
    $ret .= '</tr>';
    foreach ($product as $key => $value) {
        $ret .= '<tr>';
        foreach ($value as $key2 => $value2) {
            $ret .= '
       <td style="background:white;padding: 10px;">'.$value2.'</td>';
        }
        $ret .= '
   </tr>';
    }
    $ret .= '</table>';

    return $ret;
}
