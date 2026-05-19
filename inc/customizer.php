<?php
/**
 * Customizer settings and controls.
 *
 * @package Ifende
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * Register Customizer settings.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
 */
function ifende_customizer( $wp_customize ) {
  $wp_customize->add_panel( 'ifende_panel', [
    'title'    => esc_html__( 'Ifende Portfolio Options', 'ifende' ),
    'priority' => 30,
  ] );

  // --- HERO SECTION ---
  $wp_customize->add_section( 'ifende_hero', [
    'title' => esc_html__( 'Hero Section', 'ifende' ),
    'panel' => 'ifende_panel',
  ] );

  $hero = [
    'hero_label'     => [ 'Tagline', 'Based in Nigeria · Available Globally' ],
    'hero_name'      => [ 'Your Name', 'Onyemechi Ifende' ],
    'hero_roles'     => [ 'Roles (pipe separated)', 'Project Manager|Web Developer|Consultant' ],
    'hero_bio'       => [ 'Short Bio', 'A multi-disciplinary professional with rich experience in project management, web development, consulting, and branding.' ],
    'hero_stat1_n'   => [ 'Stat 1 Number', '12+' ],
    'hero_stat1_l'   => [ 'Stat 1 Label', 'Clients Served' ],
    'hero_stat2_n'   => [ 'Stat 2 Number', '5+' ],
    'hero_stat2_l'   => [ 'Stat 2 Label', 'Years Experience' ],
    'hero_stat3_n'   => [ 'Stat 3 Number', '4' ],
    'hero_stat3_l'   => [ 'Stat 3 Label', 'Core Services' ],
    'hero_status'    => [ 'Availability Status', 'Available for Freelance' ],
    'hero_photo_url' => [ 'Photo URL', '' ],
  ];

  foreach ( $hero as $k => [ $l, $d ] ) {
    $wp_customize->add_setting( "ifende_$k", [
      'default'           => $d,
      'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( "ifende_$k", [
      'label'   => $l,
      'section' => 'ifende_hero',
      'type'    => 'text',
    ] );
  }


  // --- ABOUT SECTION ---
  $wp_customize->add_section( 'ifende_about', [
    'title' => esc_html__( 'About Section', 'ifende' ),
    'panel' => 'ifende_panel',
  ] );

  $about = [
    'about_bio'         => [ 'Full Bio', "Hello! I'm Onyemechi Ifende — a Project Manager, Consultant, Web Developer, and Freelancer from Nigeria." ],
    'about_location'    => [ 'Location', 'Global — Based in Nigeria' ],
    'about_freelance'   => [ 'Freelance Status', 'Currently Available' ],
    'about_twitter'     => [ 'Twitter Handle', '@ifende' ],
    'about_twitter_url' => [ 'Twitter URL', 'https://twitter.com/ifende' ],
    'about_skills'      => [ 'Skills (comma separated)', 'WordPress,Project Management,Web Design,Consulting,Branding,Game Dev,Remote Ops,Team Leadership' ],
  ];

  foreach ( $about as $k => [ $l, $d ] ) {
    $wp_customize->add_setting( "ifende_$k", [
      'default'           => $d,
      'sanitize_callback' => 'sanitize_textarea_field',
    ] );
    $wp_customize->add_control( "ifende_$k", [
      'label'   => $l,
      'section' => 'ifende_about',
      'type'    => 'textarea',
    ] );
  }


  // --- MARQUEE SECTION ---
  $wp_customize->add_section( 'ifende_marquee', [
    'title'       => esc_html__( 'Marquee / Scrolling Text', 'ifende' ),
    'panel'       => 'ifende_panel',
    'description' => esc_html__( 'Comma-separated list of items displayed in the scrolling marquee bar.', 'ifende' ),
  ] );

  $wp_customize->add_setting( 'ifende_marquee_items', [
    'default'           => 'Project Management,Web Development,Consulting,Branding,Game Development,Remote Operations,WordPress,Digital Strategy',
    'sanitize_callback' => 'sanitize_textarea_field',
  ] );
  $wp_customize->add_control( 'ifende_marquee_items', [
    'label'   => esc_html__( 'Marquee Items (comma separated)', 'ifende' ),
    'section' => 'ifende_marquee',
    'type'    => 'textarea',
  ] );

  // --- SERVICES SECTION ---
  $wp_customize->add_section( 'ifende_services', [
    'title'       => esc_html__( 'Services Section', 'ifende' ),
    'panel'       => 'ifende_panel',
    'description' => esc_html__( 'Configure up to 4 services. Each service has an icon, title, and description.', 'ifende' ),
  ] );

  $services_defaults = [
    1 => [ 'icon' => '🌐', 'title' => 'Web Development', 'desc' => 'I develop unique web presences that deliver your dream concepts to life. Your website designed and built to your specifications — not just websites, but dreams and relationships. Built on WordPress and modern web technologies.' ],
    2 => [ 'icon' => '🎯', 'title' => 'Consulting', 'desc' => 'I consult on various business processes giving clients a holistic experience. My aim is to be your one-stop spot for your virtual enterprise — maximising value and advocating for growth at every step.' ],
    3 => [ 'icon' => '✦', 'title' => 'Branding', 'desc' => 'Branding is what distinguishes you from your competitors and affects your bottom line. Your brand needs to be memorable and distinctive — my design approach has in-depth knowledge of marketing strategies.' ],
    4 => [ 'icon' => '🎮', 'title' => 'Game Development', 'desc' => 'Developing memorable and unique mobile games for Android, iOS, and video game platforms. I create immersive gaming experiences that engage, entertain, and leave lasting impressions on players.' ],
  ];

  foreach ( $services_defaults as $i => $svc ) {
    $num = str_pad( $i, 2, '0', STR_PAD_LEFT );

    $wp_customize->add_setting( "ifende_service_{$i}_icon", [
      'default'           => $svc['icon'],
      'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( "ifende_service_{$i}_icon", [
      'label'   => sprintf( esc_html__( 'Service %d Icon (emoji)', 'ifende' ), $i ),
      'section' => 'ifende_services',
      'type'    => 'text',
    ] );

    $wp_customize->add_setting( "ifende_service_{$i}_title", [
      'default'           => $svc['title'],
      'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( "ifende_service_{$i}_title", [
      'label'   => sprintf( esc_html__( 'Service %d Title', 'ifende' ), $i ),
      'section' => 'ifende_services',
      'type'    => 'text',
    ] );

    $wp_customize->add_setting( "ifende_service_{$i}_desc", [
      'default'           => $svc['desc'],
      'sanitize_callback' => 'sanitize_textarea_field',
    ] );
    $wp_customize->add_control( "ifende_service_{$i}_desc", [
      'label'   => sprintf( esc_html__( 'Service %d Description', 'ifende' ), $i ),
      'section' => 'ifende_services',
      'type'    => 'textarea',
    ] );
  }


  // --- CLIENTS SECTION ---
  $wp_customize->add_section( 'ifende_clients', [
    'title'       => esc_html__( 'Clients / Partners', 'ifende' ),
    'panel'       => 'ifende_panel',
    'description' => esc_html__( 'Enter clients as a structured list. Format per line: Name|URL|Emoji', 'ifende' ),
  ] );

  $clients_default = "Leadetics|https://leadetics.ng/|🔷\nLibertyhub|https://libertyhub.ng|🟢\nVTLeasing Limited|https://vtleasing.com/|🔵\nStratagem Legal|https://stratagemlp.com/|⚖️\nFort Solutions|https://fortsolutions.net|🏗️\nLiberty Mall|https://libertymall.ng|🛍️\nLibertyhub MCS|https://libertyhubmcs.ng|🤝\nPortal Consultancy|https://portalconsultancy.com.ng/|📋\nCFHRAD|https://cfhrad.org/|🏥\nJos Water Services|https://www.jwsc.pl.gov.ng/|💧\nLiberty Matrix|http://libertymatrix.ng/|🔗\nUrban Bounty MCS|#|🌱";

  $wp_customize->add_setting( 'ifende_clients_list', [
    'default'           => $clients_default,
    'sanitize_callback' => 'sanitize_textarea_field',
  ] );
  $wp_customize->add_control( 'ifende_clients_list', [
    'label'       => esc_html__( 'Clients List (Name|URL|Emoji per line)', 'ifende' ),
    'section'     => 'ifende_clients',
    'type'        => 'textarea',
    'input_attrs' => [ 'rows' => 14 ],
  ] );

  $wp_customize->add_setting( 'ifende_clients_intro', [
    'default'           => 'A growing portfolio of businesses across Nigeria who trust me to deliver exceptional digital and consultancy work.',
    'sanitize_callback' => 'sanitize_textarea_field',
  ] );
  $wp_customize->add_control( 'ifende_clients_intro', [
    'label'   => esc_html__( 'Clients Section Description', 'ifende' ),
    'section' => 'ifende_clients',
    'type'    => 'textarea',
  ] );


  // --- CONTACT & FORMS ---
  $wp_customize->add_section( 'ifende_contact', [
    'title' => esc_html__( 'Contact & Forms', 'ifende' ),
    'panel' => 'ifende_panel',
  ] );

  $contact = [
    'email'         => [ 'Email Address', 'hello@ifende.com' ],
    'instagram_url' => [ 'Instagram URL', 'https://instagram.com/onyema.ifende' ],
    'twitter_url'   => [ 'Twitter/X URL', 'https://twitter.com/ifende' ],
    'formspree_id'  => [ 'Formspree Form ID', '' ],
    'web3forms_key' => [ 'Web3Forms Access Key', '' ],
  ];

  foreach ( $contact as $k => [ $l, $d ] ) {
    $wp_customize->add_setting( "ifende_$k", [
      'default'           => $d,
      'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( "ifende_$k", [
      'label'   => $l,
      'section' => 'ifende_contact',
      'type'    => 'text',
    ] );
  }
}
add_action( 'customize_register', 'ifende_customizer' );
