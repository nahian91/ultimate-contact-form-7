<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

class UCF7E_Widget_Contact_Form_7 extends Widget_Base {

    public function get_name() {
        return 'ucf7e_contact_form_7';
    }

    public function get_title() {
        return __( 'Ultimate Contact Form 7', 'nahian-ultimate-cf7-elementor' );
    }

    public function get_icon() {
        return 'eicon-form-horizontal';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    protected function _register_controls() {

        // ---------------- Content Tab ----------------
        $this->start_controls_section(
            'ucf7e_content_section',
            [
                'label' => __( 'Content', 'nahian-ultimate-cf7-elementor' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ucf7e_form_id',
            [
                'label'   => __( 'Select Form', 'nahian-ultimate-cf7-elementor' ),
                'type'    => Controls_Manager::SELECT,
                'options' => $this->get_all_cf7_forms(),
            ]
        );

        $this->add_control(
            'ucf7e_enable_ajax',
            [
                'label'        => __( 'Enable AJAX Submission', 'nahian-ultimate-cf7-elementor' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'ucf7e_redirect_url',
            [
                'label'       => __( 'Redirect After Submission', 'nahian-ultimate-cf7-elementor' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => 'https://',
            ]
        );

        $this->end_controls_section();

        // ---------------- Container ----------------
        $this->start_controls_section(
            'ucf7e_container_style',
            [
                'label' => __( 'Container', 'nahian-ultimate-cf7-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'ucf7e_container_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7',
            ]
        );

        $this->add_responsive_control(
            'ucf7e_container_padding',
            [
                'label'     => __( 'Padding', 'nahian-ultimate-cf7-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ucf7e_container_border',
                'selector' => '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7',
            ]
        );

        $this->add_control(
            'ucf7e_container_radius',
            [
                'label'     => __( 'Border Radius', 'nahian-ultimate-cf7-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ucf7e_container_shadow',
                'selector' => '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7',
            ]
        );

        $this->end_controls_section();

        // ---------------- Label ----------------
        $this->start_controls_section(
            'ucf7e_label_style',
            [
                'label' => __( 'Label', 'nahian-ultimate-cf7-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ucf7e_label_typography',
                'selector' => '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 label',
            ]
        );

        $this->add_control(
            'ucf7e_label_color',
            [
                'label'     => __( 'Color', 'nahian-ultimate-cf7-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
			'ucf7e_label_spacing',
			[
				'label' => esc_html__( 'Spacing', 'nahian-ultimate-cf7-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .ucf7e-widget-wrapper p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();
// ---------------- Input Fields ----------------
$this->start_controls_section(
    'ucf7e_input_style',
    [
        'label' => __( 'Input Fields', 'nahian-ultimate-cf7-elementor' ),
        'tab'   => Controls_Manager::TAB_STYLE,
    ]
);

// Typography
$this->add_group_control(
    Group_Control_Typography::get_type(),
    [
        'name'     => 'ucf7e_input_typography',
        'selector' => '
            {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="text"],
            {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="email"],
            {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="url"],
            {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="tel"],
            {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="number"],
            {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="password"]
        ',
    ]
);

// Text Color
$this->add_control(
    'ucf7e_input_color',
    [
        'label'     => __( 'Text Color', 'nahian-ultimate-cf7-elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="text"],
             {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="email"],
             {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="url"],
             {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="tel"],
             {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="number"],
             {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="password"]' => 'color: {{VALUE}};',
        ],
    ]
);

// Placeholder Color
$this->add_control(
    'ucf7e_input_placeholder',
    [
        'label'     => __( 'Placeholder Color', 'nahian-ultimate-cf7-elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="text"]::placeholder,
             {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="email"]::placeholder,
             {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="url"]::placeholder,
             {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="tel"]::placeholder,
             {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="number"]::placeholder,
             {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="password"]::placeholder' => 'color: {{VALUE}};',
        ],
    ]
);

// Focus Border Color
$this->add_control(
    'ucf7e_input_focus_border',
    [
        'label'     => __( 'Focus Border Color', 'nahian-ultimate-cf7-elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input:focus' => 'border-color: {{VALUE}}; outline: none;',
        ],
    ]
);

// Border
$this->add_group_control(
    Group_Control_Border::get_type(),
    [
        'name'     => 'ucf7e_input_border',
        'selector' => '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="text"],
                       {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="email"],
                       {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="url"],
                       {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="tel"],
                       {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="number"],
                       {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="password"]',
    ]
);

// Border Radius
$this->add_control(
    'ucf7e_input_radius',
    [
        'label'     => __( 'Border Radius', 'nahian-ultimate-cf7-elementor' ),
        'type'      => Controls_Manager::SLIDER,
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="text"],
             {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="email"],
             {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="url"],
             {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="tel"],
             {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="number"],
             {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="password"]' => 'border-radius: {{SIZE}}{{UNIT}};',
        ],
    ]
);

// Width & Height
$this->add_responsive_control(
    'ucf7e_input_width',
    [
        'label'      => __( 'Width', 'nahian-ultimate-cf7-elementor' ),
        'type'       => Controls_Manager::SLIDER,
        'size_units' => [ '%', 'px', 'em' ],
        'range'      => [
            '%'  => [ 'min' => 10, 'max' => 100 ],
            'px' => [ 'min' => 50, 'max' => 1000 ],
            'em' => [ 'min' => 5, 'max' => 50 ],
        ],
        'selectors'  => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="text"],
             {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="email"],
             {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="url"],
             {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="tel"],
             {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="number"],
             {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="password"]' => 'width: {{SIZE}}{{UNIT}};',
        ],
    ]
);

$this->add_responsive_control(
    'ucf7e_input_height',
    [
        'label'      => __( 'Height', 'nahian-ultimate-cf7-elementor' ),
        'type'       => Controls_Manager::SLIDER,
        'size_units' => [ 'px', 'em' ],
        'range'      => [
            'px' => [ 'min' => 20, 'max' => 200 ],
            'em' => [ 'min' => 1, 'max' => 20 ],
        ],
        'selectors'  => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="text"],
             {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="email"],
             {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="url"],
             {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="tel"],
             {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="number"],
             {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="password"]' => 'height: {{SIZE}}{{UNIT}};',
        ],
    ]
);

// Padding
$this->add_responsive_control(
    'ucf7e_input_padding',
    [
        'label'     => __( 'Padding', 'nahian-ultimate-cf7-elementor' ),
        'type'      => Controls_Manager::DIMENSIONS,
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="text"],
             {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="email"],
             {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="url"],
             {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="tel"],
             {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="number"],
             {{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="password"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
    ]
);

$this->end_controls_section();



        // ---------------- Textarea Fields ----------------
$this->start_controls_section(
    'ucf7e_textarea_style',
    [
        'label' => __( 'Textarea', 'nahian-ultimate-cf7-elementor' ),
        'tab'   => Controls_Manager::TAB_STYLE,
    ]
);

// Typography
$this->add_group_control(
    Group_Control_Typography::get_type(),
    [
        'name'     => 'ucf7e_textarea_typography',
        'selector' => '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 textarea',
    ]
);

// Text Color
$this->add_control(
    'ucf7e_textarea_color',
    [
        'label'     => __( 'Text Color', 'nahian-ultimate-cf7-elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 textarea' => 'color: {{VALUE}};',
        ],
    ]
);

// Placeholder Color
$this->add_control(
    'ucf7e_textarea_placeholder_color',
    [
        'label'     => __( 'Placeholder Color', 'nahian-ultimate-cf7-elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 textarea::placeholder' => 'color: {{VALUE}};',
        ],
    ]
);

// Border
$this->add_group_control(
    Group_Control_Border::get_type(),
    [
        'name'     => 'ucf7e_textarea_border',
        'selector' => '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 textarea',
    ]
);

// Border Radius
$this->add_control(
    'ucf7e_textarea_radius',
    [
        'label'     => __( 'Border Radius', 'nahian-ultimate-cf7-elementor' ),
        'type'      => Controls_Manager::SLIDER,
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 textarea' => 'border-radius: {{SIZE}}{{UNIT}};',
        ],
    ]
);

// Focus Border Color
$this->add_control(
    'ucf7e_textarea_focus_border',
    [
        'label'     => __( 'Focus Border Color', 'nahian-ultimate-cf7-elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 textarea:focus' => 'border-color: {{VALUE}}; outline: none;',
        ],
    ]
);

// Width
$this->add_responsive_control(
    'ucf7e_textarea_width',
    [
        'label'      => __( 'Width', 'nahian-ultimate-cf7-elementor' ),
        'type'       => Controls_Manager::SLIDER,
        'size_units' => [ '%', 'px', 'em' ],
        'range'      => [
            '%'  => [ 'min' => 10, 'max' => 100 ],
            'px' => [ 'min' => 50, 'max' => 1200 ],
            'em' => [ 'min' => 5, 'max' => 80 ],
        ],
        'selectors'  => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 textarea' => 'width: {{SIZE}}{{UNIT}};',
        ],
    ]
);

// Height
$this->add_responsive_control(
    'ucf7e_textarea_height',
    [
        'label'      => __( 'Height', 'nahian-ultimate-cf7-elementor' ),
        'type'       => Controls_Manager::SLIDER,
        'size_units' => [ 'px', 'em' ],
        'range'      => [
            'px' => [ 'min' => 50, 'max' => 800 ],
            'em' => [ 'min' => 3, 'max' => 50 ],
        ],
        'selectors'  => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 textarea' => 'height: {{SIZE}}{{UNIT}};',
        ],
    ]
);

// Padding
$this->add_responsive_control(
    'ucf7e_textarea_padding',
    [
        'label'     => __( 'Padding', 'nahian-ultimate-cf7-elementor' ),
        'type'      => Controls_Manager::DIMENSIONS,
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
    ]
);

$this->end_controls_section();


        // ---------------- Select Field ----------------
$this->start_controls_section(
    'ucf7e_select_field',
    [
        'label' => __( 'Select Field', 'nahian-ultimate-cf7-elementor' ),
        'tab'   => Controls_Manager::TAB_STYLE,
    ]
);

$this->add_group_control(
    Group_Control_Typography::get_type(),
    [
        'name'     => 'ucf7e_select_typography',
        'selector' => '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 select',
    ]
);

$this->add_control(
    'ucf7e_select_color',
    [
        'label'     => __( 'Text Color', 'nahian-ultimate-cf7-elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 select' => 'color: {{VALUE}};',
        ],
    ]
);

$this->add_control(
    'ucf7e_select_bg',
    [
        'label'     => __( 'Background Color', 'nahian-ultimate-cf7-elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 select' => 'background-color: {{VALUE}};',
        ],
    ]
);

$this->add_group_control(
    Group_Control_Border::get_type(),
    [
        'name'     => 'ucf7e_select_border',
        'selector' => '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 select',
    ]
);

$this->add_control(
    'ucf7e_select_radius',
    [
        'label'     => __( 'Border Radius', 'nahian-ultimate-cf7-elementor' ),
        'type'      => Controls_Manager::SLIDER,
        'size_units' => [ 'px', '%' ],
        'range' => [
            'px' => [ 'min' => 0, 'max' => 100 ],
            '%'  => [ 'min' => 0, 'max' => 100 ],
        ],
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 select' => 'border-radius: {{SIZE}}{{UNIT}};',
        ],
    ]
);

$this->add_responsive_control(
    'ucf7e_select_padding',
    [
        'label' => __( 'Padding', 'nahian-ultimate-cf7-elementor' ),
        'type' => Controls_Manager::DIMENSIONS,
        'size_units' => [ 'px', 'em', '%' ],
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
    ]
);

$this->add_responsive_control(
    'ucf7e_select_width',
    [
        'label' => __( 'Width', 'nahian-ultimate-cf7-elementor' ),
        'type' => Controls_Manager::SLIDER,
        'size_units' => [ '%', 'px', 'em' ],
        'range' => [
            '%' => [ 'min' => 10, 'max' => 100 ],
            'px' => [ 'min' => 50, 'max' => 1200 ],
            'em' => [ 'min' => 5, 'max' => 80 ],
        ],
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 select' => 'width: {{SIZE}}{{UNIT}};',
        ],
    ]
);

$this->add_responsive_control(
    'ucf7e_select_height',
    [
        'label' => __( 'Height', 'nahian-ultimate-cf7-elementor' ),
        'type' => Controls_Manager::SLIDER,
        'size_units' => [ 'px', 'em' ],
        'range' => [
            'px' => [ 'min' => 20, 'max' => 300 ],
            'em' => [ 'min' => 1, 'max' => 30 ],
        ],
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 select' => 'height: {{SIZE}}{{UNIT}};',
        ],
    ]
);

$this->end_controls_section();


        // ---------------- Radio Buttons ----------------
$this->start_controls_section(
    'ucf7e_radio_field',
    [
        'label' => __( 'Radio Buttons', 'nahian-ultimate-cf7-elementor' ),
        'tab'   => Controls_Manager::TAB_STYLE,
    ]
);

// Radio Size
$this->add_responsive_control(
    'ucf7e_radio_size',
    [
        'label' => __( 'Size', 'nahian-ultimate-cf7-elementor' ),
        'type' => Controls_Manager::SLIDER,
        'size_units' => [ 'px' ],
        'range' => [
            'px' => [ 'min' => 10, 'max' => 50 ],
        ],
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="radio"]' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
        ],
    ]
);

// Radio Color (Normal)
$this->add_control(
    'ucf7e_radio_color',
    [
        'label'     => __( 'Radio Color', 'nahian-ultimate-cf7-elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="radio"]' => 'accent-color: {{VALUE}};',
        ],
    ]
);

// Radio Label Typography
$this->add_group_control(
    Group_Control_Typography::get_type(),
    [
        'name'     => 'ucf7e_radio_label_typography',
        'selector' => '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="radio"] + span',
    ]
);

// Radio Label Color
$this->add_control(
    'ucf7e_radio_label_color',
    [
        'label'     => __( 'Label Color', 'nahian-ultimate-cf7-elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="radio"] + span' => 'color: {{VALUE}};',
        ],
    ]
);

// Gap between radio and label
$this->add_responsive_control(
    'ucf7e_radio_gap',
    [
        'label' => __( 'Gap Between Radio & Label', 'nahian-ultimate-cf7-elementor' ),
        'type' => Controls_Manager::SLIDER,
        'size_units' => [ 'px' ],
        'range' => [
            'px' => [ 'min' => 0, 'max' => 50 ],
        ],
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="radio"] + span' => 'margin-left: {{SIZE}}{{UNIT}};',
        ],
    ]
);

$this->end_controls_section();

// ---------------- Checkbox ----------------
$this->start_controls_section(
    'ucf7e_checkbox_field',
    [
        'label' => __( 'Checkboxes', 'nahian-ultimate-cf7-elementor' ),
        'tab'   => Controls_Manager::TAB_STYLE,
    ]
);

// Checkbox Size
$this->add_responsive_control(
    'ucf7e_checkbox_size',
    [
        'label' => __( 'Size', 'nahian-ultimate-cf7-elementor' ),
        'type' => Controls_Manager::SLIDER,
        'size_units' => [ 'px' ],
        'range' => [
            'px' => [ 'min' => 10, 'max' => 50 ],
        ],
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="checkbox"]' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
        ],
    ]
);

