<?php

namespace Zoop\Exceptions;

use RuntimeException;

/**
* Class ValidationException.
*/
class ValidationException extends RuntimeException
{
	/**
	* @var int
	*/
	private $statusCode;

	/**
	* @var Error[]
	*/
	private $errors;

	/**
	* ValidationException constructor.
	*
	* Exception thrown when the zoop API returns a 4xx http code.
	* Indicates that an invalid value was passed.
	*
	* @param int     $statusCode
	* @param Error $error
	*/
	public function __construct($statusCode, $error)
	{
		$this->error = $error;
		$this->statusCode = $statusCode;
	}

	/**
	* Returns the http status code ie.: 400.
	*
	* @return int
	*/
	public function getStatusCode()
	{
		return $this->statusCode;
	}

	/**
	* Returns the list of errors returned by the API.
	*
	* @return Error[]
	*
	* @see \Zoop\Exceptions\Error
	*/
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	* Convert error variables in string.
	*
	* @return string
	*/
	public function __toString()
	{
		$template = "[$this->code] The following errors ocurred:\n%s";
		$category = $this->error->getCategory();
		$msg = $this->error->getMessage();
		$temp_list = "$category: $msg\n";

		return sprintf($template, $temp_list);
	}
}
