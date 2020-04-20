<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Zkwbbr\View;

/**
 * Note: Don't remove the @runInSeparateProcess annotation in the methods or
 * you must run these tests one by one because the methods uses output
 * buffering functions which is causing issues in PHPunit if they are run
 * in one go
 */
class ViewTest extends TestCase
{
    private $view;
    private $data;

    public function setUp(): void
    {
        $this->data = [
            'foo' => 'hello',
            'bar' => 'world'
        ];

        $this->view = (new View\View)
            ->setData($this->data)
            ->setLayoutFile('sampleLayout')
            ->setTemplateVar('myTemplateVar')
            ->setTemplateDir(__DIR__ . '/testTemplates/');
    }

    public function tearDown(): void
    {

    }

    /**
     * @runInSeparateProcess
     */
    public function testViewWithLayout()
    {
        $this->view->setTemplate('sampleTemplate');

        $output = $this->view->generatedView();

        $expected = '<html><body><h1>' . $this->data['foo'] . '</h1>' . $this->data['bar'] . '</body></html>';

        $this->assertEquals($expected, $output);
    }

    /**
     * @runInSeparateProcess
     */
    public function testViewWithNoLayout()
    {
        $this->view->setTemplate('sampleTemplate');
        $this->view->setUseLayout(false);

        $output = $this->view->generatedView();

        $expected = '<h1>' . $this->data['foo'] . '</h1>';

        $this->assertEquals($expected, $output);
    }

    /**
     * @runInSeparateProcess
     */
    public function testViewWithAutoDetectTemplate()
    {
        $this->view->setBacktraceIndex(0);

        $output = $this->view->generatedView();

        $expected = '<html><body><h1>' . $this->data['foo'] . '</h1>' . $this->data['bar'] . '</body></html>';

        $this->assertEquals($expected, $output);
    }

    /**
     * @runInSeparateProcess
     */
    public function testStripStringFromTemplateFile()
    {
        $this->view->setBacktraceIndex(1);

        $this->view->setStripStringFromTemplateFile('Generated'); // word to remove in template file so final template file is ViewView.php

        $output = $this->view->generatedView();

        $this->assertStringContainsString('setStripWordFromTemplateFile', $output);
    }

}