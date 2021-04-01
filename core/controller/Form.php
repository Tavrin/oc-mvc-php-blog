<?php


namespace Core\controller;


use Core\database\EntityEnums;
use Core\database\EntityManager;
use Core\http\Request;
use Core\http\Session;
use Core\utils\ArrayUtils;
use Core\utils\ClassUtils;
use Core\utils\StringUtils;

class Form
{
    protected Session $session;
    protected string $method = 'POST';
    protected string $name = '';
    protected ?string $action = null;
    protected array $data = [];
    protected string $css = '';
    protected object $entity;
    protected array $allEntityData = [];
    protected ?array $submit = null;
    protected array $options = [];
    public bool $isValid = false;

    /**
     * Form constructor.
     * @param string $currentAction
     * @param object $entity
     * @param Session $session
     * @param array $options
     * @throws \Exception
     */
    public function __construct(string $currentAction, object $entity, Session $session, array $options)
    {
        $this->session = $session;
        if (isset($options['method'])) {
            $this->method = $options['method'];
        }

        isset($options['name']) ? $this->name = $options['name'] : false;

        isset($options['action']) ? $this->action = $options['action'] : $this->action = $currentAction;
        if (isset($options['sanitize']) && !$options['sanitize']) {
            $this->options['sanitize'] = false;
        }

        $this->entity = $entity;
        $this->allEntityData = EntityManager::getAllEntityData();
    }

    /**
     * @param string $name
     * @param array $options
     */
    public function addTextInput(string $name, array $options = [])
    {
        $options['type'] = 'text';
        $this->setData(FormEnums::TEXT, $name, $options);
    }

    /**
     * @param string $name
     * @param array $options
     */
    public function addEmailInput(string $name, array $options = [])
    {
        $options['type'] = 'email';
        $this->setData(FormEnums::EMAIL, $name, $options);
    }

    /**
     * @param string $name
     * @param array $options
     */
    public function addDateTimeInput(string $name, array $options = [])
    {
        $options['type'] = 'datetime';
        $this->setData(FormEnums::DATETIME, $name, $options);
    }



    /**
     * @param string $name
     * @param array $options
     */
    public function addPasswordInput(string $name, array $options = [])
    {
        $options['type'] = 'password';
        $this->setData(FormEnums::PASSWORD, $name, $options);
    }

    /**
     * @param string $text
     * @param array $options
     */
    public function setSubmitValue(string $text, array $options = [])
    {
        $this->submit['value'] = $text;
        isset($options['class']) ? $this->submit['class'] = $options['class'] : $this->submit['class'] = '';

    }

    /**
     * @param string $css
     */
    public function addCss(string $css)
    {
        $this->css .= $css . ' ';
    }

    /**
     * @return string
     */
    public function renderForm(): string
    {
        $token = $this->session->get('csrf-new');

        $render = "<form action='{$this->action}' method='{$this->method}' class='{$this->css}'>" . PHP_EOL;
        $render .= "    <input type=\"hidden\"  name=\"csrf\" value=\"{$token}\">" . PHP_EOL;
        foreach ($this->data as $input) {
            $render .= '    <div class="mb-1">' . PHP_EOL .
            "        <label for='{$input['id']}'>{$input['placeholder']}</label>" . PHP_EOL .
            '        ' . $input['render'] . '</div>'. PHP_EOL;
        }
        $render .= "    <input type=\"hidden\"  name=\"formName\" value=\"{$this->name}\">" . PHP_EOL;

        if (isset($this->submit)) {
            $render .= "    <input type=\"submit\"  class=\"{$this->submit['class']}\" value=\"{$this->submit['value']}\">" . PHP_EOL;
        } else {
            $render .= '    <input type="submit" class="" value="Submit">' . PHP_EOL;
        }

        return $render . "</form>";
    }

