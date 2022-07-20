<?php

declare(strict_types=1);

namespace Zkwbbr\View;

class View
{
    /**
     * Array of template vars
     *
     * @var array
     */
    private $data = [];

    /**
     * HTTP status
     *
     * @var int
     */
    private $status = 200;

    /**
     * Location of template dir
     *
     * @var string
     */
    private $templateDir;

    /**
     * Template file to use without .php suffix
     *
     * @var string
     */
    private $template = null;

    /**
     * Render template using layout or not
     *
     * @var bool
     */
    private $useLayout = true;

    /**
     * Layout file to use without .php suffix
     *
     * @var string
     */
    private $layoutFile = 'layout.php';

    /**
     * Template variable name inside layout file.
     * Used to echo template contents within layout file.
     *
     * @var string
     */
    private $templateVar;

    /**
     * Backtrace index to auto-detect template file relative to where render()
     * is called
     *
     * @var int
     */
    private $backtraceIndex;

    /**
     * Word to strip from template file.
     *
     * E.g., If the autodetected file is 'UserControllerIndex' and the word to
     * strip is 'Controller', the final autodetected file will be UserIndex
     *
     * @var string
     */
    private $stripStringFromTemplateFile = null;

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status)
    {
        $this->status = $status;
        return $this;
    }

    public function getTemplateDir(): string
    {
        return $this->templateDir;
    }

    public function setTemplateDir(string $templateDir)
    {
        $this->templateDir = $templateDir;
        return $this;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setTemplate(?string $template)
    {
        $this->template = $template;
        return $this;
    }

    public function getUseLayout(): bool
    {
        return $this->useLayout;
    }

    public function setUseLayout(bool $useLayout)
    {
        $this->useLayout = $useLayout;
        return $this;
    }

    public function getLayoutFile(): string
    {
        return $this->layoutFile;
    }

    public function setLayoutFile(string $layoutFile)
    {
        $this->layoutFile = $layoutFile;
        return $this;
    }

    public function getTemplateVar(): string
    {
        return $this->templateVar;
    }

    public function setTemplateVar(string $templateVar)
    {
        $this->templateVar = $templateVar;
        return $this;
    }

    public function getBacktraceIndex(): int
    {
        return $this->backtraceIndex;
    }

    public function setBacktraceIndex(int $backtraceIndex)
    {
        $this->backtraceIndex = $backtraceIndex;
        return $this;
    }

    public function getStripStringFromTemplateFile(): ?string
    {
        return $this->stripStringFromTemplateFile;
    }

    public function setStripStringFromTemplateFile(string $stripStringFromTemplateFile)
    {
        $this->stripStringFromTemplateFile = $stripStringFromTemplateFile;
        return $this;
    }

    /**
     * Returns function name where render() based on backtrace index
     *
     * It's then assumed that the function name is the template name
     *
     * @param int $backtraceIndex
     * @return string
     */
    private function autoDetectedTemplate(int $backtraceIndex): string
    {
        $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

        $class = explode('\\', $bt[$backtraceIndex]['class']);
        $class = array_pop($class);

        $template = $class . ucfirst($bt[$backtraceIndex]['function']);

        if ($this->getStripStringFromTemplateFile())
            $template = str_replace($this->getStripStringFromTemplateFile(), '', $template);

        return $template;
    }

    /**
     * Generate the template view based from the setter methods
     *
     * @return string
     */
    public function generatedView(): string
    {
        // extract vars to view
        extract($this->getData());

        $template = $this->getTemplate();

        // auto detect template file
        if (is_null($template))
            $template = $this->autoDetectedTemplate($this->getBacktraceIndex());

        // use layout or not
        if ($this->getUseLayout()) {

            ob_start();
            require $this->getTemplateDir() . $template . '.php';
            ${$this->getTemplateVar()} = ob_get_clean();

            ob_start();
            require_once $this->getTemplateDir() . $this->getLayoutFile() . '.php';
            $appTemplateContent = ob_get_clean();

        } else {

            ob_start();
            require_once $this->getTemplateDir() . $template . '.php';
            $appTemplateContent = ob_get_clean();

        }

        return $appTemplateContent;
    }

    /**
     * Render the template view based from the setter methods
     *
     * Note: we put the view generation code in generatedView() for testability
     *
     * @return void
     */
    public function render(): void
    {
        http_response_code($this->getStatus());

        echo $this->generatedView();

        exit;
    }

}