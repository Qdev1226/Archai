<?php

class CloudflareController_bwg
{
    /**
     * @var $view
     */
    private $view;

    public function __construct()
    {
        $this->view = new CloudflareView_bwg();
    }
    /**
     * Execute.
     */
    public function execute() {
        $this->display();
    }

    /**
     * Display.
     */
    public function display() {
        $this->view->display();
    }
}