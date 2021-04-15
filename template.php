<!DOCTYpe html>
<html lang="<?php echo $this->lang; ?>">

<head>
    <meta charset="utf-8">
    <title><?php echo $this->title; ?>
    </title>
    <meta name='description'
        value="<?php echo $this->description; ?>">
    <meta name="author" value="<?php echo $this->author; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?php echo $this->icon; ?>"
        type="image/png">
    <?php
        if ($this->extraCss != '') {
            ?>
    <link rel="stylesheet" href="<?php echo $this->extraCss; ?>"
        type="text/css">
    <?php
        }
    ?>

    <link rel="stylesheet" href="<?php echo $this->css; ?>"
        type="text/css">

</head>

<body style="background-image:url(img/back9.jpg)">
    <div class="wrapper">
        <header>
            <nav class="navbar navbar-expand-lg navbar-light">
                <a class="navbar-brand" href="./index.php" title="Discover a personalized CBD experience"> <img
                        class="logo-img" src="img/logo11.png" width="150" height="90"
                        alt="Discover a personalized CBD experience"> </a>
                <img src="img/logo-bg.png" alt="logo" width="529" height="178" class="logo-bg">

                <div class="collapse navbar-collapse custom-nav" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-md-auto">
                        <li
                            class="nav-item <?php echo $this->activeMenu == 'home' ? 'active' : ''; ?>">
                            <a class="nav-link" href="./index.php" title="Home"> Home </a>
                        </li>
                        <li
                            class="nav-item <?php echo $this->activeMenu == 'catalogue' ? 'active' : ''; ?>">
                            <a class="nav-link" href="./index.php?op=1" title="Catalog"> Catalog </a>
                        </li>
                        <li
                            class="nav-item <?php echo $this->activeMenu == 'gallery' ? 'active' : ''; ?>">
                            <a class="nav-link" href="./index.php?op=2" title="Galary"> Gallery </a>
                        </li>
                        <li
                            class="nav-item <?php echo $this->activeMenu == 'contactPage' ? 'active' : ''; ?>">
                            <a class="nav-link" href="./index.php?op=5" title="Contact"> Contact </a>
                        </li>
                        <?php
        if (!isset($_SESSION['user_name'])) {
            ?>
                        <li
                            class="nav-item <?php echo $this->activeMenu == 'signuppage' ? 'active' : ''; ?>">
                            <a class="nav-link" href="./index.php?op=3" title="SignUp"> SignUp </a>
                        </li>
                        <li
                            class="nav-item <?php echo $this->activeMenu == 'loginpage' ? 'active' : ''; ?>">
                            <a class="nav-link" href="./index.php?op=4" title="Login"> LogIn </a>
                        </li>
                        <?php
        } else {
            if (isset($_SESSION['user_email']) && $_SESSION['user_level'] == 'A') {?>
                        <li
                            class="nav-item <?php echo $this->activeMenu == 'users' ? 'active' : ''; ?>">
                            <a class="nav-link" href="./index.php?op=51">List Users</a>
                        </li>
                        <li
                            class="nav-item <?php echo $this->activeMenu == 'logs' ? 'active' : ''; ?>">
                            <a class="nav-link" href="./index.php?op=50">Server Logs</a>
                        </li>
                        <?php
        } ?>

                        </li><?php
        }?>
                    </ul><?php
                    if (isset($_SESSION['user_name'])) {?>


                    <ul class="profile-block">
                        <li style="display:inline-block">
                            <div class="dropdown">
                                <button class="dropbtn"><?php echo $_SESSION['user_name']; ?></button>
                                <div class="dropdown-content">
                                    <a class="dropdown-item" href="./index.php?op=8">Logout</a>
                                    <a class="dropdown-item" href="./index.php?op=123">Cart</a>
                                    <a class="dropdown-item" href="./index.php?op=125">My orders</a>
                                </div>
                            </div>
                        </li>
                </div>
                </li>
                </ul>



                </li>
                </ul>

                <?php }?>

            </nav>
        </header>
        <!--    Header HTML Ends Here    -->

        <div class="body">
            <?php echo $this->content; ?>
        </div>

        <div class="push"></div>
    </div>
    <!-- Footer -->
    <footer style="font-family: sans-serif;">
        <div class="footerdiv">

            <div class="footerCatalog">
                <div class="footerThumbnail1">
                    <h2>Address</h2>
                    <p>
                        Institut supérieur d'informatique<br>
                        2100 boul. de Maisonneuve Est, étage #4<br>
                        Montréal, Québec<br>
                        H2K 4S1<br>
                    </p>
                    <br>
                    <h2>Contacts</h2>
                    <p>
                        Email:services@cannabis.com<br>
                        Phone:+51412121212<br>
                    </p>
                </div>
                <div class="footerThumbnail2">
                    <h2>Links</h2>
                    <a href="./index.php">Home </a><br>
                    <a href="./index.php?op=5">Contact Us</a> <br>
                    <a href="./index.php?op=2">Our Services </a><br>
                    <br>
                    <h2>About us </h2>
                    <p> we are serving since 1999 and<br>
                        maintain our standard in the<br>
                        market </p><br>
                </div>
                <div class="footerThumbnail3">

                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2795.553933109297!2d-73.69307198492606!3d45.51905743780313!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4cc91871f7e220d5%3A0x555ea3f6f54012fd!2s1590%20D%C3%A9carie%20St%2C%20Saint-Laurent%2C%20QC%20H4L%203N3!5e0!3m2!1sen!2sca!4v1595094007612!5m2!1sen!2sca"
                        width="400" height="250" style="border:0;" allowfullscreen="" aria-hidden="false"
                        tabindex="0"></iframe><br><br>
                </div>
            </div>
            <div class="subscribe">
                <input id="textemail" type="hidden" name="email">
            </div>
            <div class="LowerFooter">
                <a class="backontop" href="#">back on top</a><br>
                &copy;Copyright reserverd cannabis.com 2019
                <div class="SocialLinks">
                    <a id="gmaillogo" href="mailto:manjit7898@gmail.com"><img src="img/gmail.png" width="30" height="30"
                            alt="click here" title="click for Gmail"> </a><a href="mailto:manjit7898@gmail.com"></a>
                    <a id="fblogo" href="https://www.facebook.com/profile.php?id=100008256668725" target="_blank"><img
                            src="img/fb1.png" width="30" height="30" alt="click here" title="click for facebook"></a><a
                        href="https://www.facebook.com/profile.php?id=100008256668725" target="_blank"></a>
                    <a id="calllogo" href="tel:438-722-0870"><img src="img/call111.png" width="30" height="30"
                            alt="click here" title="call us"></a><a href="tel:438-722-0870"></a>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer -->
</body>

</html>