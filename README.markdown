TagHelperBundle
========================================================

Overview
--------

Just a simple Bundle which allow you to use the Diem tag helper in a Symfony 2 project. See http://diem-project.org/diem-5-0/doc/en/reference-book/template-helpers#tag-helpers:_tag-create-a-tag for more details.

Basically this code :

  [php]
  <?php echo $view->tag->tag('div#test.toto', 'Foo'); ?>

Will output : <div id="test" class="toto">Foo</div> 

Requirements
------------

You will need the latest version of Symfony 2 (not the sandbox PR1 one). You can get it from github here: http://github.com/symfony/symfony

Installation
------------

