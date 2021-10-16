<?php 
namespace wc_video\admin\form_fields;
if ( ! defined( 'ABSPATH' ) ) {
    exit;
  }
  
if( ! class_exists( 'thisPluginField' ) )
{
    class thisPluginField
    {
        public function __construct()
        {
  
        }
        public function get_fields_form($formID){
            $fields= [];
			if ($formID == 'test_form_mini') {
				$fields=[
					[
						'headline' => 'Nadpis formuláře',
						'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
						[
							'type' => 'email',
							'name' => 'email1',
							'label' => 'E-mail',
							'floating_label' => true,
							'saveAs' => 'meta',
							'required' => true,
						],
						/*[
							'type' => 'range',
							'name' => 'range2',
							'label' => 'Výběr hodnoty s danou jednotkou',
							'description' => 'V tomto výběru je daná jednotkou a zobrazuje se minimální a maximální hodnota',
							'help_text' => 'Defaultně nastavena hodnota 1250 px',
							'max' => 2400,
							'min' => 0,
							'value' => 1250,
							'unit' => 'px',
							'show_attr' => true,
							'saveAs' => 'meta',
						],*/
						[
							'type' => 'range',
							'name' => 'range3',
							'label' => 'Výběr hodnoty s výběrem jednotky',
							'help_text' => 'Defaultně nastavena hodnota 1250 px',
							'max' => 2400,
							'min' => 0,
							'value' => 1250,
							'unit' => [
								'px' => 'px',
								'%' => '%',
								'rem' => 'rem',
								'em' => 'em',
								'vh' => 'vh',
								'vw' => 'vw',
							],
							'saveAs' => 'meta',
						],
						[
							'type' => 'switch',
							'name' => 'switch1',
							'label' => 'Checkbox as switch',
							'saveAs' => 'meta',
						],
						[
							'type' => 'checkbox',
							'name' => 'checkbox2',
							'label' => 'Checkbox Lorem ipsum dolor sit amet',
							'saveAs' => 'meta',
						],
						[
							'type' => 'checkbox',
							'name' => 'checkboxMulti',
							'label' => 'Checkbox Lorem ipsum dolor sit amet',
							'saveAs' => 'meta',
							'options' => [
								'check1' => 'Lorem ipsum dolor sit amet', // label, help text
								'check2' => 'Lorem ipsum dolor sit amet',
								'check3' => 'Lorem ipsum dolor sit amet',
								'check4' => 'Lorem ipsum dolor sit amet',
		
							],
						]
					],
				];
			} else if ($formID == 'test_form_mini_2') {
				$fields=[
					[
						'headline' => 'Nadis formuláře',
						'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
						[
							'type' => 'email',
							'name' => 'email2',
							'label' => 'E-mail',
							'floating_label' => true,
							'saveAs' => 'meta',
							'required' => true,
						],
						[
							'type' => 'range',
							'name' => 'range',
							'label' => 'Výběr hodnoty s výběrem jednotky',
							'help_text' => 'Defaultně nastavena hodnota 1250 px',
							'max' => 400,
							'min' => 0,
							'value' => 250,
							'unit' => [
								'px' => 'px',
								'%' => '%',
								'rem' => 'rem',
								'em' => 'em',
								'vh' => 'vh',
								'vw' => 'vw',
							],
							'saveAs' => 'meta',
						],
						[
							'type' => 'checkbox',
							'name' => 'checkbox2',
							'label' => 'Checkbox Lorem ipsum dolor sit amet',
							'saveAs' => 'meta',
						],
						[
							'type' => 'url',
							'name' => 'odkaz',
							'label' => 'Url',
							'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Senectus libero ut lorem ac dictumst phasellus nunc sit. Eu nisi sed viverra id aliquam enim, odio nunc.',
							'help_text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
							'floating_label' => true,
							'saveAs' => 'meta',
							'required' => true,
						],
					],
				];
			} else if ($formID == 'emails_settings') {
				$fields=[
					[
						'headline' => 'Nastavení emailů',
						'description' => 'Pro správné používání emailů je potřeba přizpůsobit jejich vzhled a nastavení.',
						[
							'type' => 'switch',
							'name' => 'switch',
							'label' => 'Aktivace emailů',
							'description' => 'Zaškrtnutí tlačítka aktivuje odesílání emailů',
							'saveAs' => 'meta',
						],
					],
					[           
                        'headline' => 'Nastavení vzhledu emailu',     
						'columns' => [   
							[
								[
									'type' => 'color',
									'name' => 'footer_bg_color',
									'label' => 'Barva pozadí zápatí',
									'help_text' => 'Vyberte si barvu, kterou bude mít zápatí emailů',
									'value' => '#0a0a0a', // rgba(30,30,30,0.64)
									'saveAs' => 'meta',
									'required' => true,
								]
							],
							[
								[
									'type' => 'color',
									'name' => 'footer_color',
									'label' => 'Barva textu zápatí',
									'help_text' => 'Vyberte si barvu, kterou bude mít text  v zápatí emailů',
									'value' => '#ff00dd', // rgba(30,30,30,0.64)
									'saveAs' => 'meta',
									'required' => true,
								]
							]
						],
                        [
                            'type' => 'image',
                            'name' => 'header_logo',
                            'label' => 'Logo do hlavičky emailu',
                            'description' => 'Chcete-li vložit do emailu své logo, zde ho nahrajte',
                            'help_text' => 'Chcete-li vložit do emailu své logo, zde ho nahrajte. Nahrávejte ve formátu jpg, nebo png o šířce alespoň 200px',
                            'value' => '',
                            'floating_label' => true,
                            'saveAs' => 'meta'
                        ],     
						[
                            'type' => 'text',
                            'name' => 'reply_to_email',
                            'saveAs' => 'meta',
                            'label' => 'Email pro odpověď',
                            'help_text' => 'Vyplňte email, na který má přijít odpověď',
                        ]
                    ],
					[           
                        'headline' => 'Nastavení textace jednotlivých emailů',     
						[
                            'type' => 'editor',
                            'name' => 'mail_text_license_wds',
                            'label' => 'Text emailu',
                            'description' => 'Text emailu',
                            'help_text' => 'Pomocný text pro editor',
                            'floating_label' => true,
                            'saveAs' => 'meta',
                        ],
						[
                            'type' => 'text',
                            'name' => 'license_email_subject',
                            'saveAs' => 'meta',
                            'label' => 'Předmět emailu',
                            'help_text' => 'Vyplňte předmět, který má být uvedem v emailu s odeslanou licencí',
                        ]
					]
				];
			}
			return $fields;
        
        }
        public function get_fields_cpt_form($post_type){
            $fields= [];
            if ($post_type == 'license') {
                $fields=[
                    [
                        'type' => 'text',
                        'name' => 'license_author_email_wds',
                        'saveAs' => 'meta',
                        'label' => 'Email zákazníka',
                    ],
                    [
                        'type' => 'text',
                        'name' => 'transaction_ID_wds',
                        'saveAs' => 'meta',
                        'label' => 'ID Transakce',
                    ],
                    [
                        'type' => 'text',
                        'name' => 'license_ID_wds',
                        'saveAs' => 'meta',
                        'label' => 'Číslo licence',
                    ]
                ];
            }
            return $fields;
        }
    }
}

?>