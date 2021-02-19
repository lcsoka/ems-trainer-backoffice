<?php

namespace App;

/*
|--------------------------------------------------------------------------
| Api Responder
|--------------------------------------------------------------------------
|
| This helper class is used to make generalized api responses.
|
*/

class ApiResponder
{
    private $result;
    private $statusCode;
    private $errorMessages;
    private $backtrace;

    public function __construct() {
        $this->result = [];
        $this->statusCode = 200;
        $this->errorMessages = [];
        $this->backtrace = null;
    }

    /**
     * Sets the HTTP response status code
     *
     * @param $status
     */
    public function setStatusCode($status)
    {
        $this->statusCode = $status;
    }


//    /**
//     * Return a success message in JSON format.
//     * @param $data
//     * @param string|null $message
//     * @param int $code
//     * @return \Illuminate\Http\JsonResponse
//     */
//    protected function success($data, string $message = null, int $code = 200)
//    {
//        return response()->json([
//            'message' => $message,
//            'data' => $data
//        ], $code);
//    }
//
//    protected function error(array $errors, int $code, $data = null)
//    {
//        return response()->json([
//            'errors' => $errors,
//        ], $code);
//    }

    public function addItem($objectName, $value, $toData = null)
    {
        if (is_null($toData)) {
            if (!isset($this->result['data'])) {
                $this->result['data'] = [];
            }
            $this->result['data'][$objectName] = $value;
        } else {
            if (!$toData) {
                $this->result[$objectName] = $value;
            } else {
                if (!isset($this->result['data'])) {
                    $this->result['data'] = [];
                }

                $this->result['data'][$objectName] = $value;
            }
        }
        return $this;
    }

    /**
     * Generating the response object used by generateResponseArray and generateJSONResponse
     *
     * @return array
     */
    public function generateResponseObject()
    {
        if (count($this->getErrorMessages()) > 0) {
            $this->addItem('errors', $this->getErrorMessages(), false);
            if (!isset($this->result['success'])) {
                $this->addItem('success', false, false);
            }

            if (!is_null($this->backtrace)) {
                $this->addItem('backtrace', $this->backtrace);
            }
        } else {
            if (!isset($this->result['success'])) {
                $this->addItem('success', true, false);
            }
        }

        foreach ($this->permanentItems as $key => $value) {
            $this->addItem($key, $value);
        }

        $this->addItem('response_code', $this->getStatusCode(), false);

        return $this->getResult();
    }

    /**
     * Generating JSON response object from the result array
     *
     * @return mixed
     */
    public function generateJSONResponse() {
        $response = $this->generateResponseObject();

        $return = response()
            ->json($response, $this->getStatusCode());

        return $return;
    }


    /**
     * Adds an error message to the response
     *
     * @param string|\Exception $message
     * @param null $statusCode
     * @return APIResponse
     */
    public function addErrorMessageWithErrorCode($errorCode, $statusCode = null, $additionalContent = null)
    {
        if (!is_null($statusCode)) {
            $this->setStatusCode($statusCode);
        }

        $errorMessage = ErrorMessages::getMessageByCode($errorCode);

        if ($additionalContent instanceof \Exception) {
            $this->errorMessages[] = [
                'error_code' => $errorCode,
                'error_message' => $errorMessage,
                'message' => $additionalContent->getMessage(),
                'file' => $additionalContent->getFile(),
                'line' => $additionalContent->getLine()];
        } else {
            $this->errorMessages[] = [
                'error_code' => $errorCode,
                'error_message' => $errorMessage . (is_null($additionalContent) ? '' : ' - ' . $additionalContent)];
        }

        return $this;
    }


    protected function addValidatorErrors($messages, $statusCode = null)
    {
        $this->setStatusCode(is_null($statusCode) ? '400' : $statusCode);

        foreach ($messages->toArray() as $messageKey => $messageValues) {
            if (is_array($messageValues)) {
                foreach ($messageValues as $messageValue) {
                    $this->addErrorMessageWithErrorCode('ERROR_INVALID_FORM_DATA', $messageKey, null, $messageValue);
                }
            } else {
                $this->addErrorMessageWithErrorCode('ERROR_INVALID_FORM_DATA', $messageKey, null, $messageValues);
            }
        }

        return $this;
    }

    /**
     * Get the error messages
     *
     * @param bool $asString
     * @return array|string
     */
    public function getErrorMessages($asString = false)
    {
        if ($asString) {
            return implode(', ', $this->errorMessages);
        } else {
            return $this->errorMessages;
        }
    }

    /**
     * Gets the response as an array
     *
     * @return array
     */
    public function getResult() {
        return $this->result;
    }

    public function getStatusCode() {
        return $this->statusCode;
    }

}
