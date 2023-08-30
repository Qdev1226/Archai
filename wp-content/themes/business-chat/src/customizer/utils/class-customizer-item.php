<?php

namespace SuperbThemesCustomizer\Utils;

use SuperbThemesCustomizer\CustomizerController;
use SuperbThemesCustomizer\CustomizerPanels;

use SuperbThemesCustomizer\Utils\CustomizerType;
use SuperbThemesCustomizer\Utils\CustomRadioImageControl;

class CustomizerItem
{
    const REFOCUS_WRAPPER_AFFIX = "_refocus_wrapper";

    private $id;
    private $label;
    private $description;
    private $type;
    private $parents;
    private $priority;
    private $alt_id;
    private $default_value;
    private $section;
    private $radio_choices;
    private $range_attributes;
    private $conditions;

    public function __construct($id, $options)
    {
        $defaults = array('type' => "", 'label' => "", "choices" => array(), "range" => array(), "description" => "", "parents" => [""], "alt_id" => false, "priority" => 10, "default" => "", "section" => "", "conditions" => false);
        $options = array_merge($defaults, $options);
        $this->id = $id;
        $this->type = $options['type'];
        $this->label = $options['label'];
        $this->description = $options['description'];
        $this->parents = $options['parents'];
        $this->priority = $options['priority'];
        $this->alt_id = $options['alt_id'];
        $this->default_value = $options['default'];
        $this->section = $options['section'];
        $this->radio_choices = $options['choices'];
        $this->range_attributes = $options['range'];
        $this->conditions = $options['conditions'];

        switch ($this->type) {
            case CustomizerType::PANEL:
                $this->AddPanel();
                break;
            case CustomizerType::SECTION:
                $this->AddSection();
                break;
            case CustomizerType::CONTROL_CHECKBOX:
            case CustomizerType::CONTROL_COLOR:
            case CustomizerType::CONTROL_TEXT:
            case CustomizerType::CONTROL_RADIO:
            case CustomizerType::CONTROL_RADIO_IMAGE:
            case CustomizerType::CONTROL_RANGE:
            case CustomizerType::CONTROL_IMAGE:
                $this->AddControl();
                break;
            default:
                //nothing
                break;
        }
    }


    public function GetId()
    {
        return $this->id;
    }

    public function GetAltId()
    {
        return $this->alt_id;
    }

    public function HasAlternativeId()
    {
        return $this->alt_id !== false;
    }

    public function GetLabel()
    {
        return $this->label;
    }

    public function GetDescription()
    {
        return $this->description;
    }

    public function GetType()
    {
        return $this->type;
    }

    public function GetParents()
    {
        return $this->parents;
    }

    public function GetPriority()
    {
        return $this->priority;
    }

    public function GetDefaultValue()
    {
        return $this->default_value;
    }

    public function GetRadioChoices()
    {
        return $this->radio_choices;
    }

    public function GetRangeAttributes()
    {
        return $this->range_attributes;
    }


    private function AddPanel()
    {
        CustomizerController::GetCustomizerObject()->add_panel($this->GetId(), array(
            'title'    => $this->GetLabel(),
            'description' => $this->GetDescription(),
            'priority' => $this->GetPriority(),
        ));
    }

    private function AddSection()
    {
        foreach ($this->GetParents() as $parent) {
            $section = $parent . $this->GetId();

            CustomizerController::GetCustomizerObject()->add_section($section, array(
                'title'    => $this->GetLabel(),
                'description' => $this->GetDescription(),
                'priority' => $this->GetPriority(),
                'panel' => $parent
            ));

            if (count($this->GetParents()) > 1) {
                $wrapper_id = $section . self::REFOCUS_WRAPPER_AFFIX;
                $buttons = array();
                foreach ($this->GetParents() as $other_parent) {
                    if ($this->HasAlternativeId() && $parent === $other_parent) {
                        // Has alternative ID, should not create refocus buttons in other parents
                        continue;
                    }
                    if ($other_parent !== $parent) {
                        $buttons[] = $this->AddRefocusButton(
                            $wrapper_id,
                            $parent,
                            $other_parent
                        );
                    }
                }

                CustomizerController::GetCustomizerObject()->add_control(new RefocusButtonControl(CustomizerController::GetCustomizerObject(), $wrapper_id, array(
                    'priority' => 0,
                    'settings' => array(),
                    'section' => $section,
                ), $buttons));
            }

            // Has alternative ID, should not only be made in first parent.
            if ($this->HasAlternativeId()) {
                break;
            }
        }
    }

    private function AddRefocusButton($wrapper_id, $current_parent, $other_parent)
    {
        $button_id = $current_parent . $this->GetId() . "_to_" . $other_parent . $this->GetId() . "_btn";
        $refocus_id = $this->HasAlternativeId() ? $other_parent . $this->GetAltId() : $other_parent . $this->GetId();
        $title = CustomizerController::GetCustomizerObject()->get_panel($other_parent)->title;

        $button = false;
        if (in_array($other_parent, CustomizerPanels::SHOULD_REFOCUS_TO_PANEL)) {
            $button = new CustomizerRefocusButton($wrapper_id, $button_id, $refocus_id, $title, "panel", $other_parent);
        } else {
            $button = new CustomizerRefocusButton($wrapper_id, $button_id, $refocus_id, $title);
        }

        CustomizerController::AddRefocusButtonToScripts($button);

        return $button;
    }