// Checkbox Color (Normal)
$this->add_control(
    'ucf7e_checkbox_color',
    [
        'label'     => __( 'Checkbox Color', 'nahian-ultimate-cf7-elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="checkbox"]' => 'accent-color: {{VALUE}};',
        ],
    ]
);

// Checkbox Label Typography
$this->add_group_control(
    Group_Control_Typography::get_type(),
    [
        'name'     => 'ucf7e_checkbox_label_typography',
        'selector' => '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="checkbox"] + span',
    ]
);

// Checkbox Label Color
$this->add_control(
    'ucf7e_checkbox_label_color',
    [
        'label'     => __( 'Label Color', 'nahian-ultimate-cf7-elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="checkbox"] + span' => 'color: {{VALUE}};',
        ],
    ]
);

// Gap between checkbox and label
$this->add_responsive_control(
    'ucf7e_checkbox_gap',
    [
        'label' => __( 'Gap Between Checkbox & Label', 'nahian-ultimate-cf7-elementor' ),
        'type' => Controls_Manager::SLIDER,
        'size_units' => [ 'px' ],
        'range' => [
            'px' => [ 'min' => 0, 'max' => 50 ],
        ],
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="checkbox"] + span' => 'margin-left: {{SIZE}}{{UNIT}};',
        ],
    ]
);

