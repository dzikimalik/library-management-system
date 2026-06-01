<?php
require_once __DIR__ . '/Helper.php';

class Controller
{
    protected function view($view, $data = [])
    {
        extract($data);
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View '$view' not found");
        }
    }

    protected function model($model)
    {
        $modelFile = __DIR__ . '/../models/' . $model . '.php';
        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $model();
        } else {
            die("Model '$model' not found");
        }
    }

    protected function json($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function validate($rules, $data)
    {
        $errors = [];

        foreach ($rules as $field => $ruleSet) {
            $ruleList = explode('|', $ruleSet);
            $value = isset($data[$field]) ? $data[$field] : '';

            foreach ($ruleList as $rule) {
                if ($rule === 'required' && empty($value)) {
                    $errors[$field][] = "$field is required";
                } elseif (strpos($rule, 'min:') === 0) {
                    $min = explode(':', $rule)[1];
                    if (strlen($value) < $min) {
                        $errors[$field][] = "$field must be at least $min characters";
                    }
                } elseif (strpos($rule, 'max:') === 0) {
                    $max = explode(':', $rule)[1];
                    if (strlen($value) > $max) {
                        $errors[$field][] = "$field must not exceed $max characters";
                    }
                } elseif ($rule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = "$field must be a valid email";
                } elseif ($rule === 'numeric' && !is_numeric($value)) {
                    $errors[$field][] = "$field must be numeric";
                }
            }
        }

        return $errors;
    }

    protected function redirect($url)
    {
        redirect($url);
    }
}
