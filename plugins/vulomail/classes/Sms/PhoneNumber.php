<?php
/**
 * PhoneNumber class file.
 *
 * @package VuloMail
 */

namespace VuloMail\Sms;

defined( 'ABSPATH' ) || exit;

/**
 * Phone number normalisation.
 */
class PhoneNumber {

	/**
	 * Normalises a number to E.164 (`+` and 8-15 digits).
	 *
	 * @param string $raw          Number as entered: spaces, dashes, brackets and a 00 prefix are accepted.
	 * @param string $country_code Digits of the default country calling code, applied to national numbers.
	 * @return string E.164 number, or '' when the input can't be a phone number.
	 */
	public static function normalize( $raw, $country_code = '' ) {
		$raw           = trim( (string) $raw );
		$international = 0 === strpos( $raw, '+' );
		$digits        = preg_replace( '/\D/', '', $raw );

		if ( ! $international && 0 === strpos( $digits, '00' ) ) {
			$international = true;
			$digits        = substr( $digits, 2 );
		}

		if ( ! $international ) {
			$country_code = preg_replace( '/\D/', '', (string) $country_code );

			if ( '' === $country_code ) {
				return '';
			}

			// National numbers are usually written with a leading trunk zero that isn't dialled internationally.
			$digits = $country_code . ltrim( $digits, '0' );
		}

		$length = strlen( $digits );

		return $length >= 8 && $length <= 15 ? '+' . $digits : '';
	}

	/**
	 * Number of SMS segments a text will be billed as.
	 *
	 * @param string $body Message text.
	 * @return int
	 */
	public static function segments( $body ) {
		$body = (string) $body;

		if ( '' === $body ) {
			return 0;
		}

		// Anything outside basic Latin forces UCS-2, which fits far fewer characters per segment. This
		// is a close approximation of the GSM 03.38 alphabet, good enough for an estimate.
		$unicode = (bool) preg_match( '/[^\x0A\x0D\x20-\x7E£¥èéùìòÇØøÅåÆæßÉÄÖÑÜ§¿äöñüà€]/u', $body );
		$length  = mb_strlen( $body );
		$single  = $unicode ? 70 : 160;
		$multi   = $unicode ? 67 : 153;

		return $length <= $single ? 1 : (int) ceil( $length / $multi );
	}
}
