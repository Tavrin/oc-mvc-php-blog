<?php


namespace Core\controller;


use Core\database\EntityEnums;
use Core\database\EntityManager;
use Core\file\FormFile;
use Core\http\Request;
use Core\http\Session;
use Core\utils\ArrayUtils;
use Core\utils\ClassUtils;
use Core\utils\StringUtils;
use DateTime;
use Exception;
use function array_key_exists;

class Form
{
    protected Session $session;

    protected string $method = 'POST';

    protected string $name = 'defaultForm';

    protected ?string $action = null;

    protected array $data = [];

    protected string $css = '';

    protected object $entity;

    protected array $allEntityData = [];

    protected ?array $submit = null;

    protected array $options = [
        'enctype' => 'application/x-www-form-urlencoded'
    ];

    public bool $isValid = false;

    public bool $isSubmitted = false;

    /**
     * Form constructor.
     * @param \Core\http\Request $request
     * @param object $entity
     * @param Session $session
     * @param array $options
     */
    public function __construct(Request $request, object $entity, Session $session, array $options)
    {
        $this->session = $session;
        if (isset($options['method'])) {
            $this->method = $options['method'];
        }

        isset($options['name']) ? $this->name = $options['name'] : false;
        (isset($options['submit']) && !$options['submit']) ? $this->options['submit'] = false : $this->options['submit'] = true;
        isset($options['wrapperClass']) ? $this->options['wrapperClass'] = $options['wrapperClass'] : $this->options['wrapperClass'] = '';

        isset($options['action']) ? $this->action = $options['action'] : $this->action = $request->getPathInfo();
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
        $currentDate = date("Y-m-d\TH:i:s");
        $options['value'] ?? $options['value'] = $currentDate;
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
     * @param string $name
     * @param array $selection
     * @param array $options
     */
    public function addSelectInput(string $name, array $selection, array $options = [])
    {
        $options['type'] = 'select';
        $input = "<select name='{$name}' " ;

        isset($options['id']) ? true :  $options['id'] = $name;
        foreach ($options as $optionName => $option) {

            if (!in_array($optionName, FormEnums::SELECT['attributes'])) {
                continue;
            }

            if ('disabled' === $optionName) {
                $input .= "{$optionName} ";
                continue;
            }

            $input .= $optionName . "=\"{$option}\" ";
        }

        if (!isset($options['required']) || (isset($options['required']) && true === $options['required'])) {
            $input .= "required ";
        }

        $input .= ">" . PHP_EOL;
        $input .= $this->setSelectOptions($name, $selection, $options);
        $input .= '</select>';
        $fieldData = $this->setDataOptions($name, $options, FormEnums::SELECT);
        $fieldData['render'] = $input;
        $fieldData['selection'] = $selection;
        $this->data[$name] = $fieldData;
    }

    private function setSelectOptions(string $name, array $selection, array $options): string
    {
        $input = '    <option value="">' . ($options['placeholder'] ?? $name) . '</option>' . PHP_EOL;
        foreach ($selection as $item) {
            $selected = '';
            if (is_array($item) && isset($item['id'])) {
                if ($options['selected'] && $options['selected'] === $item['id']) {
                    $selected = 'selected="' . $item['id'] . '"';
                } elseif (true === $this->session->get('formError') && $formData = $this->session->get('formData')) {
                    if (array_key_exists($name, $formData)) {
                        $selected = 'selected="' . $formData[$name]. '"';
                    }
                }

                $input .= '    <option value="' . $item['id'] . '" ' . $selected . '>' . ($item['placeholder'] ?? $item['id']) . '</option>' . PHP_EOL;
            }
        }

        return $input;
    }

    /**
     * @param string $name
     * @param array $options
     */
    public function addHiddenInput(string $name, array $options = [])
    {
        $options['type'] = 'hidden';
        $this->setData(FormEnums::HIDDEN, $name, $options);
    }

    public function addFileInput(string $name, array $options = [])
    {
        $options['type'] = FormEnums::FILE['type'];
        $this->options['enctype'] = 'multipart/form-data';
        if ($options['whitelist']) {
            if (!$options['whitelist']['type'] || ('enum' !== $options['whitelist']['type']  && 'manual' !== $options['whitelist']['type'] )) {
                throw new \LogicException('A whitelist needs a type [enum] or [manual]');
            } elseif (!$options['whitelist']['mimes']) {
                throw new \LogicException('A whitelist needs mime types');
            }

            if ('enum' === $options['whitelist']['type']) {
                $mimes = explode(',', $options['whitelist']['mimes']);
                foreach ($mimes as $mime) {
                    $mime = trim($mime);
                    if (!FormEnums::hasWhiteList($mime)) {
                        throw new \LogicException('whitelist type [' . $mime . '] is not in the enums');
                    }
                }
            }

        } else {
            $options['whitelist'] = false;
        }

        $options['whitelist'] ?? $options['whitelist'] = false;
        $this->setData(FormEnums::FILE, $name, $options);
    }

    /**
     * @param string $text
     * @param array $options
     */
    public function setSubmitValue(string $text, array $options = [])
    {
        if (!$this->options['submit']) {
            return;
        }

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
     * @return array
     */
    public function renderForm(): array
    {
        $token = $this->session->get('csrf-new');

        $globalOptions = $this->options;
        $render = "<form action='{$this->action}' enctype='{$globalOptions['enctype']}' id='{$this->name}' method='{$this->method}' class='{$this->css}'>" . PHP_EOL;
        $render .= "    <input type=\"hidden\"  name=\"csrf\" value=\"{$token}\">" . PHP_EOL;
        foreach ($this->data as $input) {
            if ('hidden' === $input['type']) {
                $render .= '        ' . $input['render'] . PHP_EOL;
            } else {
                $render .= "    <div class='{$globalOptions['wrapperClass']} {$input['wrapperClass']}'>" . PHP_EOL .
                    "        <label for='{$input['id']}'>{$input['label']}</label>" . PHP_EOL .
                    '        ' . $input['render'] . '</div>'. PHP_EOL;
            }
        }
        $render .= "    <input type=\"hidden\"  name=\"formName\" value=\"{$this->name}\">" . PHP_EOL;

        if (isset($this->submit) && $this->options['submit']) {
            $render .= "    <input type=\"submit\"  class=\"{$this->submit['class']}\" value=\"{$this->submit['value']}\">" . PHP_EOL;
        } elseif ($this->options['submit']) {
            $render .= '    <input type="submit" class="" value="Submit">' . PHP_EOL;
        }
        $render .= "</form>";
        $form['render'] = $render;
        $form['data'] = $this->data;
        $form['name'] = $this->name;
        return $form;
    }

    /**
     * @param array $type
     * @param string $name
     * @param array $options
     */
    protected function setData(array $type, string $name, array $options)
    {

        if (true === $this->session->get('formError') && $this->session->get('formData') && array_key_exists($name, $this->session->get('formData'))) {
            $options['value'] = $this->session->get('formData')[$name];
        }

        $input = "<input type='{$type['type']}' name='{$name}' " ;

        isset($options['id']) ? true :  $options['id'] = $name;
        foreach ($options as $optionName => $option) {
            if (!in_array($optionName, $type['attributes'])) {
                continue;
            }

            if ('dataAttributes' === $optionName) {
                $input .= $this->setDataAttributes($option);
                continue;
            }

            if (in_array($optionName,FormEnums::BOOL_FIELDS)) {
                $input .= "{$optionName} ";
                continue;
            }

            $input .= $optionName . "=\"{$option}\" ";
        }

        if (!isset($options['required']) || (isset($options['required']) && true === $options['required'])) {
            $input .= "required ";
        }


        $input .= ">";

        $fieldData = $this->setDataOptions($name, $options, $type);
        $fieldData['render'] = $input;
        $this->data[$name] = $fieldData;
    }

    private function setDataAttributes($option): string
    {
        $input = '';
        foreach ($option as $attributeName => $dataAttribute) {
            if (is_array($dataAttribute)) {
                $dataAttribute = json_encode($dataAttribute);
                $input .= "data-{$attributeName}={$dataAttribute} ";
            }

            $input .= "data-{$attributeName}=\"{$dataAttribute}\" ";
        }

        return $input;
    }

    /**
     * @param $name
     * @param $options
     * @return array
     */
    private function setDataOptions($name, $options, $type): array
    {
        if (isset($options['required']) && false === $options['required']) {
            $fieldData['required'] = false;
        } else {
            $fieldData['required'] = true;
        }

        $fieldData['result'] = '';

        if ('file' === $type['type']) {
            $fieldData['whitelist'] = $options['whitelist'];
        }

        isset($options['sanitize']) ? $fieldData['sanitize'] = $options['sanitize'] : $fieldData['sanitize'] = true;
        isset($options['hash']) ? $fieldData['hash'] = $options['hash'] : false;
        isset($options['entity']) ? $fieldData['entity'] = $options['entity'] : $fieldData['entity'] = $this->entity;
        isset($options['targetField']) ? $fieldData['targetField'] = $options['targetField'] : false;
        isset($options['fieldName']) ? $fieldData['fieldName'] = $options['fieldName'] : $fieldData['fieldName'] = $name;
        isset($options['type']) ? $fieldData['type'] = $options['type'] : false;
        isset($options['placeholder']) ? $fieldData['placeholder'] = $options['placeholder'] : $fieldData['placeholder'] = $name;
        isset($options['label']) ? $fieldData['label'] = $options['label'] : $fieldData['label'] = $fieldData['placeholder'];
        isset($options['wrapperClass']) ? $fieldData['wrapperClass'] = $options['wrapperClass'] : $fieldData['wrapperClass'] = '';
        isset($options['dataAttributes']) ? $fieldData['dataAttributes'] = $options['dataAttributes'] : $fieldData['dataAttributes'] = null;
        isset($options['property']) ? $fieldData['property'] = $options['property'] : false;
        $fieldData['id'] = $options['id'];

        return $fieldData;
    }

    /**
     * @param Request $request
     * @throws Exception
     */
    public function handle(Request $request)
    {
        $this->session->remove('formError');
        $this->session->remove('formData');

        if (false !== $currentRequest = $this->validateRequest($request)) {

            $this->isSubmitted = true;
            $filesArray = [];

            foreach ($this->data as $fieldName => $fieldData) {
                if ('file' === $fieldData['type']) {
                    if (null === $newFileData = $this->handleFile($currentRequest->files, $fieldData, $fieldName)) {
                        $this->setFormError($request);
                        return;
                    }

                    $filesArray[$fieldName] = $newFileData;
                    continue;
                }

                $currentRequest->request[$fieldName] = $this->sanitizeRequest( $currentRequest->request, $fieldData, $fieldName);

                if ((!isset( $currentRequest->request[$fieldName]) || "" ==  $currentRequest->request[$fieldName]) && true === $fieldData['required']) {
                    $this->setFormError($request);
                    return;
                }

                if (false === $requestField = $this->validateRequestField($fieldData,  $currentRequest->request[$fieldName])) {
                    $this->setFormError($request);
                    return;
                }

                if (false === $fieldData['entity']) {
                    $this->data[$fieldName]['result'] = $requestField;
                    continue;
                }

                if (false === $this->validateAndHydrateEntity($fieldName, $fieldData, $requestField)) {
                    $this->setFormError($request);
                    return;
                }

                $this->data[$fieldName]['result'] = $requestField;
            }

            foreach ($filesArray as $name => $data) {
                $this->data[$name]['result'] = new FormFile($data['tmp_name'], $this->data, $data['name'], $data['type']);
            }

            $this->isValid = true;

        }
    }

    private function handleFile(array $currentRequest, $fieldData, $fieldName): ?array
    {
        if (!is_uploaded_file($currentRequest[$fieldName]['tmp_name'])) {
            return null;
        }

        $newFileData = $currentRequest[$fieldName];
        $newFileData['type'] =  mime_content_type($newFileData['tmp_name']);
        $newFileData['name'] = StringUtils::slugify(pathinfo($newFileData['name'])['filename']) . '.' . pathinfo($newFileData['name'])['extension'];

        if ($fieldData['whitelist']) {
            if ('manual' === $fieldData['whitelist']['type']) {
                $mimes = explode(',', trim($fieldData['whitelist']['mimes']));
                if (!in_array($newFileData['type'], $mimes)) {
                    return null;
                }
            } elseif ('enum' === $fieldData['whitelist']['type']) {
                $mimes = explode(',', strtoupper($fieldData['whitelist']['mimes']));
                $totalMimes = [];
                foreach ($mimes as $mime) {
                    $mime = trim($mime);
                    $formEnumTypes = FormEnums::getWhitelist($mime);
                    foreach ($formEnumTypes as $formEnumType) {
                        $totalMimes[] = $formEnumType;
                    }
                }

                if (!in_array($newFileData['type'], $totalMimes)) {
                    return null;
                }
            }
        }

        if (($newFileData['type']) !== $currentRequest[$fieldName]['type']) {
            return null;
        }


        return $newFileData;
    }

    private function sanitizeRequest($currentRequest, $fieldData, $fieldName)
    {
        $currentRequestField = $currentRequest[$fieldName];
        if ($fieldData['sanitize']) {
            $currentRequestField = htmlspecialchars($currentRequestField);
        }

        return $currentRequestField;
    }

    public function setFormError(Request $request)
    {
        $this->session->set('formError', true);
        $this->session->set('formData', $request->request);
    }

    /**
     * @param Request $request
     * @return Request|false
     */
    private function validateRequest(Request $request)
    {
        $oldToken = $this->session->get('csrf-old');

        if (!isset($request->request)
            || (isset($this->name) && (!isset($request->request['formName']) || $this->name !== $request->getRequest('formName'))
            || ( !isset($request->request['csrf']) || !isset($oldToken) || $request->getRequest('csrf') !== $oldToken->toString()))) {
            return false;
        }

        return $request;
    }

    /**
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
     * @throws Exception
     */
    private function validateAndHydrateEntity(string $fieldName, array $fieldData, string $requestField): bool
    {
        $entityName = strtolower(ClassUtils::getClassNameFromObject($fieldData['entity']));
        if (!isset($this->allEntityData[$entityName]['fields'][$fieldData['fieldName']])) {
            return false;
        }

        $fieldType = gettype(StringUtils::changeTypeFromValue($requestField));

        if (isset($fieldData['type']) && 'datetime' === $fieldData['type'] && preg_match('#^(?:(\d{4}-\d{2}-\d{2})T(\d{2}:\d{2}:\d{2}?))$#', $requestField, $match)) {
            $requestField = new DateTime($match[0]);
            $fieldType = strtolower(get_class($requestField));
        }

        $entityField = $this->allEntityData[$entityName][EntityEnums::FIELDS_CATEGORY][$fieldData['fieldName']];

        if (EntityEnums::TYPE_ASSOCIATION === $entityField[EntityEnums::FIELD_TYPE]) {
            $repository = new $entityField['repository'];
            $associatedEntity = $repository->findOneBy($fieldData['targetField'] ?? 'id',$requestField);
        }

        if ( 'string' === $fieldType) {
            'text' === $entityField[EntityEnums::FIELD_TYPE] ? $fieldType = 'text' : $fieldType = 'string';
        }

        if ((EntityEnums::TYPE_ASSOCIATION !== $entityField[EntityEnums::FIELD_TYPE] && $fieldType !== $entityField[EntityEnums::FIELD_TYPE]) ||
            (EntityEnums::TYPE_ASSOCIATION === $entityField[EntityEnums::FIELD_TYPE] && !isset($associatedEntity))) {
            return false;
        }

        $entityMethod = 'set' . ucfirst($fieldData['fieldName']);
        $fieldData['entity']->$entityMethod($associatedEntity ?? $requestField);
        return true;
    }

    /**
     * @param string $fieldName
     * @return mixed|null
     */
    public function getData(string $fieldName)
    {
        return array_key_exists($fieldName, $this->data) ? $this->data[$fieldName]['result'] : null;
    }

    public function getAllData()
    {
        return $this->data;
    }
}