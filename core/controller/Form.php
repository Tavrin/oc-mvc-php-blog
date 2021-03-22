<?php


namespace Core\controller;


use Core\http\Request;

class Form
{
    protected string $method = 'POST';
    protected string $name = '';
    protected ?string $action = null;
    protected array $data = [];
    protected string $css = '';
    protected object $entity;
    protected ?array $submit = null;

    /**
     * Form constructor.
     * @param string $currentAction
     * @param object $entity
     * @param array $options
     */
    public function __construct(string $currentAction, object $entity, array $options)
    {
        if (isset($options['method'])) {
            $this->method = $options['method'];
        }

        isset($options['name']) ? $this->name = $options['name'] : false;

        isset($options['action']) ? $this->action = $options['action'] : $this->action = $currentAction;
        $this->entity = $entity;
    }

    /**
     * @param string $name
     * @param array $options
     */
    public function addTextInput(string $name, array $options = [])
    {
        $this->setData(FormEnums::TEXT, $name, $options);
    }

    /**
     * @param string $name
     * @param array $options
     */
    public function addPasswordInput(string $name, array $options = [])
    {
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
        $render = "<form action='{$this->action}' method='{$this->method}' class='{$this->css}'>" . PHP_EOL;
        foreach ($this->data as $input) {
            $render .= '    <div class="mb-1">' . PHP_EOL .
            "        <label for='{$input['id']}'>{$input['placeholder']}</label>" . PHP_EOL .
            '        ' . $input['render'] . '</div>';
        }

        $render .= "    <input type=\"hidden\"  name=\"formName\" value=\"{$this->name}\">" . PHP_EOL;

        if (isset($this->submit)) {
            $render .= "    <input type=\"submit\"  class=\"{$this->submit['class']}\" value=\"{$this->submit['value']}\">" . PHP_EOL;
        } else {
            $render .= '    <input type="submit" class="" value="Submit">' . PHP_EOL;
        }

        $render .= "</form>";

        return $render;
    }

    /**
     * @param array $type
     * @param string $name
     * @param array $options
     */
    protected function setData(array $type, string $name, array $options)
    {
        $input = "<input type='{$type['type']}  ' name='{$name}' " ;

        isset($options['id']) ? true :  $options['id'] = $name;
        foreach ($options as $optionName => $option) {
            if (!in_array($optionName, $type['attributes'])) {
                continue;
            }

            if ('readonly' === $optionName || 'required' === $optionName) {
                $input .= "{$optionName} ";
                continue;
            }

            $input .= $optionName . "=\"{$option}\" ";
        }

        $input .= ">";
        isset($options['entity']) ? $fieldData['entity'] = $options['entity'] : $fieldData['entity'] = $this->entity;
        isset($options['required']) ? $fieldData['required'] = $options['required'] : $fieldData['required'] = false;
        isset($options['type']) ? $fieldData['type'] = $options['type'] : false;
        isset($options['placeholder']) ? $fieldData['placeholder'] = $options['placeholder'] : $fieldData['placeholder'] = $name;
        isset($options['property']) ? $fieldData['property'] = $options['property'] : false;
        $fieldData['id'] = $options['id'];
        $fieldData['render'] = $input;
        $this->data[$name] = $fieldData;
    }

    public function handle(Request $request)
    {
        if (!isset($request->request) || (isset($this->name) && !isset($request->request['formName']))) {
            return;
        }

        if ($this->name !== $request->getRequest('formName')) {
            return;
        }
    }
}