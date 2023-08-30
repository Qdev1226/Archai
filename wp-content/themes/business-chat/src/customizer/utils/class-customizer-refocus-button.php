<?php

namespace SuperbThemesCustomizer\Utils;

class CustomizerRefocusButton
{
    private $wrapper_id;
    private $button_id;
    private $refocus_id;
    private $focus_type;
    private $panel;
    private $title;

    public function __construct($wrapper_id, $button_id, $refocus_id, $title, $focus_type = "section", $panel = false)
    {
        $this->wrapper_id = $wrapper_id;
        $this->button_id = $button_id;
        $this->refocus_id = $refocus_id;
        $this->title = $title;
        $this->focus_type = $focus_type;
        $this->panel = $panel;
    }

    public function GetWrapperId()
    {
        return $this->wrapper_id;
    }

    public function GetId()
    {
        return $this->button_id;
    }

    public function GetRefocusId()
    {
        return $this->focus_type === "panel" ? $this->panel :  $this->refocus_id;
    }

    public function GetType()
    {
        return $this->focus_type;
    }

    public function GetTitle()
    {
        return $this->title;
    }
}
