<?php

require_once 'tools.php';
$DB = new DB_PDO();

$users = $DB->table('users');

class Users
{
    public function LoginPage($err_msg = '', $prev_values = [])
    {
        if ($prev_values == []) {
            $prev_values = [
                'email' => '',
                'password' => '',
            ];
        }
        $loginPage = new Webpage();
        $loginPage->title = 'LOgin';
        $loginPage->activeMenu = 'loginpage';
        $loginPage->extraCss = 'css\login.css';
        $loginPage->content = <<<HTML
        <!--    Login form HTML Starts Here   -->
        <div class="login">
        <div>$err_msg</div>
            <form action="./index.php?op=6" method="POST">
                <h3>Login
                    <a href="./index.php?op=3" style=" float: right;">Create Account</a>
                </h3>
                <br>
                <div>
                    <div class="input-icons">
                        <i class="fa fa-user icon">
                        </i>
                        <input type="text" placeholder="Username" name='email' required="" id="username" />
                    </div>
                </div>
                <div class="input-icons">
                    <i class="fa fa-key icon">
                    </i>
                    <input type="password" placeholder="Password"  name='password' required="" id="password" />
                </div>
                <div>
                    <button type="login" style="width:90px;height:50px;  background-color: grey;padding:15px;
                    margin:10px 0px 0px 45px;">Log
                        In</button>
                    
                </div>
            </form>
        </div>
        <!--     Login form HTML End Here   -->
        HTML;
        $loginPage->render();
    }

    public function LoginPageVerify()
    {
        global $users;
        $err_msg = '';
        if (isset($_POST['email'])) {
            $email_input = $_POST['email'];
        } else {
            crash(500, 'Email not found in form login, class user.php');
        }

        if (!filter_var($email_input, FILTER_VALIDATE_EMAIL)) {
            $err_msg .= 'email wrong in form login, class user.php';
        }

        if (isset($_POST['password'])) {
            $PW_input = $_POST['password'];
        } else {
            crash(500, 'Password not found in form login, class user.php<br>');
        }

        if (strlen($PW_input) < 8) {
            $err_msg .= 'Password must be 8 characters<br>';
        }

        //data problem
        if ($err_msg != '') {
            //display form with error msg and values previously entered
            $this->LoginPage($err_msg, $_POST);
        }

        //verify if email+pw in the list of users
        $user_found = false;
        foreach ($users as $one_user) {
            if ($one_user['email'] == $email_input and $one_user['password'] == $PW_input) {
                $user_found = true;
                echo '<p> user found</p>';
                $_SESSION['user_connected'] = true;
                $_SESSION['user_email'] = $one_user['email'];
                $_SESSION['user_name'] = $one_user['firstname'];
                $_SESSION['user_id'] = $one_user['id'];
                $_SESSION['user_level'] = $one_user['type'];

                setcookie('user_name', $one_user['firstname'], time() + 24 * 365);
                setcookie('user_email', $one_user['email'], time() + 24 * 365);
                setcookie('user_last_login', time(), time() + 24 * 365);
                break;
            }
        }
        if ($user_found) {
            header('Location:./index.php');
        } else {
            $this->LoginPage('email and/or password combination not found, try again', $_POST);
        }
    }

    public function SignupPage($err_msg = '', $prev_values = [])
    {
        if ($prev_values == []) {
            $prev_values = [
                'fname' => '',
                'lname' => '',
                'email' => '',
                'pwd' => '',
            ];
        }
        $signupPage = new Webpage();
        $signupPage->title = 'Signup page';
        $signupPage->activeMenu = 'signuppage';
        $signupPage->extraCss = 'css\signUp.css';
        $signupPage->content = <<<HTML
        <!--    signUp form HTML Starts Here   -->
        <div class="signUp">
            <div>$err_msg</div>
            <form action="./index.php?op=7" method='POST'>
                <h1>Sign Up</h1>
                <p>Please fill in this form to create an account.</p>
                <hr>
                <table>
                    <tr>
                        <td>
                            <label for="fname"><b>First Name</b></label>
                        </td>
                        <td> <input type="text" placeholder="Enter First Name" name="fname" value="{$prev_values['fname']}" required maxlength="15"></td>
                    </tr>
                    <tr>
                        <td> <label for="lname"><b>Last Name</b></label></td>
                        <td> <input type="text" placeholder="Enter Last Name" name="lname" value="{$prev_values['lname']}" required maxlength="15"></td>
                    </tr>
                    <tr>
                        <td> <label for="email"><b>Email</b></label></td>
                        <td> <input type="text" placeholder="Enter Email" name="email" value="{$prev_values['email']}" required maxlength="50"></td>
                    </tr>
                    <tr>
                        <td> <label for="pwd"><b>Password</b></label></td>
                        <td> <input type="password" placeholder="Enter Password" name="pwd" value="{$prev_values['pwd']}" required maxlength="16"></td>
                    </tr>
                    <tr>
                        <td> <label for="pwd-repeat"><b>Repeat Password</b></label></td>
                        <td> <input type="password" placeholder="Repeat Password" name="pwd-repeat" required maxlength="16"></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <label>
                                <input type="checkbox" checked="checked" name="remember" style="margin-bottom:15px"> You
                                agree to our Terms & Privacy.
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="btnContainer">
                                <button type="reset" class="cancelbtn">Reset</button>
                                <button type="submit" class="signupbtn">Sign Up</button>
                            </div>
                        </td>
                    </tr>
                </table>    
            </form>
        </div>
        <!--     signUp form HTML End Here   -->
        HTML;
        $signupPage->render();
    }

