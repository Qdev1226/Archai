<?php

namespace SuperbThemesCustomizer\Utils;

class CustomizerColor
{
    private $ID;
    private $Label;
    private $Description;
    private $Default;
    private $Dark;

    public function __construct($id, $label, $description, $default, $dark = false)
    {
        $this->ID = $id;
        $this->Label = $label;
        $this->Description = $description;
        $this->Default = $default;
        $this->Dark = $dark;
    }

    /**
     * Get the value of ID
     */
    public function GetId()
    {
        return $this->ID;
    }

    /**
     * Get the value of Title
     */
    public function GetLabel()
    {
        return $this->Label;
    }

    /**
     * Get the value of Description
     */
    public function GetDescription()
    {
        return $this->Description;
    }

    /**
     * Get the value of Default
     */
    public function GetDefault()
    {
        return $this->Default;
    }

    /**
     * Get the value of Dark
     */
    public function GetDarkDefault()
    {
        return $this->Dark;
    }

    public function GetDarkId()
    {
        return $this->Dark ? $this->ID . '-dark' : false;
    }
}
