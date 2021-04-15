<?php

require_once 'tools.php';
$DB = new DB_PDO();

$contact = $DB->table('contacts');
class Contact
{
    public function displayContactPage()
    {
        $contactPage = new Webpage();
        $contactPage->title = 'ContactPage';
        $contactPage->activeMenu = 'contactPage';
        $contactPage->content = <<<HTML
        <div class="wrapper">
            <div class="section-contact">
                <h1 class="contact-heading text-center"> We would love to hear from you </h1>
                <p class="common-desc text-center"> Drop Us a message here or email us at <a href="javascript:void(0)">
                        info@cbd</a> and we'll get back to you! </p>
                <div class="row sec-contact">
                    <div class="col-md-12 col-lg-7">
                        <div class="contact-left" style="width: 50%; float: left;">
                            <img src="img/contact.png" width="452" height="438" alt="Contact Us">
                        </div>
              <div class="contact-right" style="width: 50%;float: left;">
                  <form id="contactForm" class="contact-form" action="./index.php?op=124" method="post">
                  <div>
                  <input class="contact-input-field" type="text" name="name" required>
                  <label class="label"> Name </label>
                </div>
                <div>
                  <input class="contact-input-field" type="email" name="email" required>
                  <label class="label"> Email </label>
                </div>
                <div>
                  <input class="contact-input-field" type="text" name="subject" required>
                  <label class="label"> Subject </label>
                </div>
                <div class="contact-input-field">
                  <label> Gender </label><br>
                  &nbsp;<input type="radio" id="male" required name="gender" value="male">
                  <label for="male">Male</label><br>
                  &nbsp;<input type="radio" id="female" name="gender" value="female">
                  <label for="female">Female</label><br>
                  &nbsp;<input type="radio" id="other" name="gender" value="other">
                  <label for="other">Other</label>
                </div>
                <div class="contact-input-field">
                  <label for="province">Province : </label>
                  <select id="province" name="province" required>
                    <option value="" selected>Choose province</option>
                    <option value="Montreal">Montreal</option>
                    <option value="Ontario">Ontario</option>
                    <option value="British Columbia">British Columbia</option>
                  </select>
                </div>
                <div>
                  <textarea class="contact-input-field textarea" name="message" required></textarea>
                  <label class="label"> Message </label>
                </div>
                <div class="text-center">
                  <button type="submit" class="primary-btn contact-submit-btn"> Send </button>
                </div>
                            </form>
                        </div>
                    </div>
                    <div class="" style="clear: both;">
                    </div>
                </div>
            </div>
        </div>
        HTML;
        $contactPage->render();
    }
}
