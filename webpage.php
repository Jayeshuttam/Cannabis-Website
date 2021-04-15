<?php

require_once 'global_defines.php';
class Webpage
{

    public $title=PAGE_DEFAULT_TITLE;
    public $description='';
    public $author=PAGE_AUTHORS;
    public $lang='en-CA';
    public $icon='logo11.png';
    public $css='css\style.css';
    public $content='';
    public $activeMenu='home';
    public $extraCss='';

        public function _construct()
        {
        }

        public function render(){
            if($this->content=='')
            {
                crash(500,'Internal Server Error');
            }
            else
            {
                $this->title .=' - '.COMPANY_NAME;
            if($this->lang=='en-CA'){
                require_once('template.php');
            }else if($this->lang=='fr-CA'){
                require_once('template_fr.php');
            }else{
                crash(400,"language not supported");
            }
            die(); //stop program here
            }
        }
}