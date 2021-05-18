<?php


namespace Core\controller;


use Core\database\EntityEnums;
use Core\database\EntityManager;
use Core\file\FileException;
use Core\file\FormFile;
use Core\FormHandleException;
use Core\http\Request;
use Core\http\Session;
use Core\utils\ArrayUtils;
use Core\utils\ClassUtils;
use Core\utils\StringUtils;
use DateTime;
use Exception;
use Ramsey\Uuid\Uuid;
use function array_key_exists;

class Form
{
    private const DOUBLE_INDENT = '        ';
    protected Session $session;

    protected string $method = 'POST';

    protected string $name = 'defaultForm';

    protected ?string $action = null;

    protected array $data = [];

    protected string $css = '';

    protected ?object $entity = null;

    protected array $allEntityData = [];

    protected ?array $submit = null;

    protected array $options = [
        'enctype' => 'application/x-www-form-urlencoded'
    ];

    public bool $isValid = false;

    public bool $isSubmitted = false;

    public ?array $errors = null;

    /**
     * Form constructor.
     * @param \Core\http\Request $request
     * @param object $entity
     * @param Session $session
     * @param array $options
     */
    public function __construct(Request $request, object $entity, Session $session, array $options = [])
    {
        $this->session = $session;
        if (isset($options['method'])) {
            $this->method = $options['method'];
        }

        isset($options['name']) ? $this->name = $options['name'] : $this->name = 'defaultForm';
        (isset($options['submit']) && !$options['submit']) ? $this->options['submit'] = false : $this->options['submit'] = true;
        isset($options['wrapperClass']) ? $this->options['wrapperClass'] = $options['wrapperClass'] : $this->options['wrapperClass'] = '';
        isset($options['errorClass']) ? $this->options['errorClass'] = $options['errorClass'] : $this->options['errorClass'] = null;

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
     * @return $this
     */
    public function addTextInput(string $name, array $options = []): Form
    {
        $options['type'] = 'text';
        $this->setData(FormEnums::TEXT, $name, $options);
        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     * @return $this
     */
    public function addTextareaInput(string $name, array $options = []): Form
    {
        $options['type'] = 'textarea';
        $this->setData(FormEnums::TEXTAREA, $name, $options);
        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     */
    public function addEmailInput(string $name, array $options = []): Form
    {
        $options['type'] = 'email';
        $this->setData(FormEnums::EMAIL, $name, $options);
        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     * @return $this
     */
    public function addDateTimeInput(string $name, array $options = []): Form
    {
        $options['type'] = 'datetime';
        $currentDate = date("Y-m-d\TH:i:s");
        $options['value'] ?? $options['value'] = $currentDate;
        $this->setData(FormEnums::DATETIME, $name, $options);
        return $this;
    }


    /**
     * @param string $name
     * @param array $options
     * @return $this
     */
    public function addPasswordInput(string $name, array $options = []): Form
    {
        $options['type'] = 'password';
        $this->setData(FormEnums::PASSWORD, $name, $options);
        return $this;
    }

    /**
     * @param string $name
     * @param array $selection
     * @param array $options
     * @return $this
     */
    public function addSelectInput(string $name, array $selection, array $options = []): Form
    {
        $options['type'] = 'select';
        $input = "<select name='{$name}' " ;

        if ((isset($options['entitySelect']) && true === $options['entitySelect']) || !isset($options['entitySelect'] )) {
            $options['id'] ?? $options['id'] = $name;
        }
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

        return $this;
    }

    private function setSelectOptions(string $name, array $selection, array $options): string
    {
        $input = '    <option value="">' . ($options['placeholder'] ?? $name) . '</option>' . PHP_EOL;
        foreach ($selection as $item) {
            $selected = '';
            if (is_array($item) && isset($item['id'])) {
                if (isset($options['selected']) && $options['selected'] === $item['id']) {
                    $selected = 'selected';
                } elseif ($this->session->has('formData') && $formData = $this->session->get('formData')) {
                    if (array_key_exists($name, $formData['data']) && $this->name === $formData['formName']) {
                        $selected = 'selected';
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
     * @return $this
     */
    public function addHiddenInput(string $name, array $options = []): Form
    {
        $options['type'] = 'hidden';
        $this->setData(FormEnums::HIDDEN, $name, $options);

         return $this;
    }

    /**
     * @param string $name
     * @param array $options
     * @return $this
     */
    public function addCheckbox(string $name, array $options = []): Form
    {
        $options['type'] = 'checkbox';
        $this->setData(FormEnums::CHECKBOX, $name, $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     * @return $this
     */
    public function addButton(string $name, array $options = []): Form
    {
        $options['type'] = 'button';
        $this->setData(FormEnums::BUTTON, $name, $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     * @return $this
     */
    public function addDiv(string $name, array $options = []): Form
    {
        $options['type'] = 'div';
        $this->setData(FormEnums::DIV, $name, $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     * @return $this
     */
    public function addFileInput(string $name, array $options = []): Form
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

        return $this;
    }

    /**
     * @param string $text
     * @param array $options
     * @return $this|null
     */
    public function setSubmitValue(string $text, array $options = []): ?Form
    {
        if (!$this->options['submit']) {
            return null;
        }

        $this->submit['value'] = $text;
        isset($options['class']) ? $this->submit['class'] = $options['class'] : $this->submit['class'] = '';

        return $this;
    }

    /**
     * @param string $css
     * @return $this
     */
    public function addCss(string $css): Form
    {
        $this->css .= $css . ' ';

        return $this;
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
            $render .= $this->renderInput($input, $globalOptions);
        }

        $render .= "    <input type=\"hidden\"  name=\"formName\" value=\"{$this->name}\">" . PHP_EOL;

        $render .= $this->renderFormButton();
        $render .= "</form>";
        $form['render'] = $render;
        $form['data']['fields'] = $this->data;
        $form['data']['action'] = $this->action;
        $form['data']['options'] = $this->options;
        $form['data']['fields']['formName'] = ['name' => 'formName', 'value' => $this->name, 'type' => 'hidden'];
        $form['data']['fields']['csrf'] = ['name' => 'csrf', 'value' => $token, 'type' => 'hidden'];
        $form['name'] = $this->name;
        return $form;
    }

    protected function renderFormButton(): string
    {
        if (isset($this->submit) && $this->options['submit']) {
            return "    <input type=\"submit\"  class=\"{$this->submit['class']}\" value=\"{$this->submit['value']}\">" . PHP_EOL;
        } elseif ($this->options['submit']) {
            return '    <input type="submit" class="" value="Submit">' . PHP_EOL;
        }

        return '';
    }

    protected function renderInput($input, $globalOptions): string
    {
        $render = '';
        if ('hidden' === $input['type']) {
            $render .= self::DOUBLE_INDENT . $input['render'] . PHP_EOL;
        } else {
            $render .= "    <div class='{$globalOptions['wrapperClass']} {$input['wrapperClass']}'>" . PHP_EOL;
            if ('button' !== $input['type']) {
                if (isset($input['inputBeforeLabel']) && true === $input['inputBeforeLabel']) {$render .=    self::DOUBLE_INDENT . $input['render'] . PHP_EOL;}
                $render .=    "        <label for='{$input['id']}'>{$input['label']}</label>" . PHP_EOL;
            }
            if (!isset($input['inputBeforeLabel']) || (isset($input['inputBeforeLabel']) && false === $input['inputBeforeLabel'])) {
                $render .=    self::DOUBLE_INDENT . $input['render'] . '</div>'. PHP_EOL;
            } else {
                $render .=    '        </div>'. PHP_EOL;
            }
        }

        return $render;
    }

    /**
     * @param array $type
     * @param string $name
     * @param array $options
     */
    protected function setData(array $type, string $name, array $options)
    {

        if ($this->session->has('formData') && 'password' !== $type['type']) {
            $formData = $this->session->get('formData');
            if ($this->name === $formData['formName'] && array_key_exists($name, $formData['data'])) {
                $options['value'] = $formData['data'][$name]['result'];
                $options['error'] = ['status' => $formData['data'][$name]['status'], 'error' => $formData['data'][$name]['error']];
            }
        }

        $options['id'] ??  $options['id'] = $name;

        if ('textarea' === $type['type']) {
            $input = "<textarea name='{$name}' ";
        } elseif ('button' === $type['type'] || 'div' === $type['type']) {
            $input = "<{$type['type']} ";
        } else {
            $input = "<input type='{$type['type']}' name='{$name}' ";
        }

        $input .= $this->setInputOptions($type, $options);
        $input .= ">";

        if ('textarea' === $type['type'] || 'button' === $type['type'] || 'div' === $type['type']) {
            if(isset($options['value'])) {
                $input .= $options['value'];
            }

            $input .= "</{$type['type']}>";
        }

        $fieldData = $this->setDataOptions($name, $options, $type);
        $fieldData['render'] = $input;
        $this->data[$name] = $fieldData;
    }

    /**
     * @param array $type
     * @param array $options
     * @param string $name
     * @return string
     */
    private function setInputOptions(array $type, array $options): string
    {
        $input = '';
        foreach ($options as $optionName => $option) {
            if (!in_array($optionName, $type['attributes'])) {
                continue;
            }

            if ('dataAttributes' === $optionName) {
                $input .= $this->setDataAttributes($option);
                continue;
            }

            if ((isset($options['error']) && true === $options['error']['status']) && 'class' === $optionName && (isset($this->options['errorClass']) || isset($options['errorClass']))) {
                $option .= ' ' . $this->options['errorClass']?? '' . $options['errorClass']?? '';
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

        return $input;
    }

    /**
     * @param $option
     * @return string
     */
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
     * @param $type
     * @return array
     */
    private function setDataOptions($name, $options, $type): array
    {
        $fieldData['required'] = $this->setRequiredField($options);

        $fieldData['result'] = '';

        if ('file' === $type['type']) {
            $fieldData['whitelist'] = $options['whitelist'];
        }

        isset($options['inputBeforeLabel']) ? $fieldData['inputBeforeLabel'] = $options['inputBeforeLabel'] : $fieldData['inputBeforeLabel'] = false;
        isset($options['error']) ? $fieldData['error'] = $options['error'] : $fieldData['error'] = null;
        isset($options['sanitize']) ? $fieldData['sanitize'] = $options['sanitize'] : $fieldData['sanitize'] = true;
        isset($options['hash']) ? $fieldData['hash'] = $options['hash'] : $fieldData['hash'] = null;
        isset($options['entity']) ? $fieldData['entity'] = $options['entity'] : $fieldData['entity'] = $this->entity;
        isset($options['value']) ? $fieldData['value'] = $options['value'] : $fieldData['value'] = null;
        isset($options['modifyIfEmpty']) ? $fieldData['modifyIfEmpty'] = $options['modifyIfEmpty'] : $fieldData['modifyIfEmpty'] = null;
        isset($options['class']) ? $fieldData['class'] = explode(' ', $options['class']) : $fieldData['class'] = null;
        isset($options['targetField']) ? $fieldData['targetField'] = $options['targetField'] : $fieldData['targetField'] = null;
        isset($options['fieldName']) ? $fieldData['fieldName'] = $options['fieldName'] : $fieldData['fieldName'] = $name;
        isset($options['type']) ? $fieldData['type'] = $options['type'] : $fieldData['type'] = null;
        isset($options['placeholder']) ? $fieldData['placeholder'] = $options['placeholder'] : $fieldData['placeholder'] = $name;
        isset($options['label']) ? $fieldData['label'] = $options['label'] : $fieldData['label'] = $fieldData['placeholder'];
        isset($options['wrapperClass']) ? $fieldData['wrapperClass'] = $options['wrapperClass'] : $fieldData['wrapperClass'] = '';
        isset($options['dataAttributes']) ? $fieldData['dataAttributes'] = $options['dataAttributes'] : $fieldData['dataAttributes'] = null;
        isset($options['property']) ? $fieldData['property'] = $options['property'] : $fieldData['property'] = null;
        $fieldData['id'] = $options['id'];

        return $fieldData;
    }

    protected function setRequiredField(array $options): bool
    {
        if (isset($options['required']) && false === $options['required']) {
            return false;
        }

        return true;
    }

    /**
     * @param Request $request
     * @throws Exception
     */
    public function handle(Request $request, bool $isJson = false)
    {
        $this->updateSession($isJson);
        $currentRequest = $this->validateRequest($request, $isJson);
        if ($currentRequest instanceof FormHandleException) {
            $this->errors['request'] = ['error' => $currentRequest, 'status' => true, 'result' => $request];
            return;
            }

            $this->isSubmitted = true;
            $filesArray = [];

            foreach ($this->data as $fieldName => $fieldData) {
                if ('div' === $fieldData['type'] || 'button' === $fieldData['type']) {
                    continue;
                }
                if ('file' === $fieldData['type']) {
                    $newFileData = $this->handleFile($currentRequest->files[$fieldName], $fieldData);
                    if ($newFileData instanceof FormHandleException){
                        $this->errors[$fieldName] = ['error' => $newFileData, 'status' => true, 'result' => $currentRequest->files[$fieldName]];
                        continue;
                    }

                    $filesArray[$fieldName] = $newFileData;
                    continue;
                }

                $this->checkField($fieldName, $fieldData, $currentRequest);
            }

            foreach ($filesArray as $name => $data) {
                if ($data){
                    $this->data[$name]['result'] = new FormFile($data['tmp_name'], $this->data, $data['name'], $data['type'], $data['extension']);
                }
            }

            if ($this->errors) {
                $this->setFormError();
                return;
            }

            $this->isValid = true;
    }

    /**
     * @throws Exception
     */
    private function checkField($fieldName, $fieldData, $currentRequest)
    {
        if (false === $requestField = $this->validateAndSanitizeRequest($fieldData, $fieldName, $currentRequest)) {
            return;
        }

        if (false === $fieldData['entity']) {
            $this->data[$fieldName]['result'] = $requestField;
            return;
        }

        if (false === $this->validateAndHydrateEntity($fieldData, $requestField)) {
            $this->errors[$fieldName]= ['error' => new FormHandleException($fieldData['type'], $fieldName, "Field ${fieldName} is not a valid entity property"), 'status' => true, 'result' => $requestField];
            return;
        }

        $this->data[$fieldName]['result'] = $requestField;
    }

    private function updateSession(bool $isJson)
    {
        if (false === $isJson) {
            $this->updateToken();
            $this->session->remove('formData');
        }
    }

    protected function updateToken()
    {
        $token = Uuid::uuid4();

        $oldToken = $this->session->get('csrf-new');
        $this->session->set('csrf-old', $oldToken);
        $this->session->set('csrf-new', $token->toString());
    }

    private function validateAndSanitizeRequest($fieldData, $fieldName, $currentRequest)
    {
        $currentRequest->request[$fieldName] = $this->sanitizeRequest( $currentRequest->request, $fieldData, $fieldName);

        if ((!isset( $currentRequest->request[$fieldName]) || "" ==  $currentRequest->request[$fieldName]) && true === $fieldData['required']) {
            $this->errors[$fieldName] = ['error' => new FormHandleException($fieldData['type'], $fieldName, "Field ${fieldName} is missing from the request and is required"), 'status' => true, 'result' => $currentRequest->request[$fieldName] ?? ''];
            return false;
        }

        if (false === $requestField = $this->validateRequestField($fieldData,  $currentRequest->request[$fieldName])) {
            $this->errors[$fieldName] = ['error' => new FormHandleException($fieldData['type'], $fieldName, "Field ${fieldName} is invalid"), 'status' => true, 'result' => $requestField];
            return false;
        }

        return $requestField;
    }

    private function handleFile(array $currentField, $fieldData)
    {
        $fileError = null;
        if (!is_uploaded_file($currentField['tmp_name'])) {
            return new FormHandleException('file', $currentField['name'], "File [${$currentField['type']}] doesn't exist" );
        }

        $newFileData = $currentField;
        $newFileData['type'] =  mime_content_type($newFileData['tmp_name']);
        $newFileData['name'] = StringUtils::slugify(pathinfo($newFileData['name'])['filename']) . '.' . pathinfo($newFileData['name'])['extension'];
        $newFileData['extension'] = pathinfo($newFileData['name'])['extension'];

        if ($fieldData['whitelist']) {
            $fileError = $this->validateFileWhitelist($newFileData, $fieldData);
        }

        if (($newFileData['type'] !== $currentField['type'] || mb_strlen($newFileData['name']) > 250 || 0 === $newFileData['size']) && !isset($fileError)) {
            $fileError = new FormHandleException('file', $newFileData['name'], "File [${$newFileData['name']}] encountered en error");
        }

        if (isset($fileError) && $fileError instanceof FormHandleException) {
            return $fileError;
        }

        return $newFileData;
    }

    public function validateFileWhitelist(array $newFileData, array $fieldData): ?FormHandleException
    {
        if ('manual' === $fieldData['whitelist']['type']) {
            $mimes = explode(',', trim($fieldData['whitelist']['mimes']));
            if (!in_array($newFileData['type'], $mimes)) {
                return new FormHandleException('file', $newFileData['name'], "File MIME [${$newFileData['type']}] doesn't correspond to manual whitelist" );
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

            if (!in_array($newFileData['type'], $totalMimes) && !isset($fileError)) {
                return new FormHandleException('file', $newFileData['name'], "File MIME [${$newFileData['type']}]doesn't correspond to enum whitelists" );
            }
        }

        return null;
    }

    private function sanitizeRequest($currentRequest, $fieldData, $fieldName)
    {
        if (!isset($currentRequest[$fieldName])) {
            return null;
        }

        $currentRequestField = $currentRequest[$fieldName];
        if ($fieldData['sanitize']) {
            $currentRequestField = htmlspecialchars($currentRequestField);
        }

        return $currentRequestField;
    }

    public function setFormError()
    {
        foreach ($this->data as $fieldName => $fieldData) {
            if (!array_key_exists($fieldName, $this->errors)) {
                $this->errors[$fieldName] = ['result' => $fieldData['result'], 'status' => false, 'error' => null];
            }
        }

        $formData['data'] = $this->errors;
        $formData['formName'] = $this->name;
        $this->session->set('formData', $formData);
    }

    /**
     * @param Request $request
     * @return FormHandleException|Request
     */
    private function validateRequest(Request $request, bool $isJson = false)
    {
        false === $isJson ? $oldToken = $this->session->get('csrf-old') : $oldToken = $this->session->get('csrf-new');

        if (!isset($request->request)) {
            $request = new FormHandleException('request', 'the request doesn\'t exist');
        } elseif (isset($this->name) && (!isset($request->request['formName']) || $this->name !== $request->getRequest('formName'))) {
            $request = new FormHandleException('requestName', 'the request names don\'t match');
        } elseif ( !isset($request->request['csrf']) || !isset($oldToken) || $request->getRequest('csrf') !== $oldToken) {
            $this->isSubmitted = true;
            $request = new FormHandleException('csrf', 'the csrf tokens don\'t match');
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

        if (isset($fieldData['type']) && 'password' === $fieldData['type'] && isset($fieldData['hash']) && true === $fieldData['hash'] && !empty($requestField)) {
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
    private function validateAndHydrateEntity(array $fieldData, string $requestField): bool
    {
        $entityName = strtolower(ClassUtils::getClassNameFromObject($fieldData['entity']));
        if (!isset($this->allEntityData[$entityName]['fields'][$fieldData['fieldName']])) {
            return false;
        }

        $newTypeData = StringUtils::changeTypeFromValue($requestField);
        $fieldType = gettype($newTypeData);

        if (isset($fieldData['type']) && 'datetime' === $fieldData['type'] && preg_match('#^(?:(\d{4}-\d{2}-\d{2})T(\d{2}:\d{2}:\d{2}?))$#', $requestField, $match)) {
            $newTypeData = new DateTime($match[0]);
            $fieldType = strtolower(get_class($newTypeData));
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

        if (isset($fieldData['modifyIfEmpty']) && false === $fieldData['modifyIfEmpty'] && empty($requestField)) {
            return true;
        }

        $fieldData['entity']->$entityMethod($associatedEntity ?? $newTypeData);
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

    public function getAllData(): array
    {
        return $this->data;
    }
}