$this->end_controls_section();


        // ---------------- Submit Button ----------------
$this->start_controls_section(
    'ucf7e_button_style',
    [
        'label' => __( 'Submit Button', 'nahian-ultimate-cf7-elementor' ),
        'tab'   => Controls_Manager::TAB_STYLE,
    ]
);

$this->start_controls_tabs( 'ucf7e_button_tabs' );

// ---------------- Normal ----------------
$this->start_controls_tab( 'ucf7e_button_normal', 
    [ 
        'label' => __( 'Normal', 'nahian-ultimate-cf7-elementor' ) 
    ] 
);

// Typography
$this->add_group_control(
    \Elementor\Group_Control_Typography::get_type(),
    [
        'name'     => 'ucf7e_button_typography',
        'selector' => '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="submit"]',
    ]
);

$this->add_control(
    'ucf7e_button_text_color',
    [
        'label'     => __( 'Text Color', 'nahian-ultimate-cf7-elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="submit"]' => 'color: {{VALUE}};',
        ],
    ]
);

$this->add_control(
    'ucf7e_button_bg_color',
    [
        'label'     => __( 'Background', 'nahian-ultimate-cf7-elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="submit"]' => 'background-color: {{VALUE}};',
        ],
    ]
);

// Border
$this->add_group_control(
    \Elementor\Group_Control_Border::get_type(),
    [
        'name'     => 'ucf7e_button_border',
        'selector' => '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="submit"]',
    ]
);

