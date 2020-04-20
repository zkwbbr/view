# zkwbbr/view

Display template file inside a layout file using plain PHP

---

## Install

Install via composer as `zkwbbr/view`

## Sample Usage

Create a templates folder (e.g., `myTemplates/`)

Inside your templates folder, create a layout file (e.g., `myLayout.php`) and put the ff.

```html
<html>
    <head>
    <title><?=$title></title>
    </head>
    <body>
        <?=$templateContent?>
    </body>
</html>
```

Inside your templates folder, create a view file (e.g., `myView.php`) and put the ff.

```html
<h1><?=$heading?></h1>
<?=$body?>
```

In your PHP code (e.g., controller), put the ff.

```php
<?php

use Zkwbbr\View;

$data = [
    'title' => 'My Title',
    'heading' => 'My Heading',
    'body' => 'My Body'
];

$view = (new View\View)
    ->setData($data)
    ->setLayoutFile('myLayout')
    ->setTemplateVar('templateContent')
    ->setTemplateDir(__DIR__ . '/myTemplates/')
    ->setTemplate('myView')
    ->setBacktraceIndex(0) // # of nested calls relative to render(); for auto-detecting template file (try 0 first then increment until you find it)
    ->setStripStringFromTemplateFile('foo') // optional, remove string from template file (e.g., if your controller is the basis for auto template detection e.g., UserControllerIndex, and your actual template file is UserIndex, use 'Controller' as value here)
    ->render(); // you get also use generatedView() to return the generated view instead of outputting it
```

## Expected output

```html
<html>
    <head>
    <title>My Title</title>
    </head>
    <body>
        <h1>My Heading</h1>
        My Body
    </body>
</html>
```