    private function AddControl()
    {
        $section = $this->section;

        switch ($this->GetType()) {
            case CustomizerType::CONTROL_CHECKBOX:
            case CustomizerType::CONTROL_TEXT:
            case CustomizerType::CONTROL_RADIO:
            case CustomizerType::CONTROL_RADIO_IMAGE:
                CustomizerController::GetCustomizerObject()->add_setting($this->GetId(), array(
                    'default'           => $this->GetDefaultValue(),
                    'sanitize_callback' => 'sanitize_text_field',
                    'capability'        => 'edit_theme_options',
                    'type'              => 'theme_mod',
                ));
                break;
            case CustomizerType::CONTROL_COLOR:
                CustomizerController::GetCustomizerObject()->add_setting($this->GetId(), array(
                    'default'           => $this->GetDefaultValue(),
                    'sanitize_callback' => 'sanitize_hex_color',
                    'transport'         => 'postMessage',
                    'capability'        => 'edit_theme_options',
                    'type'              => 'theme_mod',
                ));
                break;
            case CustomizerType::CONTROL_IMAGE:
                CustomizerController::GetCustomizerObject()->add_setting($this->GetId(), array(
                    'default'           => $this->GetDefaultValue(),
                    'sanitize_callback' => 'absint',
                    'capability'        => 'edit_theme_options',
                    'type'              => 'theme_mod',
                ));
                break;
            case CustomizerType::CONTROL_RANGE:
                CustomizerController::GetCustomizerObject()->add_setting($this->GetId(), array(
                    'default'           => $this->GetDefaultValue(),
                    'sanitize_callback' => 'absint',
                    'transport'         => 'postMessage',
                    'capability'        => 'edit_theme_options',
                    'type'              => 'theme_mod',
                ));
            default:
                //nothing
                break;
        }

        switch ($this->GetType()) {
            case CustomizerType::CONTROL_CHECKBOX:
            case CustomizerType::CONTROL_TEXT:
                CustomizerController::GetCustomizerObject()->add_control($this->GetId(), array(
                    'label'    => $this->GetLabel(),
                    'description' => $this->GetDescription(),
                    'priority' => $this->GetPriority(),
                    'section'  => $section,
                    'settings' => $this->GetId(),
                    'type'     => $this->GetType(),
                    "active_callback" => $this->MaybeGetActiveCallback(),
                ));
                break;
            case CustomizerType::CONTROL_RADIO:
                CustomizerController::GetCustomizerObject()->add_control($this->GetId(), array(
                    'label'    => $this->GetLabel(),
                    'description' => $this->GetDescription(),
                    'priority' => $this->GetPriority(),
                    'section'  => $section,
                    'settings' => $this->GetId(),
                    'type'     => $this->GetType(),
                    'choices'  => $this->GetRadioChoices(),
                    "active_callback" => $this->MaybeGetActiveCallback(),
                ));
                break;
            case CustomizerType::CONTROL_RANGE:
                CustomizerController::GetCustomizerObject()->add_control($this->GetId(), array(
                    'label'    => $this->GetLabel(),
                    'description' => $this->GetDescription(),
                    'priority' => $this->GetPriority(),
                    'section'  => $section,
                    'settings' => $this->GetId(),
                    'type'     => $this->GetType(),
                    'input_attrs'  => $this->GetRangeAttributes(),
                    "active_callback" => $this->MaybeGetActiveCallback(),
                ));
                break;
            case CustomizerType::CONTROL_RADIO_IMAGE:
                CustomizerController::GetCustomizerObject()->add_control(
                    new CustomRadioImageControl(
                        CustomizerController::GetCustomizerObject(),
                        $this->GetId(),
                        array(
                            'settings'        => $this->GetId(),
                            'section'        => $section,
                            'priority'      => $this->GetPriority(),
                            'label'            => $this->GetLabel(),
                            'type'          => $this->GetType(),
                            'description'    => $this->GetDescription(),
                            'choices'        => $this->GetRadioChoices(),
                            "active_callback" => $this->MaybeGetActiveCallback(),
                        )
                    )
                );
                break;
            case CustomizerType::CONTROL_IMAGE:
                CustomizerController::GetCustomizerObject()->add_control(
                    new \WP_Customize_Media_Control(
                        CustomizerController::GetCustomizerObject(),
                        $this->GetId(),
                        array(
                            'settings'        => $this->GetId(),
                            'section'        => $section,
                            'priority'      => $this->GetPriority(),
                            'label'            => $this->GetLabel(),
                            'description'    => $this->GetDescription(),
                            'mime_type'     => $this->GetType(),
                            "active_callback" => $this->MaybeGetActiveCallback(),
                        )
                    )
                );
                break;
            case CustomizerType::CONTROL_COLOR:
                CustomizerController::GetCustomizerObject()->add_control(
                    new \WP_Customize_Color_Control(
                        CustomizerController::GetCustomizerObject(),
                        $this->GetId(),
                        array(
                            'label'       => $this->GetLabel(),
                            'description' => $this->GetDescription(),
                            'section'     => $section,
                            'priority'   => $this->GetPriority(),
                            'settings'    => $this->GetId(),
                            "active_callback" => $this->MaybeGetActiveCallback(),
                        )
                    )
                );
                break;
        }
    }

    private function MaybeGetActiveCallback()
    {
        if (!$this->conditions || !is_array($this->conditions) || count($this->conditions) <= 0) {
            return false;
        }

        $conditions = $this->conditions;
        return function ($control) use ($conditions) {
            foreach ($conditions as $setting => $values) {
                if (in_array($control->manager->get_setting($setting)->value(), $values)) {
                    return true;
                }
            }
            return false;
        };
    }
}