$this->add_control(
            'ucf7e_button_radius',
            [
                'label'     => __( 'Border Radius', 'nahian-ultimate-cf7-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="submit"]' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

// Padding
$this->add_responsive_control(
    'ucf7e_button_padding',
    [
        'label'      => __( 'Padding', 'nahian-ultimate-cf7-elementor' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => [ 'px', '%', 'em' ],
        'selectors'  => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
    ]
);


// ✅ Width Control
$this->add_responsive_control(
    'ucf7e_button_width',
    [
        'label' => __( 'Width', 'nahian-ultimate-cf7-elementor' ),
        'type' => Controls_Manager::SLIDER,
        'size_units' => [ '%', 'px', 'em' ],
        'range' => [
            '%' => [ 'min' => 10, 'max' => 100 ],
            'px' => [ 'min' => 50, 'max' => 1000 ],
            'em' => [ 'min' => 5, 'max' => 50 ],
        ],
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="submit"]' => 'width: {{SIZE}}{{UNIT}} !important;',
        ],
    ]
);

// ✅ Height Control
$this->add_responsive_control(
    'ucf7e_button_height',
    [
        'label' => __( 'Height', 'nahian-ultimate-cf7-elementor' ),
        'type' => Controls_Manager::SLIDER,
        'size_units' => [ 'px', 'em' ],
        'range' => [
            'px' => [ 'min' => 20, 'max' => 200 ],
            'em' => [ 'min' => 1, 'max' => 20 ],
        ],
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="submit"]' => 'height: {{SIZE}}{{UNIT}} !important;',
        ],
    ]
);

