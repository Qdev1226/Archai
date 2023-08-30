<?php
/*
*/

if (!class_exists("SpbThemesAdminNotices")) {
    if (!class_exists("\WPTRT\Autoload\Loader")) {
        include(__DIR__ . '/Loader.php');
    }

    $loader = new \WPTRT\Autoload\Loader();
    $loader->add('WPTRT\\AdminNoticesSPBCustom\\', __DIR__ . '/src');
    $loader->register();

    class SpbThemesAdminNotices
    {
        private $spbthemesAdminNotices;

        public function __construct()
        {
            $this->spbthemesAdminNotices = new \WPTRT\AdminNoticesSPBCustom\Notices();
        }

        public function Add($unique_id, $title, $content, $type = "info", $options = array())
        {
            $options['scope'] = "user";
            $options['type'] = $type;
            $this->spbthemesAdminNotices->add($unique_id, $title, $content, $options);
        }

        public function Boot()
        {
            $this->spbthemesAdminNotices->boot();
        }
    }
}

return new SpbThemesAdminNotices();
