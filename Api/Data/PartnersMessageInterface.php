<?php

namespace Manugentoo\PartnerPortal\Api\Data;

/**
 *
 */
interface PartnersMessageInterface
{
	/**
	 * @var string
	 */
	const ERROR_MESSAGE_EMAIL_DONT_MATCH = 'The email you entered isnot found.';
	/**
	 * @var string
	 */
	const ERROR_MESSAGE_INVALID_OTP = 'Invalid OTP please try again.';
	/**
	 * @var string
	 */
	const ERROR_MESSAGE_TOKEN_EXPIRED = 'Your access token is expired.';
	/**
	 * @var string
	 */
	const ERROR_MESSAGE_OTP_SESSION_EXPIRED = 'The allowed time to enter OTP has expired.';
	/**
	 * @var string
	 */
	const ERROR_MESSAGE_OTP_RESEND = 'You have reached the maximum OTP resend request. Please wait for %1 minutes and try again.';
	/**
	 * @var string
	 */
	const MESSAGE_EMAIL_TEXT_OTP_SENT = 'A 6 digit OTP code was sent to you email.';
	/**
	 * @var string
	 */
	const MESSAGE_OTP_RENTER_EMAIL = 'Enter your email to resend OTP code';
	/**
	 * @var string
	 */
	const MESSAGE_OTP_RESEND = 'We sent a new OTP code to your email.';
	/**
	 * @string
	 */
	const EMAIL_TEXT_ERROR_EMAIL_NOT_MATCH = 'The email you entered is not found.';
}