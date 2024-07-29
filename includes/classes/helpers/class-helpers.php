<?php

namespace ElementorCmAddons\classes\helpers;

use ElementorCmAddons\traits\Singleton;

class Helpers {

	use Singleton;

	public function get_country_options( $settings ): string {
		$country_array   = $this->get_country_array();
		$current_country = Location::get_country_iso();

		$valid  = $current_country ? 'valid' : '';
		$output = "<div class='cm-form-input-container cm-form-country-container'>
						<select id='countryiso2' style='font-family: " . $settings['font_family'] . ";' name='countryiso2' class='cm-form-country " . $valid . "'>";

		$output .= "<option value='' disabled hidden selected data-telephonecode='+'>${settings['country_placeholder']}</option>";
		foreach ( $country_array as $country ) {
			$selected     = $current_country && $current_country === $country['iso'] ? 'selected' : '';
			$language     = $settings['language'] === 'ar-SA' ? 'Ar' : '';
			$country_name = $country[ 'name' . $language ];
			$output      .= "<option value='" . $country['iso'] . "' data-telephonecode='" . $country['telephonecode'] . "' $selected>$country_name</option>";
		}

			$output .= "</select>
			<p id='countryiso2-error' class='cm-form-error' style='color: " . $settings['error_color'] . '; font-family: ' . $settings['font_family'] . ";'>" . $settings['country_error'] . '</p>
		</div>';

		return $output;
	}

	public function lang_settings( $settings, $lang ): array {
		switch ( $lang ) {
			case 'ar-SA':
				$langValues = $this->ar_lang_settings();
				break;
			case 'es-ES':
				$langValues = $this->es_lang_settings();
				break;
			default:
				$langValues = $this->en_lang_settings();
				break;
		}

		$result = null;
		foreach ( $settings as $key => $value ) {
			if ( !empty( $value ) ) {
				$result[ $key ] = $value;
			} elseif ( !empty( $langValues[ $key ] ) ) {
				$result[ $key ] = $langValues[ $key ];
			} else {
				$result[ $key ] = $value;
			}
		}
		return $result;
	}

	private function ar_lang_settings() {
		return [
			'firstname_error'    => 'الرجاء ادخل الإسم الأول',
			'lastname_error'     => 'الرجاء ادخل إسم العائلة',
			'email_error'        => 'الرجاء ادخل عنوان الايميل',
			'country_error'      => 'اختر البلد',
			'phone_error'        => 'هذه الخانة مطلوبه',
			'phone_digits_error' => 'الرجاء1دخال رقم هاتف من 6-12 ارقام',
			'agree_error'        => 'الرجاء الموافقة على الشروط والأحكام',
			'employment_error'   => 'empleyment_error_ar',
		];
	}

	private function en_lang_settings(): array {
		return [
			'firstname_placeholder' => 'First Name',
			'firstname_error' => 'Please Enter Valid First Name',
			'lastname_placeholder' => 'Last Name',
			'lastname_error' => 'Please Enter Valid Last Name',
			'email_placeholder' => 'Email Address',
			'email_error' => 'Please Enter Valid Email Address',
			'country_placeholder' => 'Select Country',
			'country_error' => 'Please Select Country',
			'phone_placeholder' => 'Enter Phone Number',
			'phone_error' => 'Please Enter Valid Phone Number',
			'phone_digits_error' => 'Please enter a 6-10 digits phone number',
			'agree_error' => '',
			'employment_placeholder' => 'Choose Employment',
			'employment_error' => 'Please Select Employment',
			'password_placeholder' => 'Password',
			'password_error' => 'Password Must have A lowercase letter, A capital letter, A number, and 8-12 characters. No special character',
			'birthday_placeholder' => 'Date of birth',
			'birthday_error' => 'You must be over 18 years to open a trading account',
			'promocode_placeholder' => 'Promocode',
			'promocode_error' => 'The promo code cannot contain more than 32 characters',
			'terms_text' => 'By checking this box I accept the <a href="#footer-anc">IB Partnership Agreement (T&amp;Cs)</a> and <a href="#footer-anc">Risk Disclosure Notice</a> and confirm that I am over 18 years of age.',
		];
	}

	private function es_lang_settings(): array {
		return [
			'firstname_placeholder' 	=> 'Nombre',
			'firstname_error'    		=> 'Introduzca un nombre válido, por favor',
			'lastname_placeholder' 		=> 'Apellido',
			'lastname_error'     		=> 'Inserte un apellido válido, por favor',
			'email_placeholder' 		=> 'Correo electrónico',
			'email_error'        		=> 'Inserte un correo electrónico válido, por favor',
			'country_placeholder' 		=> 'Seleccionar país',
			'country_error' 			=> 'Selecciona país, por favor',
			'phone_placeholder' 		=> 'Teléfono',
			'phone_error'        		=> 'Inserte un teléfono válido, por favor',
			'phone_digits_error' 		=> 'Ingrese un número de teléfono de 6-10 dígitos',
			'agree_error'        		=> 'Please Agree to our T&C',
			'employment_placeholder' 	=> 'Elige Empleo',
			'employment_error'   		=> 'Elige empleo por favor',
			'password_placeholder' 		=> 'Contraseña',
			'password_error' 			=> 'La contraseña debe tener una letra minúscula, una letra mayúscula, un número, un mínimo de 8 caracteres. Sin carácter especial',
			'birthday_placeholder' 		=> 'Fecha de Nacimiento',
			'birthday_error' 			=> 'Debe tener más de 18 años para abrir una cuenta commercial',
			'promocode_placeholder' 	=> 'Código promocional',
			'promocode_error' 			=> 'El código promocional no puede contener más de 32 caracteres',
			'terms_text' 				=> 'Al marcar esta casilla, acepto los Términos y condiciones y confirmo que soy mayor de 18 años. Aviso de Divulgación de Riesgo',
		];
	}

