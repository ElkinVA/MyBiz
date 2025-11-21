<?php
namespace App\Core;

class Validator {
    private $errors = [];
    private $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function validate($rules) {
        foreach ($rules as $field => $ruleSet) {
            $rulesArray = explode('|', $ruleSet);
            $value = $this->data[$field] ?? null;

            foreach ($rulesArray as $rule) {
                $this->applyRule($field, $value, $rule);
            }
        }

        return empty($this->errors);
    }

    private function applyRule($field, $value, $rule) {
        $params = [];

        if (strpos($rule, ':') !== false) {
            list($rule, $param) = explode(':', $rule);
            $params = explode(',', $param);
        }

        switch ($rule) {
            case 'required':
                if (empty($value)) {
                    $this->addError($field, "Поле {$field} обязательно для заполнения");
                }
                break;

            case 'email':
                if (!empty($value) && !Security::validateEmail($value)) {
                    $this->addError($field, "Некорректный email адрес");
                }
                break;

            case 'min':
                if (!empty($value) && strlen($value) < $params[0]) {
                    $this->addError($field, "Минимальная длина поля {$field} - {$params[0]} символов");
                }
                break;

            case 'max':
                if (!empty($value) && strlen($value) > $params[0]) {
                    $this->addError($field, "Максимальная длина поля {$field} - {$params[0]} символов");
                }
                break;

            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    $this->addError($field, "Поле {$field} должно быть числом");
                }
                break;
        }
    }

    private function addError($field, $message) {
        $this->errors[$field][] = $message;
    }

    public function getErrors() {
        return $this->errors;
    }

    public function getFirstError($field) {
        return $this->errors[$field][0] ?? null;
    }
}
?>