$this->end_controls_tab();

// ---------------- Hover ----------------
$this->start_controls_tab( 'ucf7e_button_hover', [ 'label' => __( 'Hover', 'nahian-ultimate-cf7-elementor' ) ] );

$this->add_control(
    'ucf7e_button_hover_color',
    [
        'label'     => __( 'Text Hover Color', 'nahian-ultimate-cf7-elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="submit"]:hover' => 'color: {{VALUE}};',
        ],
    ]
);

$this->add_control(
    'ucf7e_button_bg_hover_color',
    [
        'label'     => __( 'Background', 'nahian-ultimate-cf7-elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="submit"]:hover' => 'background-color: {{VALUE}};',
        ],
    ]
);

// Hover Border Color
$this->add_control(
    'ucf7e_button_hover_border_color',
    [
        'label'     => __( 'Border Hover Color', 'nahian-ultimate-cf7-elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="submit"]:hover' => 'border-color: {{VALUE}};',
        ],
    ]
);

// Transition
$this->add_control(
    'ucf7e_button_transition',
    [
        'label' => __( 'Transition Duration', 'nahian-ultimate-cf7-elementor' ),
        'type'  => Controls_Manager::NUMBER,
        'default' => 300,
        'description' => __( 'Transition duration in milliseconds', 'nahian-ultimate-cf7-elementor' ),
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7 input[type="submit"]' => 'transition: all {{VALUE}}ms ease;',
        ],
    ]
);

$this->end_controls_tab();
$this->end_controls_tabs();
$this->end_controls_section();


        // ---------------- Messages ----------------
$this->start_controls_section(
    'ucf7e_messages_style',
    [
        'label' => __( 'Messages', 'nahian-ultimate-cf7-elementor' ),
        'tab'   => Controls_Manager::TAB_STYLE,
    ]
);

// ---------------- Success Message ----------------
$this->start_controls_tabs( 'ucf7e_success_tabs' );

// Normal
$this->start_controls_tab(
    'ucf7e_success_normal',
    [ 'label' => __( 'Success', 'nahian-ultimate-cf7-elementor' ) ]
);

$this->add_control(
    'ucf7e_success_text_color',
    [
        'label'     => __( 'Text Color', 'nahian-ultimate-cf7-elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7-mail-sent-ok' => 'color: {{VALUE}};',
        ],
    ]
);

