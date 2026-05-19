<?php
if ( ! defined( 'ABSPATH' ) ) exit;
define( 'IFENDE_VERSION', '1.0.0' );
define( 'IFENDE_URI', get_template_directory_uri() );

function ifende_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo', ['height'=>60,'width'=>200,'flex-height'=>true,'flex-width'=>true] );
    add_theme_support( 'html5', ['search-form','comment-form','comment-list','gallery','caption'] );
    register_nav_menus(['primary' => 'Primary Navigation']);
}
add_action( 'after_setup_theme', 'ifende_setup' );

function ifende_enqueue() {
    wp_enqueue_style( 'ifende-main', IFENDE_URI . '/assets/css/main.css', [], IFENDE_VERSION );
    wp_enqueue_script( 'ifende-main', IFENDE_URI . '/assets/js/main.js', [], IFENDE_VERSION, true );
    wp_localize_script( 'ifende-main', 'ifendeData', [
        'ajaxUrl'   => admin_url('admin-ajax.php'),
        'nonce'     => wp_create_nonce('ifende_nonce'),
        'formspree' => get_theme_mod('ifende_formspree_id',''),
        'web3forms' => get_theme_mod('ifende_web3forms_key',''),
        'email'     => get_theme_mod('ifende_email','hello@ifende.com'),
    ]);
}
add_action( 'wp_enqueue_scripts', 'ifende_enqueue' );

function ifende_customizer( $wp_customize ) {
    $wp_customize->add_panel('ifende_panel', ['title'=>'Ifende Portfolio Options','priority'=>30]);

    $wp_customize->add_section('ifende_hero', ['title'=>'Hero Section','panel'=>'ifende_panel']);
    $hero = [
        'hero_label'     => ['Tagline','Based in Nigeria · Available Globally'],
        'hero_name'      => ['Your Name','Onyemechi Ifende'],
        'hero_roles'     => ['Roles (pipe separated)','Project Manager|Web Developer|Consultant'],
        'hero_bio'       => ['Short Bio','A multi-disciplinary professional with rich experience in project management, web development, consulting, and branding.'],
        'hero_stat1_n'   => ['Stat 1 Number','12+'],
        'hero_stat1_l'   => ['Stat 1 Label','Clients Served'],
        'hero_stat2_n'   => ['Stat 2 Number','5+'],
        'hero_stat2_l'   => ['Stat 2 Label','Years Experience'],
        'hero_stat3_n'   => ['Stat 3 Number','4'],
        'hero_stat3_l'   => ['Stat 3 Label','Core Services'],
        'hero_status'    => ['Availability Status','Available for Freelance'],
        'hero_photo_url' => ['Photo URL',''],
    ];
    foreach($hero as $k=>[$l,$d]){
        $wp_customize->add_setting("ifende_$k",['default'=>$d,'sanitize_callback'=>'sanitize_text_field']);
        $wp_customize->add_control("ifende_$k",['label'=>$l,'section'=>'ifende_hero','type'=>'text']);
    }

    $wp_customize->add_section('ifende_about',['title'=>'About Section','panel'=>'ifende_panel']);
    $about = [
        'about_bio'         => ['Full Bio',"Hello! I'm Onyemechi Ifende — a Project Manager, Consultant, Web Developer, and Freelancer from Nigeria."],
        'about_location'    => ['Location','Global — Based in Nigeria'],
        'about_freelance'   => ['Freelance Status','Currently Available'],
        'about_twitter'     => ['Twitter Handle','@ifende'],
        'about_twitter_url' => ['Twitter URL','https://twitter.com/ifende'],
        'about_skills'      => ['Skills (comma separated)','WordPress,Project Management,Web Design,Consulting,Branding,Game Dev,Remote Ops,Team Leadership'],
    ];
    foreach($about as $k=>[$l,$d]){
        $wp_customize->add_setting("ifende_$k",['default'=>$d,'sanitize_callback'=>'sanitize_textarea_field']);
        $wp_customize->add_control("ifende_$k",['label'=>$l,'section'=>'ifende_about','type'=>'textarea']);
    }

    $wp_customize->add_section('ifende_contact',['title'=>'Contact & Forms','panel'=>'ifende_panel']);
    $contact = [
        'email'          => ['Email Address','hello@ifende.com'],
        'instagram_url'  => ['Instagram URL','https://instagram.com/onyema.ifende'],
        'twitter_url'    => ['Twitter/X URL','https://twitter.com/ifende'],
        'formspree_id'   => ['Formspree Form ID',''],
        'web3forms_key'  => ['Web3Forms Access Key',''],
    ];
    foreach($contact as $k=>[$l,$d]){
        $wp_customize->add_setting("ifende_$k",['default'=>$d,'sanitize_callback'=>'sanitize_text_field']);
        $wp_customize->add_control("ifende_$k",['label'=>$l,'section'=>'ifende_contact','type'=>'text']);
    }
}
add_action( 'customize_register', 'ifende_customizer' );

function ifende_opt($key,$default=''){
    return get_theme_mod('ifende_'.$key,$default);
}

function ifende_handle_contact(){
    check_ajax_referer('ifende_nonce','nonce');
    $name    = sanitize_text_field($_POST['name'] ?? '');
    $email   = sanitize_email($_POST['email'] ?? '');
    $subject = sanitize_text_field($_POST['subject'] ?? 'Portfolio Enquiry');
    $message = sanitize_textarea_field($_POST['message'] ?? '');
    if(!$email||!$message){ wp_send_json_error(['message'=>'Required fields missing.']); }
    $to      = get_theme_mod('ifende_email','hello@ifende.com');
    $headers = ['Content-Type: text/html; charset=UTF-8','Reply-To: '.$name.' <'.$email.'>'];
    $body    = "<p><strong>From:</strong> {$name} &lt;{$email}&gt;</p><p><strong>Subject:</strong> {$subject}</p><p><strong>Message:</strong><br>".nl2br(esc_html($message))."</p>";
    $sent    = wp_mail($to,$subject,$body,$headers);
    $sent ? wp_send_json_success() : wp_send_json_error(['message'=>'Failed to send.']);
}
add_action('wp_ajax_nopriv_ifende_contact','ifende_handle_contact');
add_action('wp_ajax_ifende_contact','ifende_handle_contact');

add_action('after_switch_theme',function(){ flush_rewrite_rules(); });
