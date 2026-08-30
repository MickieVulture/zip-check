<?php
/** Settings storage and sanitization. @package DVLNT_Service_Area_Checker */
if ( ! defined( 'ABSPATH' ) ) { exit; }

class DVLNT_SAC_Settings {
	const OPTION_NAME = 'dvlnt_sac_settings';
	public static function init() { add_action( 'admin_init', array( __CLASS__, 'register' ) ); }
	public static function defaults() {
		return array(
			'zip_codes'=>'','phone'=>'','booking_url'=>'','initial_eyebrow'=>'SERVICE AVAILABILITY','initial_heading'=>'Do we service your area?','initial_description'=>'Enter your ZIP code to check service availability.','zip_label'=>'ZIP Code','zip_placeholder'=>'91301','check_label'=>'Check Availability','success_eyebrow'=>'SERVICE AVAILABLE','success_heading'=>'Yes, we service your area!','success_description'=>'Great news—we provide service in [ZIP].','success_reassurance'=>'Friendly, dependable service is just a click away.','booking_label'=>'Book Service','call_label'=>'Call Now','unavailable_eyebrow'=>'SERVICE AREA UPDATE','unavailable_heading'=>'We’re not in your area yet.','unavailable_description'=>'We do not currently provide service in [ZIP], but our service area is always growing.','retry_label'=>'Check Another ZIP Code',
			'font_family'=>'Lato','eyebrow_font_size'=>'16','eyebrow_font_weight'=>'400','heading_size_desktop'=>'42','heading_size_tablet'=>'30','heading_size_mobile'=>'28','heading_font_weight'=>'700','paragraph_font_size'=>'16','paragraph_font_weight'=>'400','button_font_size'=>'16','button_font_weight'=>'700','label_font_size'=>'16','label_font_weight'=>'700',
			'accent_color'=>'#0EA5E9','heading_color'=>'#09090B','body_color'=>'#404040','eyebrow_color'=>'#0EA5E9','button_background'=>'#0EA5E9','button_text_color'=>'#FFFFFF','input_border_color'=>'#4DB7FF','modal_background'=>'#FFFFFF','modal_border_color'=>'#CFE8FA','overlay_color'=>'#F1F5F9','success_color'=>'#15803D','error_color'=>'#B91C1C',
			'border_radius'=>'22','button_radius'=>'10','input_radius'=>'10','modal_max_width'=>'440','modal_padding'=>'40','button_height'=>'50',
		);
	}
	public static function appearance_keys() { return array('font_family','eyebrow_font_size','eyebrow_font_weight','heading_size_desktop','heading_size_tablet','heading_size_mobile','heading_font_weight','paragraph_font_size','paragraph_font_weight','button_font_size','button_font_weight','label_font_size','label_font_weight','accent_color','heading_color','body_color','eyebrow_color','button_background','button_text_color','input_border_color','modal_background','modal_border_color','overlay_color','success_color','error_color','border_radius','button_radius','input_radius','modal_max_width','modal_padding','button_height'); }
	public static function get() { $saved=get_option(self::OPTION_NAME,array()); $saved=is_array($saved)?$saved:array(); if(isset($saved['accent_color'])){foreach(array('eyebrow_color','button_background') as $key){if(!isset($saved[$key])){$saved[$key]=$saved['accent_color'];}}} return wp_parse_args($saved,self::defaults()); }
	public static function register() { register_setting('dvlnt_sac_group',self::OPTION_NAME,array('type'=>'array','sanitize_callback'=>array(__CLASS__,'sanitize'),'default'=>self::defaults())); }
	public static function normalize_zip_codes($value) { $parts=preg_split('/[\s,]+/',(string)$value,-1,PREG_SPLIT_NO_EMPTY); $valid=array(); foreach($parts as $part){$zip=trim($part);if(preg_match('/^\d{5}$/',$zip)){$valid[$zip]=$zip;}} return implode("\n",array_values($valid)); }
	private static function ranged_int($value,$default,$min,$max) { $value=filter_var($value,FILTER_VALIDATE_INT); return (string)(false===$value?$default:min($max,max($min,$value))); }
	public static function sanitize($input) {
		$input=is_array($input)?$input:array(); $d=self::defaults(); $o=array();
		$o['zip_codes']=self::normalize_zip_codes($input['zip_codes']??''); $o['phone']=sanitize_text_field($input['phone']??''); $o['booking_url']=esc_url_raw($input['booking_url']??'');
		$text=array('initial_eyebrow','initial_heading','initial_description','zip_label','zip_placeholder','check_label','success_eyebrow','success_heading','success_description','success_reassurance','booking_label','call_label','unavailable_eyebrow','unavailable_heading','unavailable_description','retry_label'); foreach($text as $key){$o[$key]=sanitize_text_field($input[$key]??$d[$key]);}
		$fonts=array('Lato','Arial','Helvetica','Open Sans','Roboto','Montserrat','Poppins','Inter','system-ui'); $o['font_family']=in_array($input['font_family']??'',$fonts,true)?$input['font_family']:$d['font_family'];
		$weights=array('400','500','600','700','800','900'); foreach(array('eyebrow_font_weight','heading_font_weight','paragraph_font_weight','button_font_weight','label_font_weight') as $key){$o[$key]=in_array((string)($input[$key]??''),$weights,true)?(string)$input[$key]:$d[$key];}
		$ranges=array('eyebrow_font_size'=>array(10,30),'heading_size_desktop'=>array(20,72),'heading_size_tablet'=>array(18,60),'heading_size_mobile'=>array(18,52),'paragraph_font_size'=>array(12,30),'button_font_size'=>array(12,28),'label_font_size'=>array(12,28),'border_radius'=>array(0,48),'button_radius'=>array(0,40),'input_radius'=>array(0,40),'modal_max_width'=>array(320,800),'modal_padding'=>array(16,80),'button_height'=>array(40,80)); foreach($ranges as $key=>$range){$o[$key]=self::ranged_int($input[$key]??$d[$key],$d[$key],$range[0],$range[1]);}
		$colors=array('accent_color','heading_color','body_color','eyebrow_color','button_background','button_text_color','input_border_color','modal_background','modal_border_color','overlay_color','success_color','error_color'); foreach($colors as $key){$color=sanitize_hex_color($input[$key]??'');$o[$key]=$color?strtoupper($color):$d[$key];}
		return $o;
	}
}