	private function get_country_array(): array {
		return array(
			array(
				'name'          => 'Afghanistan',
				'nameAr'        => 'أفغانستان',
				'iso'           => 'AF',
				'telephonecode' => '93',
			),
			array(
				'name'          => 'Albania',
				'nameAr'        => 'ألبانيا',
				'iso'           => 'AL',
				'telephonecode' => '355',
			),
			array(
				'name'          => 'Algeria',
				'nameAr'        => 'الجزائر',
				'iso'           => 'DZ',
				'telephonecode' => '213',
			),
			array(
				'name'          => 'Andorra',
				'nameAr'        => 'أندورا',
				'iso'           => 'AD',
				'telephonecode' => '376',
			),
			array(
				'name'          => 'Angola',
				'nameAr'        => 'أنغولا',
				'iso'           => 'AO',
				'telephonecode' => '244',
			),
			array(
				'name'          => 'Antigua and Barbuda',
				'nameAr'        => 'أنتيغوا وبربودا',
				'iso'           => 'AG',
				'telephonecode' => '-267',
			),
			array(
				'name'          => 'Argentina',
				'nameAr'        => 'الأرجنتين',
				'iso'           => 'AR',
				'telephonecode' => '54',
			),
			array(
				'name'          => 'Armenia',
				'nameAr'        => 'أرمينيا',
				'iso'           => 'AM',
				'telephonecode' => '374',
			),
			array(
				'name'          => 'Australia',
				'nameAr'        => 'أستراليا',
				'iso'           => 'AU',
				'telephonecode' => '61',
			),
			array(
				'name'          => 'Azerbaijan',
				'nameAr'        => 'أذربيجان',
				'iso'           => 'AZ',
				'telephonecode' => '994',
			),
			array(
				'name'          => 'Bahamas, The',
				'nameAr'        => 'الباهاما, The',
				'iso'           => 'BS',
				'telephonecode' => '-241',
			),
			array(
				'name'          => 'Bahrain',
				'nameAr'        => 'البحرين',
				'iso'           => 'BH',
				'telephonecode' => '973',
			),
			array(
				'name'          => 'Bangladesh',
				'nameAr'        => 'بنغلاديش',
				'iso'           => 'BD',
				'telephonecode' => '880',
			),
			array(
				'name'          => 'Barbados',
				'nameAr'        => 'بربادوس',
				'iso'           => 'BB',
				'telephonecode' => '-245',
			),
			array(
				'name'          => 'Belarus',
				'nameAr'        => 'روسيا البيضاء',
				'iso'           => 'BY',
				'telephonecode' => '375',
			),
			array(
				'name'          => 'Belize',
				'nameAr'        => 'بليز',
				'iso'           => 'BZ',
				'telephonecode' => '501',
			),
			array(
				'name'          => 'Benin',
				'nameAr'        => 'بنين',
				'iso'           => 'BJ',
				'telephonecode' => '229',
			),
			array(
				'name'          => 'Bhutan',
				'nameAr'        => 'بوتان',
				'iso'           => 'BT',
				'telephonecode' => '975',
			),
			array(
				'name'          => 'Bolivia',
				'nameAr'        => 'بوليفيا',
				'iso'           => 'BO',
				'telephonecode' => '591',
			),
			array(
				'name'          => 'Bosnia and Herzegovina',
				'nameAr'        => 'البوسنة والهرسك',
				'iso'           => 'BA',
				'telephonecode' => '387',
			),
			array(
				'name'          => 'Botswana',
				'nameAr'        => 'بوتسوانا',
				'iso'           => 'BW',
				'telephonecode' => '267',
			),
			array(
				'name'          => 'Brazil',
				'nameAr'        => 'البرازيل',
				'iso'           => 'BR',
				'telephonecode' => '55',
			),
			array(
				'name'          => 'Brunei',
				'nameAr'        => 'بروناي',
				'iso'           => 'BN',
				'telephonecode' => '673',
			),
			array(
				'name'          => 'Burkina Faso',
				'nameAr'        => 'بوركينا فاسو',
				'iso'           => 'BF',
				'telephonecode' => '226',
			),
			array(
				'name'          => 'Burundi',
				'nameAr'        => 'بوروندي',
				'iso'           => 'BI',
				'telephonecode' => '257',
			),
			array(
				'name'          => 'Cambodia',
				'nameAr'        => 'كمبوديا',
				'iso'           => 'KH',
				'telephonecode' => '855',
			),
			array(
				'name'          => 'Cameroon',
				'nameAr'        => 'الكاميرون',
				'iso'           => 'CM',
				'telephonecode' => '237',
			),
			array(
				'name'          => 'Canada',
				'nameAr'        => 'كندا',
				'iso'           => 'CA',
				'telephonecode' => '1',
			),
			array(
				'name'          => 'Cape Verde',
				'nameAr'        => 'الرأس الأخضر',
				'iso'           => 'CV',
				'telephonecode' => '238',
			),
			array(
				'name'          => 'Central African Republic',
				'nameAr'        => 'جمهورية افريقيا الوسطى',
				'iso'           => 'CF',
				'telephonecode' => '236',
			),
			array(
				'name'          => 'Chad',
				'nameAr'        => 'تشاد',
				'iso'           => 'TD',
				'telephonecode' => '235',
			),
			array(
				'name'          => 'Chile',
				'nameAr'        => 'تشيلي',
				'iso'           => 'CL',
				'telephonecode' => '56',
			),
			array(
				'name'          => "China, People's Republic of",
				'nameAr'        => 'الصين ، جمهورية الصين الشعبية',
				'iso'           => 'CN',
				'telephonecode' => '86',
			),
			array(
				'name'          => 'Colombia',
				'nameAr'        => 'كولومبيا',
				'iso'           => 'CO',
				'telephonecode' => '57',
			),
			array(
				'name'          => 'Comoros',
				'nameAr'        => 'جزر القمر',
				'iso'           => 'KM',
				'telephonecode' => '269',
			),
			array(
				'name'          => 'Congo, (Congo Kinshasa)',
				'nameAr'        => 'الكونغو (الكونغو كينشاسا)',
				'iso'           => 'CD',
				'telephonecode' => '243',
			),
			array(
				'name'          => 'Congo, (Congo Brazzaville)',
				'nameAr'        => 'الكونغو (الكونغو برازافيل)',
				'iso'           => 'CG',
				'telephonecode' => '242',
			),
			array(
				'name'          => 'Costa Rica',
				'nameAr'        => 'كوستاريكا',
				'iso'           => 'CR',
				'telephonecode' => '506',
			),
			array(
				'name'          => "Cote d'Ivoire (Ivory Coast)",
				'nameAr'        => 'كوت ديفوار (ساحل العاج)',
				'iso'           => 'CI',
				'telephonecode' => '225',
			),
			array(
				'name'          => 'Cuba',
				'nameAr'        => 'كوبا',
				'iso'           => 'CU',
				'telephonecode' => '53',
			),
			array(
				'name'          => 'Djibouti',
				'nameAr'        => 'جيبوتي',
				'iso'           => 'DJ',
				'telephonecode' => '253',
			),
			array(
				'name'          => 'Dominica',
				'nameAr'        => 'دومينيكا',
				'iso'           => 'DM',
				'telephonecode' => '-766',
			),
			array(
				'name'          => 'Dominican Republic',
				'nameAr'        => 'جمهورية الدومنيكان',
				'iso'           => 'DO',
				'telephonecode' => '1829',
			),
			array(
				'name'          => 'Ecuador',
				'nameAr'        => 'الإكوادور',
				'iso'           => 'EC',
				'telephonecode' => '593',
			),
			array(
				'name'          => 'Egypt',
				'nameAr'        => 'مصر',
				'iso'           => 'EG',
				'telephonecode' => '20',
			),
			array(
				'name'          => 'El Salvador',
				'nameAr'        => 'السلفادور',
				'iso'           => 'SV',
				'telephonecode' => '503',
			),
			array(
				'name'          => 'Equatorial Guinea',
				'nameAr'        => 'غينيا الإستوائية',
				'iso'           => 'GQ',
				'telephonecode' => '240',
			),
			array(
				'name'          => 'Eritrea',
				'nameAr'        => 'إريتريا',
				'iso'           => 'ER',
				'telephonecode' => '291',
			),
			array(
				'name'          => 'Ethiopia',
				'nameAr'        => 'أثيوبيا',
				'iso'           => 'ET',
				'telephonecode' => '251',
			),
			array(
				'name'          => 'Fiji',
				'nameAr'        => 'فيجي',
				'iso'           => 'FJ',
				'telephonecode' => '679',
			),
			array(
				'name'          => 'Gabon',
				'nameAr'        => 'الغابون',
				'iso'           => 'GA',
				'telephonecode' => '241',
			),
			array(
				'name'          => 'Gambia, The',
				'nameAr'        => 'غامبيا, The',
				'iso'           => 'GM',
				'telephonecode' => '220',
			),
			array(
				'name'          => 'Georgia',
				'nameAr'        => 'جورجيا',
				'iso'           => 'GE',
				'telephonecode' => '995',
			),
			array(
				'name'          => 'Ghana',
				'nameAr'        => 'غانا',
				'iso'           => 'GH',
				'telephonecode' => '233',
			),
			array(
				'name'          => 'Grenada',
				'nameAr'        => 'غرينادا',
				'iso'           => 'GD',
				'telephonecode' => '-472',
			),
			array(
				'name'          => 'Guatemala',
				'nameAr'        => 'غواتيمالا',
				'iso'           => 'GT',
				'telephonecode' => '502',
			),
			array(
				'name'          => 'Guinea',
				'nameAr'        => 'غينيا',
				'iso'           => 'GN',
				'telephonecode' => '224',
			),
			array(
				'name'          => 'Guinea-Bissau',
				'nameAr'        => 'غينيا بيساو',
				'iso'           => 'GW',
				'telephonecode' => '245',
			),
			array(
				'name'          => 'Guyana',
				'nameAr'        => 'غيانا',
				'iso'           => 'GY',
				'telephonecode' => '592',
			),
			array(
				'name'          => 'Haiti',
				'nameAr'        => 'هايتي',
				'iso'           => 'HT',
				'telephonecode' => '509',
			),
			array(
				'name'          => 'Honduras',
				'nameAr'        => 'هندوراس',
				'iso'           => 'HN',
				'telephonecode' => '504',
			),
			array(
				'name'          => 'India',
				'nameAr'        => 'الهند',
				'iso'           => 'IN',
				'telephonecode' => '91',
			),
			array(
				'name'          => 'Indonesia',
				'nameAr'        => 'أندونيسيا',
				'iso'           => 'ID',
				'telephonecode' => '62',
			),
			array(
				'name'          => 'Iraq',
				'nameAr'        => 'العراق',
				'iso'           => 'IQ',
				'telephonecode' => '964',
			),
			array(
				'name'          => 'Jamaica',
				'nameAr'        => 'جامايكا',
				'iso'           => 'JM',
				'telephonecode' => '-875',
			),
			array(
				'name'          => 'Japan',
				'nameAr'        => 'اليابان',
				'iso'           => 'JP',
				'telephonecode' => '81',
			),
			array(
				'name'          => 'Jordan',
				'nameAr'        => 'الأردن',
				'iso'           => 'JO',
				'telephonecode' => '962',
			),
			array(
				'name'          => 'Kazakhstan',
				'nameAr'        => 'كازاخستان',
				'iso'           => 'KZ',
				'telephonecode' => '7',
			),
			array(
				'name'          => 'Kenya',
				'nameAr'        => 'كينيا',
				'iso'           => 'KE',
				'telephonecode' => '254',
			),
			array(
				'name'          => 'Kiribati',
				'nameAr'        => 'كيريباس',
				'iso'           => 'KI',
				'telephonecode' => '686',
			),
			array(
				'name'          => 'Korea, North',
				'nameAr'        => 'كوريا, North',
				'iso'           => 'KP',
				'telephonecode' => '850',
			),
			array(
				'name'          => 'Korea, South',
				'nameAr'        => 'كوريا, South',
				'iso'           => 'KR',
				'telephonecode' => '82',
			),
			array(
				'name'          => 'Kuwait',
				'nameAr'        => 'الكويت',
				'iso'           => 'KW',
				'telephonecode' => '965',
			),
			array(
				'name'          => 'Kyrgyzstan',
				'nameAr'        => 'قرغيزستان',
				'iso'           => 'KG',
				'telephonecode' => '996',
			),
			array(
				'name'          => 'Laos',
				'nameAr'        => 'لاوس',
				'iso'           => 'LA',
				'telephonecode' => '856',
			),
			array(
				'name'          => 'Lebanon',
				'nameAr'        => 'لبنان',
				'iso'           => 'LB',
				'telephonecode' => '961',
			),
			array(
				'name'          => 'Lesotho',
				'nameAr'        => 'ليسوتو',
				'iso'           => 'LS',
				'telephonecode' => '266',
			),
			array(
				'name'          => 'Liberia',
				'nameAr'        => 'ليبيريا',
				'iso'           => 'LR',
				'telephonecode' => '231',
			),
			array(
				'name'          => 'Libya',
				'nameAr'        => 'ليبيا',
				'iso'           => 'LY',
				'telephonecode' => '218',
			),
			array(
				'name'          => 'Liechtenstein',
				'nameAr'        => 'ليختنشتاين',
				'iso'           => 'LI',
				'telephonecode' => '423',
			),
			array(
				'name'          => 'Macedonia',
				'nameAr'        => 'مقدونيا',
				'iso'           => 'MK',
				'telephonecode' => '389',
			),
			array(
				'name'          => 'Madagascar',
				'nameAr'        => 'مدغشقر',
				'iso'           => 'MG',
				'telephonecode' => '261',
			),
			array(
				'name'          => 'Malawi',
				'nameAr'        => 'مالاوي',
				'iso'           => 'MW',
				'telephonecode' => '265',
			),
			array(
				'name'          => 'Malaysia',
				'nameAr'        => 'ماليزيا',
				'iso'           => 'MY',
				'telephonecode' => '60',
			),
			array(
				'name'          => 'Maldives',
				'nameAr'        => 'جزر المالديف',
				'iso'           => 'MV',
				'telephonecode' => '960',
			),
			array(
				'name'          => 'Mali',
				'nameAr'        => 'مالي',
				'iso'           => 'ML',
				'telephonecode' => '223',
			),
			array(
				'name'          => 'Marshall Islands',
				'nameAr'        => 'جزر مارشال',
				'iso'           => 'MH',
				'telephonecode' => '692',
			),
			array(
				'name'          => 'Mauritania',
				'nameAr'        => 'موريتانيا',
				'iso'           => 'MR',
				'telephonecode' => '222',
			),
			array(
				'name'          => 'Mauritius',
				'nameAr'        => 'موريشيوس',
				'iso'           => 'MU',
				'telephonecode' => '230',
			),
			array(
				'name'          => 'Mexico',
				'nameAr'        => 'المكسيك',
				'iso'           => 'MX',
				'telephonecode' => '52',
			),
			array(
				'name'          => 'Micronesia',
				'nameAr'        => 'ميكرونيزيا',
				'iso'           => 'FM',
				'telephonecode' => '691',
			),
			array(
				'name'          => 'Moldova',
				'nameAr'        => 'مولدوفا',
				'iso'           => 'MD',
				'telephonecode' => '373',
			),
			array(
				'name'          => 'Monaco',
				'nameAr'        => 'موناكو',
				'iso'           => 'MC',
				'telephonecode' => '377',
			),
			array(
				'name'          => 'Mongolia',
				'nameAr'        => 'منغوليا',
				'iso'           => 'MN',
				'telephonecode' => '976',
			),
			array(
				'name'          => 'Montenegro',
				'nameAr'        => 'الجبل الأسود',
				'iso'           => 'ME',
				'telephonecode' => '382',
			),
			array(
				'name'          => 'Morocco',
				'nameAr'        => 'المغرب',
				'iso'           => 'MA',
				'telephonecode' => '212',
			),
			array(
				'name'          => 'Mozambique',
				'nameAr'        => 'موزمبيق',
				'iso'           => 'MZ',
				'telephonecode' => '258',
			),
			array(
				'name'          => 'Myanmar (Burma)',
				'nameAr'        => 'ميانمار (بورما)',
				'iso'           => 'MM',
				'telephonecode' => '95',
			),
			array(
				'name'          => 'Namibia',
				'nameAr'        => 'ناميبيا',
				'iso'           => 'NA',
				'telephonecode' => '264',
			),
			array(
				'name'          => 'Nauru',
				'nameAr'        => 'ناورو',
				'iso'           => 'NR',
				'telephonecode' => '674',
			),
			array(
				'name'          => 'Nepal',
				'nameAr'        => 'نيبال',
				'iso'           => 'NP',
				'telephonecode' => '977',
			),
			array(
				'name'          => 'New Zealand',
				'nameAr'        => 'نيوزيلندا',
				'iso'           => 'NZ',
				'telephonecode' => '64',
			),
			array(
				'name'          => 'Nicaragua',
				'nameAr'        => 'نيكاراغوا',
				'iso'           => 'NI',
				'telephonecode' => '505',
			),
			array(
				'name'          => 'Niger',
				'nameAr'        => 'النيجر',
				'iso'           => 'NE',
				'telephonecode' => '227',
			),
			array(
				'name'          => 'Nigeria',
				'nameAr'        => 'نيجيريا',
				'iso'           => 'NG',
				'telephonecode' => '234',
			),
			array(
				'name'          => 'Norway',
				'nameAr'        => 'النرويج',
				'iso'           => 'NO',
				'telephonecode' => '47',
			),
			array(
				'name'          => 'Oman',
				'nameAr'        => 'سلطنة عمان',
				'iso'           => 'OM',
				'telephonecode' => '968',
			),
			array(
				'name'          => 'Pakistan',
				'nameAr'        => 'باكستان',
				'iso'           => 'PK',
				'telephonecode' => '92',
			),
			array(
				'name'          => 'Palau',
				'nameAr'        => 'بالاو',
				'iso'           => 'PW',
				'telephonecode' => '680',
			),
			array(
				'name'          => 'Panama',
				'nameAr'        => 'بناما',
				'iso'           => 'PA',
				'telephonecode' => '507',
			),
			array(
				'name'          => 'Papua New Guinea',
				'nameAr'        => 'بابوا غينيا الجديدة',
				'iso'           => 'PG',
				'telephonecode' => '675',
			),
			array(
				'name'          => 'Paraguay',
				'nameAr'        => 'باراغواي',
				'iso'           => 'PY',
				'telephonecode' => '595',
			),
			array(
				'name'          => 'Peru',
				'nameAr'        => 'بيرو',
				'iso'           => 'PE',
				'telephonecode' => '51',
			),
			array(
				'name'          => 'Philippines',
				'nameAr'        => 'الفلبين',
				'iso'           => 'PH',
				'telephonecode' => '63',
			),
			array(
				'name'          => 'Qatar',
				'nameAr'        => 'دولة قطر',
				'iso'           => 'QA',
				'telephonecode' => '974',
			),
			array(
				'name'          => 'Russia',
				'nameAr'        => 'روسيا',
				'iso'           => 'RU',
				'telephonecode' => '7',
			),
			array(
				'name'          => 'Rwanda',
				'nameAr'        => 'رواندا',
				'iso'           => 'RW',
				'telephonecode' => '250',
			),
			array(
				'name'          => 'Saint Kitts and Nevis',
				'nameAr'        => 'سانت كيتس ونيفيس',
				'iso'           => 'KN',
				'telephonecode' => '-868',
			),
			array(
				'name'          => 'Saint Lucia',
				'nameAr'        => 'القديسة لوسيا',
				'iso'           => 'LC',
				'telephonecode' => '-757',
			),
			array(
				'name'          => 'Saint Vincent and the Grenadines',
				'nameAr'        => 'سانت فنسنت وجزر غرينادين',
				'iso'           => 'VC',
				'telephonecode' => '-783',
			),
			array(
				'name'          => 'Samoa',
				'nameAr'        => 'ساموا',
				'iso'           => 'WS',
				'telephonecode' => '685',
			),
			array(
				'name'          => 'San Marino',
				'nameAr'        => 'سان مارينو',
				'iso'           => 'SM',
				'telephonecode' => '378',
			),
			array(
				'name'          => 'Sao Tome and Principe',
				'nameAr'        => 'ساو تومي وبرنسيبي',
				'iso'           => 'ST',
				'telephonecode' => '239',
			),
			array(
				'name'          => 'Saudi Arabia',
				'nameAr'        => 'المملكة العربية السعودية',
				'iso'           => 'SA',
				'telephonecode' => '966',
			),
			array(
				'name'          => 'Senegal',
				'nameAr'        => 'السنغال',
				'iso'           => 'SN',
				'telephonecode' => '221',
			),
			array(
				'name'          => 'Serbia',
				'nameAr'        => 'صربيا',
				'iso'           => 'RS',
				'telephonecode' => '381',
			),
			array(
				'name'          => 'Seychelles',
				'nameAr'        => 'سيشيل',
				'iso'           => 'SC',
				'telephonecode' => '248',
			),
			array(
				'name'          => 'Sierra Leone',
				'nameAr'        => 'سيرا ليون',
				'iso'           => 'SL',
				'telephonecode' => '232',
			),
			array(
				'name'          => 'Singapore',
				'nameAr'        => 'سنغافورة',
				'iso'           => 'SG',
				'telephonecode' => '65',
			),
			array(
				'name'          => 'Solomon Islands',
				'nameAr'        => 'جزر سليمان',
				'iso'           => 'SB',
				'telephonecode' => '677',
			),
			array(
				'name'          => 'Somalia',
				'nameAr'        => 'الصومال',
				'iso'           => 'SO',
				'telephonecode' => '252',
			),
			array(
				'name'          => 'South Africa',
				'nameAr'        => 'جنوب أفريقيا',
				'iso'           => 'ZA',
				'telephonecode' => '27',
			),
			array(
				'name'          => 'Sri Lanka',
				'nameAr'        => 'سيريلانكا',
				'iso'           => 'LK',
				'telephonecode' => '94',
			),
			array(
				'name'          => 'Sudan',
				'nameAr'        => 'سودان',
				'iso'           => 'SD',
				'telephonecode' => '249',
			),
			array(
				'name'          => 'Suriname',
				'nameAr'        => 'سورينام',
				'iso'           => 'SR',
				'telephonecode' => '597',
			),
			array(
				'name'          => 'Swaziland',
				'nameAr'        => 'سوازيلاند',
				'iso'           => 'SZ',
				'telephonecode' => '268',
			),
			array(
				'name'          => 'Switzerland',
				'nameAr'        => 'سويسرا',
				'iso'           => 'CH',
				'telephonecode' => '41',
			),
			array(
				'name'          => 'Syria',
				'nameAr'        => 'سوريا',
				'iso'           => 'SY',
				'telephonecode' => '963',
			),
			array(
				'name'          => 'Tajikistan',
				'nameAr'        => 'طاجيكستان',
				'iso'           => 'TJ',
				'telephonecode' => '992',
			),
			array(
				'name'          => 'Tanzania',
				'nameAr'        => 'تنزانيا',
				'iso'           => 'TZ',
				'telephonecode' => '255',
			),
			array(
				'name'          => 'Thailand',
				'nameAr'        => 'تايلاند',
				'iso'           => 'TH',
				'telephonecode' => '66',
			),
			array(
				'name'          => 'Timor-Leste (East Timor)',
				'nameAr'        => 'تيمور الشرقية (تيمور الشرقية)',
				'iso'           => 'TL',
				'telephonecode' => '670',
			),
			array(
				'name'          => 'Togo',
				'nameAr'        => 'ليذهب',
				'iso'           => 'TG',
				'telephonecode' => '228',
			),
			array(
				'name'          => 'Tonga',
				'nameAr'        => 'تونغا',
				'iso'           => 'TO',
				'telephonecode' => '676',
			),
			array(
				'name'          => 'Trinidad and Tobago',
				'nameAr'        => 'ترينداد وتوباغو',
				'iso'           => 'TT',
				'telephonecode' => '-867',
			),
			array(
				'name'          => 'Tunisia',
				'nameAr'        => 'تونس',
				'iso'           => 'TN',
				'telephonecode' => '216',
			),
			array(
				'name'          => 'Turkey',
				'nameAr'        => 'ديك رومي',
				'iso'           => 'TR',
				'telephonecode' => '90',
			),
			array(
				'name'          => 'Turkmenistan',
				'nameAr'        => 'تركمانستان',
				'iso'           => 'TM',
				'telephonecode' => '993',
			),
			array(
				'name'          => 'Tuvalu',
				'nameAr'        => 'توفالو',
				'iso'           => 'TV',
				'telephonecode' => '688',
			),
			array(
				'name'          => 'Uganda',
				'nameAr'        => 'أوغندا',
				'iso'           => 'UG',
				'telephonecode' => '256',
			),
			array(
				'name'          => 'Ukraine',
				'nameAr'        => 'أوكرانيا',
				'iso'           => 'UA',
				'telephonecode' => '380',
			),
			array(
				'name'          => 'United Arab Emirates',
				'nameAr'        => 'الإمارات العربية المتحدة',
				'iso'           => 'AE',
				'telephonecode' => '971',
			),
			array(
				'name'          => 'United Kingdom',
				'nameAr'        => 'المملكة المتحدة',
				'iso'           => 'GB',
				'telephonecode' => '44',
			),
			array(
				'name'          => 'Uruguay',
				'nameAr'        => 'أوروغواي',
				'iso'           => 'UY',
				'telephonecode' => '598',
			),
			array(
				'name'          => 'Uzbekistan',
				'nameAr'        => 'أوزبكستان',
				'iso'           => 'UZ',
				'telephonecode' => '998',
			),
			array(
				'name'          => 'Vanuatu',
				'nameAr'        => 'فانواتو',
				'iso'           => 'VU',
				'telephonecode' => '678',
			),
			array(
				'name'          => 'Venezuela',
				'nameAr'        => 'فنزويلا',
				'iso'           => 'VE',
				'telephonecode' => '58',
			),
			array(
				'name'          => 'Vietnam',
				'nameAr'        => 'فيتنام',
				'iso'           => 'VN',
				'telephonecode' => '84',
			),
			array(
				'name'          => 'Yemen',
				'nameAr'        => 'اليمن',
				'iso'           => 'YE',
				'telephonecode' => '967',
			),
			array(
				'name'          => 'Zambia',
				'nameAr'        => 'زامبيا',
				'iso'           => 'ZM',
				'telephonecode' => '260',
			),
			array(
				'name'          => 'Zimbabwe',
				'nameAr'        => 'زيمبابوي',
				'iso'           => 'ZW',
				'telephonecode' => '263',
			),
			array(
				'name'          => 'Abkhazia',
				'nameAr'        => 'أبخازيا',
				'iso'           => 'GE',
				'telephonecode' => '995',
			),
			array(
				'name'          => 'China, Republic of (Taiwan)',
				'nameAr'        => 'الصين, جمهورية (تايوان)',
				'iso'           => 'TW',
				'telephonecode' => '886',
			),
			array(
				'name'          => 'Nagorno-Karabakh',
				'nameAr'        => 'قره باغ الجبلية',
				'iso'           => 'AZ',
				'telephonecode' => '277',
			),
			array(
				'name'          => 'Northern Cyprus',
				'nameAr'        => 'شمال قبرص',
				'iso'           => 'CY',
				'telephonecode' => '-302',
			),
			array(
				'name'          => 'Pridnestrovie (Transnistria)',
				'nameAr'        => 'بريدنستروفى (ترانسنيستريا)',
				'iso'           => 'MD',
				'telephonecode' => '-160',
			),
			array(
				'name'          => 'Somaliland',
				'nameAr'        => 'أرض الصومال',
				'iso'           => 'SO',
				'telephonecode' => '252',
			),
			array(
				'name'          => 'South Ossetia',
				'nameAr'        => 'اوسيتيا الجنوبية',
				'iso'           => 'GE',
				'telephonecode' => '995',
			),
			array(
				'name'          => 'Christmas Island',
				'nameAr'        => 'جزيرة الكريسماس',
				'iso'           => 'CX',
				'telephonecode' => '61',
			),
			array(
				'name'          => 'Cocos (Keeling) Islands',
				'nameAr'        => 'جزر كوكوس (كيلينغ)',
				'iso'           => 'CC',
				'telephonecode' => '61',
			),
			array(
				'name'          => 'Heard Island and McDonald Islands',
				'nameAr'        => 'قلب الجزيرة وجزر ماكدونالز',
				'iso'           => 'HM',
				'telephonecode' => '0',
			),
			array(
				'name'          => 'Norfolk Island',
				'nameAr'        => 'جزيرة نورفولك',
				'iso'           => 'NF',
				'telephonecode' => '672',
			),
			array(
				'name'          => 'New Caledonia',
				'nameAr'        => 'كاليدونيا الجديدة',
				'iso'           => 'NC',
				'telephonecode' => '687',
			),
			array(
				'name'          => 'French Polynesia',
				'nameAr'        => 'بولينيزيا الفرنسية',
				'iso'           => 'PF',
				'telephonecode' => '689',
			),
			array(
				'name'          => 'Mayotte',
				'nameAr'        => 'مايوت',
				'iso'           => 'YT',
				'telephonecode' => '262',
			),
			array(
				'name'          => 'Saint Barthelemy',
				'nameAr'        => 'سانت بارتيليمي',
				'iso'           => 'GP',
				'telephonecode' => '590',
			),
			array(
				'name'          => 'Saint Martin',
				'nameAr'        => 'القديس مارتن',
				'iso'           => 'GP',
				'telephonecode' => '590',
			),
			array(
				'name'          => 'Saint Pierre and Miquelon',
				'nameAr'        => 'سانت بيير وميكلون',
				'iso'           => 'PM',
				'telephonecode' => '508',
			),
			array(
				'name'          => 'Wallis and Futuna',
				'nameAr'        => 'واليس وفوتونا',
				'iso'           => 'WF',
				'telephonecode' => '681',
			),
			array(
				'name'          => 'French Southern and Antarctic Lands',
				'nameAr'        => 'الأراضي الفرنسية الجنوبيةوأنتاركتيكا',
				'iso'           => 'TF',
				'telephonecode' => '0',
			),
			array(
				'name'          => 'Clipperton Island',
				'nameAr'        => 'جزيرة كليبرتون',
				'iso'           => 'PF',
				'telephonecode' => '0',
			),
			array(
				'name'          => 'Bouvet Island',
				'nameAr'        => 'جزيرة بوفيت',
				'iso'           => 'BV',
				'telephonecode' => '0',
			),
			array(
				'name'          => 'Cook Islands',
				'nameAr'        => 'جزر كوك',
				'iso'           => 'CK',
				'telephonecode' => '682',
			),
			array(
				'name'          => 'Niue',
				'nameAr'        => 'نيوي',
				'iso'           => 'NU',
				'telephonecode' => '683',
			),
			array(
				'name'          => 'Tokelau',
				'nameAr'        => 'توكيلاو',
				'iso'           => 'TK',
				'telephonecode' => '690',
			),
			array(
				'name'          => 'Guernsey',
				'nameAr'        => 'غيرنسي',
				'iso'           => 'GG',
				'telephonecode' => '44',
			),
			array(
				'name'          => 'Isle of Man',
				'nameAr'        => 'جزيرة آيل أوف مان',
				'iso'           => 'IM',
				'telephonecode' => '44',
			),
			array(
				'name'          => 'Jersey',
				'nameAr'        => 'جيرسي',
				'iso'           => 'JE',
				'telephonecode' => '44',
			),
			array(
				'name'          => 'Anguilla',
				'nameAr'        => 'أنغيلا',
				'iso'           => 'AI',
				'telephonecode' => '-263',
			),
			array(
				'name'          => 'Bermuda',
				'nameAr'        => 'برمودا',
				'iso'           => 'BM',
				'telephonecode' => '-440',
			),
			array(
				'name'          => 'British Indian Ocean Territory',
				'nameAr'        => 'إقليم المحيط البريطاني الهندي',
				'iso'           => 'IO',
				'telephonecode' => '246',
			),
			array(
				'name'          => 'British Virgin Islands',
				'nameAr'        => 'جزر فيرجن البريطانية',
				'iso'           => 'VG',
				'telephonecode' => '-283',
			),
			array(
				'name'          => 'Cayman Islands',
				'nameAr'        => 'جزر كايمان',
				'iso'           => 'KY',
				'telephonecode' => '-344',
			),
			array(
				'name'          => 'Falkland Islands (Islas Malvinas)',
				'nameAr'        => 'جزر فوكلاند(جزر فوكلاند)',
				'iso'           => 'FK',
				'telephonecode' => '500',
			),
			array(
				'name'          => 'Gibraltar',
				'nameAr'        => 'جبل طارق',
				'iso'           => 'GI',
				'telephonecode' => '350',
			),
			array(
				'name'          => 'Montserrat',
				'nameAr'        => 'مونتسيرات',
				'iso'           => 'MS',
				'telephonecode' => '-663',
			),
			array(
				'name'          => 'Pitcairn Islands',
				'nameAr'        => 'جزر بيتكيرن',
				'iso'           => 'PN',
				'telephonecode' => '0',
			),
			array(
				'name'          => 'Saint Helena',
				'nameAr'        => 'سانت هيلانة',
				'iso'           => 'SH',
				'telephonecode' => '290',
			),
			array(
				'name'          => 'South Georgia & South Sandwich Islands',
				'nameAr'        => 'جورجيا الجنوبية وجزر ساندويتش BHA',
				'iso'           => 'GS',
				'telephonecode' => '0',
			),
			array(
				'name'          => 'Turks and Caicos Islands',
				'nameAr'        => 'جزر تركس وكايكوس',
				'iso'           => 'TC',
				'telephonecode' => '-648',
			),
			array(
				'name'          => 'Northern Mariana Islands',
				'nameAr'        => 'جزر مريانا الشمالية',
				'iso'           => 'MP',
				'telephonecode' => '-669',
			),
			array(
				'name'          => 'Puerto Rico',
				'nameAr'        => 'بورتوريكو',
				'iso'           => 'PR',
				'telephonecode' => '787',
			),
			array(
				'name'          => 'American Samoa',
				'nameAr'        => 'ساموا الأمريكية',
				'iso'           => 'AS',
				'telephonecode' => '-683',
			),
			array(
				'name'          => 'Baker Island',
				'nameAr'        => 'جزيرة بيكر',
				'iso'           => 'UM',
				'telephonecode' => '0',
			),
			array(
				'name'          => 'Guam',
				'nameAr'        => 'غوام',
				'iso'           => 'GU',
				'telephonecode' => '-670',
			),
			array(
				'name'          => 'Howland Island',
				'nameAr'        => 'جزيرة هاولاند',
				'iso'           => 'UM',
				'telephonecode' => '0',
			),
			array(
				'name'          => 'Jarvis Island',
				'nameAr'        => 'جزيرة جارفيس',
				'iso'           => 'UM',
				'telephonecode' => '0',
			),
			array(
				'name'          => 'Johnston Atoll',
				'nameAr'        => 'جونستون أتول',
				'iso'           => 'UM',
				'telephonecode' => '0',
			),
			array(
				'name'          => 'Kingman Reef',
				'nameAr'        => 'كينجمان ريف',
				'iso'           => 'UM',
				'telephonecode' => '0',
			),
			array(
				'name'          => 'Midway Islands',
				'nameAr'        => 'جزر ميدواي',
				'iso'           => 'UM',
				'telephonecode' => '0',
			),
			array(
				'name'          => 'Navassa Island',
				'nameAr'        => 'جزيرة نافاسا',
				'iso'           => 'UM',
				'telephonecode' => '0',
			),
			array(
				'name'          => 'Palmyra Atoll',
				'nameAr'        => 'تدمر أتول',
				'iso'           => 'UM',
				'telephonecode' => '0',
			),
			array(
				'name'          => 'U.S. Virgin Islands',
				'nameAr'        => 'جزر فيرجن الأمريكية',
				'iso'           => 'VI',
				'telephonecode' => '-339',
			),
			array(
				'name'          => 'Wake Island',
				'nameAr'        => 'جزيرة ويك',
				'iso'           => 'UM',
				'telephonecode' => '0',
			),
			array(
				'name'          => 'Hong Kong',
				'nameAr'        => 'هونغ كونغ',
				'iso'           => 'HK',
				'telephonecode' => '852',
			),
			array(
				'name'          => 'Macau',
				'nameAr'        => 'ماكاو',
				'iso'           => 'MO',
				'telephonecode' => '853',
			),
			array(
				'name'          => 'Faroe Islands',
				'nameAr'        => 'جزر صناعية',
				'iso'           => 'FO',
				'telephonecode' => '298',
			),
			array(
				'name'          => 'Greenland',
				'nameAr'        => 'الأرض الخضراء',
				'iso'           => 'GL',
				'telephonecode' => '299',
			),
			array(
				'name'          => 'French Guiana',
				'nameAr'        => 'غيانا الفرنسية',
				'iso'           => 'GF',
				'telephonecode' => '594',
			),
			array(
				'name'          => 'Guadeloupe',
				'nameAr'        => 'جوادلوب',
				'iso'           => 'GP',
				'telephonecode' => '590',
			),
			array(
				'name'          => 'Martinique',
				'nameAr'        => 'مارتينيك',
				'iso'           => 'MQ',
				'telephonecode' => '596',
			),
			array(
				'name'          => 'Reunion',
				'nameAr'        => 'جمع شمل',
				'iso'           => 'RE',
				'telephonecode' => '262',
			),
			array(
				'name'          => 'Aland',
				'nameAr'        => 'أرض',
				'iso'           => 'AX',
				'telephonecode' => '340',
			),
			array(
				'name'          => 'Aruba',
				'nameAr'        => 'أروبا',
				'iso'           => 'AW',
				'telephonecode' => '297',
			),
			array(
				'name'          => 'Svalbard',
				'nameAr'        => 'سفالبارد',
				'iso'           => 'SJ',
				'telephonecode' => '47',
			),
			array(
				'name'          => 'Ascension',
				'nameAr'        => 'صعود',
				'iso'           => 'AC',
				'telephonecode' => '247',
			),
			array(
				'name'          => 'Tristan da Cunha',
				'nameAr'        => 'تريستان دا كونها',
				'iso'           => 'TA',
				'telephonecode' => '290',
			),
			array(
				'name'          => 'Australian Antarctic Territory',
				'nameAr'        => 'إقليم أنتاركتيكا الأسترالي',
				'iso'           => 'AQ',
				'telephonecode' => '0',
			),
			array(
				'name'          => 'Ross Dependency',
				'nameAr'        => 'روس التبعية',
				'iso'           => 'AQ',
				'telephonecode' => '0',
			),
			array(
				'name'          => 'Peter I Island',
				'nameAr'        => 'بيتر أنا الجزيرة',
				'iso'           => 'AQ',
				'telephonecode' => '0',
			),
			array(
				'name'          => 'Queen Maud Land',
				'nameAr'        => 'كوين مود لاند',
				'iso'           => 'AQ',
				'telephonecode' => '0',
			),
			array(
				'name'          => 'British Antarctic Territory',
				'nameAr'        => 'إقليم أنتاركتيكا البريطاني',
				'iso'           => 'AQ',
				'telephonecode' => '0',
			),
			array(
				'name'          => 'Kosovo',
				'nameAr'        => 'كوسوفو',
				'iso'           => 'XK',
				'telephonecode' => '383',
			),
			array(
				'name'          => 'Congo, (Congo ? Kinshasa)',
				'nameAr'        => 'الكونغو (الكونغو - كينشاسا)',
				'iso'           => 'CD',
				'telephonecode' => '243',
			),
			array(
				'name'          => 'Congo, (Congo ? Brazzaville)',
				'nameAr'        => 'الكونغو (الكونغو - برازافيل)',
				'iso'           => 'CG',
				'telephonecode' => '242',
			),
			array(
				'name'          => 'Ashmore and Cartier Islands',
				'nameAr'        => 'جزر أشمور وكارتيير',
				'iso'           => 'AU',
				'telephonecode' => '0',
			),
			array(
				'name'          => 'Coral Sea Islands',
				'nameAr'        => 'جزر بحر المرجان',
				'iso'           => 'AU',
				'telephonecode' => '0',
			),
			array(
				'name'          => 'British Sovereign Base Areas',
				'nameAr'        => 'مناطق القواعد السيادية البريطانية',
				'iso'           => 'II',
				'telephonecode' => '999',
			),
			array(
				'name'          => 'United States',
				'nameAr'        => 'الولايات المتحدة الامريكانية',
				'iso'           => 'US',
				'telephonecode' => '1',
			),
			array(
				'name'          => 'Croatia',
				'nameAr'        => 'الولايات المتحدة الامريكانية',
				'iso'           => 'HR',
				'telephonecode' => '385',
			),
		);
	}

	public function random_symbol( $string ) {
		return $string[ wp_rand( 0, ( strlen( $string ) - 1 ) ) ];
	}

	public function random_password(): string {
		$letters          = 'abcdefghijklmnopqrstuvwxyz';
		$chars            = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$required_symbols = $this->random_symbol( $letters ) . strtoupper( $this->random_symbol( $letters ) ) . wp_rand( 0, 9 );
		return substr( str_shuffle( sha1( wp_rand() . time() ) . $chars ), 0, 9 ) . $required_symbols;
	}

}