$this->add_control(
    'ucf7e_success_bg_color',
    [
        'label'     => __( 'Background Color', 'nahian-ultimate-cf7-elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7-mail-sent-ok' => 'background-color: {{VALUE}};',
        ],
    ]
);

$this->add_group_control(
    Group_Control_Border::get_type(),
    [
        'name'     => 'ucf7e_success_border',
        'selector' => '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7-mail-sent-ok',
    ]
);

$this->add_group_control(
    Group_Control_Typography::get_type(),
    [
        'name'     => 'ucf7e_success_typography',
        'selector' => '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7-mail-sent-ok',
    ]
);

$this->end_controls_tab();

// ---------------- Error Message ----------------
$this->start_controls_tab(
    'ucf7e_error_normal',
    [ 'label' => __( 'Error', 'nahian-ultimate-cf7-elementor' ) ]
);

$this->add_control(
    'ucf7e_error_text_color',
    [
        'label'     => __( 'Text Color', 'nahian-ultimate-cf7-elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7-validation-errors' => 'color: {{VALUE}};',
        ],
    ]
);

$this->add_control(
    'ucf7e_error_bg_color',
    [
        'label'     => __( 'Background Color', 'nahian-ultimate-cf7-elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7-validation-errors' => 'background-color: {{VALUE}};',
        ],
    ]
);

$this->add_group_control(
    Group_Control_Border::get_type(),
    [
        'name'     => 'ucf7e_error_border',
        'selector' => '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7-validation-errors',
    ]
);

$this->add_group_control(
    Group_Control_Typography::get_type(),
    [
        'name'     => 'ucf7e_error_typography',
        'selector' => '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7-validation-errors',
    ]
);

$this->end_controls_tab();

// ---------------- Validation Tip ----------------
$this->start_controls_tab(
    'ucf7e_validation_tab',
    [ 'label' => __( 'Tips', 'nahian-ultimate-cf7-elementor' ) ]
);

$this->add_control(
    'ucf7e_validation_text_color',
    [
        'label'     => __( 'Text Color', 'nahian-ultimate-cf7-elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7-not-valid-tip' => 'color: {{VALUE}};',
        ],
    ]
);

$this->add_control(
    'ucf7e_validation_bg_color',
    [
        'label'     => __( 'Background Color', 'nahian-ultimate-cf7-elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7-not-valid-tip' => 'background-color: {{VALUE}};',
        ],
    ]
);

$this->add_group_control(
    Group_Control_Border::get_type(),
    [
        'name'     => 'ucf7e_validation_border',
        'selector' => '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7-not-valid-tip',
    ]
);

$this->add_group_control(
    Group_Control_Typography::get_type(),
    [
        'name'     => 'ucf7e_validation_typography',
        'selector' => '{{WRAPPER}} .ucf7e-widget-wrapper .wpcf7-not-valid-tip',
    ]
);

$this->end_controls_tab();
$this->end_controls_tabs();

$this->end_controls_section();


    }

    private function get_all_cf7_forms() {
        $forms = get_posts( [
            'post_type'      => 'wpcf7_contact_form',
            'posts_per_page' => -1,
        ] );

        $options = [];
        foreach ( $forms as $form ) {
            $options[ $form->ID ] = $form->post_title;
        }

        return $options;
    }

    protected function render() {
    $settings = $this->get_settings_for_display();
    $form_id  = ! empty( $settings['ucf7e_form_id'] ) ? intval( $settings['ucf7e_form_id'] ) : 0;

        if ( $form_id ) {
            echo '<div class="ucf7e-widget-wrapper">';
            echo do_shortcode( '[contact-form-7 id="' . $form_id . '"]' );
            echo '</div>';

            // Pass redirect data to JS
            if ( ! empty( $settings['ucf7e_enable_ajax'] ) 
                && $settings['ucf7e_enable_ajax'] === 'yes' 
                && ! empty( $settings['ucf7e_redirect_url']['url'] ) ) {

                $redirect_url = esc_url( $settings['ucf7e_redirect_url']['url'] );

                wp_localize_script(
                    'ucf7e-scripts',
                    'ucf7eRedirectData',
                    [
                        'formId'      => $form_id,
                        'redirectUrl' => $redirect_url,
                    ]
                );
            }
        } else {
            echo '<p>' . esc_html__( 'Please select a Contact Form 7 form.', 'nahian-ultimate-cf7-elementor' ) . '</p>';
        }
    }
}