    /**
     * @param array $type
     * @param string $name
     * @param array $options
     */
    protected function setData(array $type, string $name, array $options)
    {
        $input = "<input type='{$type['type']}' name='{$name}' " ;

        isset($options['id']) ? true :  $options['id'] = $name;
        foreach ($options as $optionName => $option) {
            if (!in_array($optionName, $type['attributes'])) {
                continue;
            }

            if ('readonly' === $optionName) {
                $input .= "{$optionName} ";
                continue;
            }

            $input .= $optionName . "=\"{$option}\" ";
        }

        if (isset($options['required']) && false === $options['required']) {
            $fieldData['required'] = false;
        } else {
            $input .= "required ";
            $fieldData['required'] = true;
        }


        $input .= ">";

        isset($options['hash']) ? $fieldData['hash'] = $options['hash'] : false;
        isset($options['entity']) ? $fieldData['entity'] = $options['entity'] : $fieldData['entity'] = $this->entity;
        isset($options['type']) ? $fieldData['type'] = $options['type'] : false;
        isset($options['placeholder']) ? $fieldData['placeholder'] = $options['placeholder'] : $fieldData['placeholder'] = $name;
        isset($options['property']) ? $fieldData['property'] = $options['property'] : false;
        $fieldData['id'] = $options['id'];
        $fieldData['render'] = $input;
        $this->data[$name] = $fieldData;
    }

    /**
     * @param Request $request
     * @throws \Exception
     */
    public function handle(Request $request)
    {


        if (false !== $currentRequest = $this->validateAndSanitizeRequest($request)) {
            foreach ($this->data as $fieldName => $fieldData) {
                if ((!isset($currentRequest[$fieldName]) || "" == $currentRequest[$fieldName]) && true === $fieldData['required']) {
                    return;
                }

                if (false === $requestField = $this->validateRequestField($fieldData, $currentRequest[$fieldName])) {
                    return;
                }

                if (false === $this->validateAndHydrateEntity($fieldName, $fieldData, $requestField)) {
                    return;
                }
            }

            $this->isValid = true;

        }
    }

    /**
     * @param Request $request
     * @return array|false
     */
    private function validateAndSanitizeRequest(Request $request)
    {
        $oldToken = $this->session->get('csrf-old');

        if (!isset($request->request)
            || (isset($this->name) && (!isset($request->request['formName']) || $this->name !== $request->getRequest('formName'))
            || ( !isset($request->request['csrf']) || !isset($oldToken) || $request->getRequest('csrf') !== $oldToken->toString()))) {
            return false;
        }

        if (isset($this->options['sanitize']) && !$this->options['sanitize']) {
            return $request->request;
        }

        return ArrayUtils::sanitizeArray($request->request);
    }

    /**
     * @param string $fieldName
     * @param array $fieldData
     * @param string $requestField
     * @return false|string|null
     */
    private function validateRequestField(array $fieldData, string $requestField)
    {
        if ((isset($fieldData['maxLength']) && $fieldData['maxLength'] < strlen($requestField)) || (isset($fieldData['minLength']) && $fieldData['minLength'] > strlen($requestField))) {
            return false;
        }

        if (isset($fieldData['type']) && 'email' === $fieldData['type']
            && !preg_match('#^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$#', $requestField)) {
            return false;
        }

        if (isset($fieldData['type']) && 'password' === $fieldData['type'] && isset($fieldData['hash']) && true === $fieldData['hash']) {
            $requestField = password_hash($requestField, PASSWORD_DEFAULT);
        }

        return $requestField;
    }

    /**
     * @param string $fieldName
     * @param array $fieldData
     * @param string $requestField
     * @return bool
     * @throws \Exception
     */
    private function validateAndHydrateEntity(string $fieldName, array $fieldData, string $requestField): bool
    {
        $entityName = strtolower(ClassUtils::getClassNameFromObject($fieldData['entity']));
        if (!isset($this->allEntityData[$entityName]['fields'][$fieldName])) {
            return false;
        }

        $fieldType = gettype(StringUtils::changeTypeFromValue($requestField));

        if (isset($fieldData['type']) && 'datetime' === $fieldData['type'] && preg_match('#^(?:(\d{4}-\d{2}-\d{2})T(\d{2}:\d{2}?))$#', $requestField, $match)) {
            $requestField = new \DateTime($match[0]);
            $fieldType = strtolower(get_class($requestField));
        }

        if ($fieldType !== $this->allEntityData[$entityName][EntityEnums::FIELDS_CATEGORY][$fieldName][EntityEnums::FIELD_TYPE]) {
            return false;
        }

        $entityMethod = 'set' . ucfirst($fieldName);
        $fieldData['entity']->$entityMethod($requestField);

        return true;
    }
}