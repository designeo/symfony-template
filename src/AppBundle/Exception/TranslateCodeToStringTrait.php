<?php

namespace AppBundle\Exception;

trait TranslateCodeToStringTrait
{

    public function getCodeString()
    {
        $code = $this->getCode();

        if (is_null($code)) {
            return ErrorCode::MESSAGE_CODE_UNDEFINED;
        }

        $reflection = new \ReflectionClass(ErrorCode::class);

        $codes = $reflection->getConstants();
        foreach ($codes as $codeKey => $codeIdentifier) {
            if ($codeIdentifier === $code) {
                return $this->translateCode($codeKey);
            }
        }

        return ErrorCode::MESSAGE_CODE_UNDEFINED;
    }

    /**
     * @return int
     */
    abstract function getCode();

    private function translateCode($code)
    {
        return preg_replace('/^CODE_/', '', $code);
    }
}