<?php
namespace Lelesys\Comments\Validation\Validators;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Lelesys.Comments".      *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Validator for new comment.
 *
 * @Flow\Scope("singleton")
 * @api
 */
class CommentValidator extends \TYPO3\Flow\Validation\Validator\AbstractValidator {

	/**
	 * This validator always needs to be executed even if the given value is empty.
	 * See AbstractValidator::validate()
	 *
	 * @var boolean
	 */
	protected $acceptsEmptyValues = FALSE;

	/**
	 * Check the comment properties in NodeTemplate
	 *
	 * @param mixed $value The value that should be validated
	 * @return void
	 * @api
	 */
	public function isValid($value) {
		if (trim($value->getProperty('name')) === '') {
			$nameError = new \TYPO3\Flow\Validation\Error('Please fill the name', 1401345497, array());
			$this->addErrorToProperty('name', $nameError);
		}
		if (filter_var($value->getProperty('emailAddress'), FILTER_VALIDATE_EMAIL) === FALSE) {
			$emailError = new \TYPO3\Flow\Validation\Error('Please fill the Email address', 1401345548, array());
			$this->addErrorToProperty('emailAddress', $emailError);
		}
		if (trim($value->getProperty('homePage')) !== ''
			&& filter_var(trim($value->getProperty('homePage')), FILTER_VALIDATE_URL) === FALSE) {
			$textError = new \TYPO3\Flow\Validation\Error('Please specify valid url for homepage', 1401345550, array());
			$this->addErrorToProperty('homePage', $textError);
		}
		if (trim($value->getProperty('text')) === '') {
			$textError = new \TYPO3\Flow\Validation\Error('Please add your comment', 1401345555, array());
			$this->addErrorToProperty('text', $textError);
		}
	}

	/**
	 * Add error to given property in comment
	 *
	 * @param string $propertyName
	 * @param \TYPO3\Flow\Validation\Error $error
	 * @return void
	 */
	protected function addErrorToProperty($propertyName, \TYPO3\Flow\Validation\Error $error) {
		$result = new \TYPO3\Flow\Error\Result();
		$result->addError($error);
		$propertyResult = \TYPO3\Flow\Reflection\ObjectAccess::getProperty($this->result, 'propertyResults', TRUE);
		$propertyResult[$propertyName] = $result;
		\TYPO3\Flow\Reflection\ObjectAccess::setProperty($this->result, 'propertyResults', $propertyResult, TRUE);
	}
}
