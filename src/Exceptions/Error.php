<?php

namespace Zoop\Exceptions;

/**
 * Class Error.
 * Represents an error returned by the API.
 */
class Error
{
    /**
     * Code of error.
     *
     * @var int|string
     */
    private $code;

    /**
     * Path of error.
     *
     * @var string
     */
    private $category;

    /**
     * Description of error.
     *
     * @var string
     */
    private $message;

    /**
     * Error constructor.
     *
     * Represents an error return by the API. Commonly used by {@see \Zoop\Exceptions\ValidationException}
     *
     * @param string $code        unique error identifier.
     * @param string $path        represents the field where the error ocurred.
     * @param string $description error description.
     */
    public function __construct($code, $category, $message)
    {
        $this->code = $code;
        $this->category = $category;
        $this->message = $message;
    }

    /**
     * Returns the unique alphanumeric identifier of the error, ie.: "API-1".
     *
     * @return int|string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Returns the dotted string representing the field where the error ocurred, ie.: "customer.birthDate".
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Returns the error description.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Creates an Error array from a json string.
     *
     * @param string $json_string string returned by the Zoop API
     *
     * @return array
     */
    public static function parseErrors($json_string)
    {
        $error = json_decode($json_string)->error;

        return new self($error->status_code, $error->category, $error->message);
    }
}