    public function SignupVerify()
    {
        global $users,$DB;

        $err_msg = '';
        if (!isset($_POST['fname'])) {
            crash(500, 'Full name not found');
        }
       
        if (trim($_POST['fname']) == '' || strpbrk($_POST['fname'], ' ') !== false) {
            $err_msg .= 'First name must be atleast 1 character and should not contain spaces<br>';
        } elseif (strlen($_POST['fname']) > 50) {
            $err_msg .= 'First name should be less than 50 characters<br>';
        }

        if (1 === preg_match('~[0-9]~', $_POST['fname'])) {
            $err_msg .= 'First name contains numeric values.only character values allowed!.';
        }
        if (!isset($_POST['lname']) ) {
            crash(500, 'last name not found');
        }

        if (trim($_POST['lname']) == '' || strpbrk($_POST['lname'], ' ') !== false) {
            $err_msg .= 'last name must be atleast 1 character<br>';
        } elseif (strlen($_POST['lname']) > 50) {
            $err_msg .= 'Last name should be less than 50 characters<br>';
        }
        if (1 === preg_match('~[0-9]~', $_POST['lname'])) {
            $err_msg .= 'Last name contains numeric values.only character values allowed!.';
        }

        if (!isset($_POST['email'])) {
            crash(500, 'Email not found in form register, class users.php');
        }

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $err_msg .= 'email wrong in form register, class users.php';
        }
        if (!isset($_POST['pwd'])) {
            crash(500, 'Password not found in form register, class user.php<br>');
        }
        if (strlen($_POST['pwd']) < 8 && strlen($_POST['pwd']) > 15) {
            $err_msg .= 'Password must be between 8 to 15 characters<br>';
        }

        if (!isset($_POST['pwd-repeat'])) {
            crash(500, 'Repeat Password not found in form register, class user.php<br>');
        }

        if ($_POST['pwd'] != $_POST['pwd-repeat']) {
            $err_msg .= 'Repeat Password must match with the Password<br>';
        }
        if(!isset($_POST['remember']))
        {
            
                    $err_msg.="Signup unsuccesull.Please see privacy policies";
            
        }
        if ($err_msg != '') {
            $err_msg .= 'Please try again!<br>';
            $this->SignupPage($err_msg, $_POST);
        }
        //If new user found
        $new_user_found = true;
        $records = $DB->selectAll('users', ['and' => ['email' => $_POST['email']]]);
        if (!empty($records)) {
            $new_user_found = false;
        }
        if ($new_user_found) {
            $page = new Webpage();
            $DB->insert('users', ['firstname', 'lastname', 'email', 'password'], [$_POST['fname'], $_POST['lname'], $_POST['email'], $_POST['pwd']]);

            $page->title = 'You are register successfully';
            $page->activeMenu = 'signuppage';
            $page->content = 'Welcome '.$_POST['fname'];
            $_SESSION['user_connected'] = true;
            $_SESSION['user_email'] = $_POST['email'];
            $_SESSION['user_name'] = $_POST['fname'];
            $_SESSION['user_id'] = count($users);
            $_SESSION['user_level'] = 'U';

            $page->render();
        } else {
            $this->SignupPage('This email already exist, Please try again with other email', $_POST);
        }
    }

    public function Logout()
    {
        unset($_SESSION['user_connected']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_id']);
        unset($_SESSION['user_level']);
        unset($_SESSION['cart']);

        header('location:index.php');
    }

    public function UserWebService()
    {
        global $DB;
        $users = $DB->table('users');
        $userJson = json_encode($users, JSON_PRETTY_PRINT);

        $content_type = 'Content-Type:application/json;charset=UTF-8';
        header($content_type);
        http_response_code(200);
        echo $userJson;
    }
}
