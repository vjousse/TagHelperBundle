TagHelperBundle
========================================================

Overview
--------

Just a simple Bundle which allow you to use the Diem tag helper in a Symfony 2 project. See http://diem-project.org/diem-5-0/doc/en/reference-book/template-helpers#tag-helpers:_tag-create-a-tag for more details.

Basically this code :

    [php]
    echo $view->tag->tag('div#test.toto', 'Foo');

Will output : 

`<div>` id="test" class="toto"&gt;Foo&lt;/div&gt; 

Requirements
------------

You will need the latest version of Symfony 2 (not the sandbox PR1 one). You can get it from github here: http://github.com/symfony/symfony

Installation
------------

The bledding edge version of Symfony is slightly different from the sandbox one.

  * First you will have to modify you config_dev.yml and delete the web.debug section. Replace it with

        [yml]
        profiler.config:
          toolbar: true
      
  * Then in your Application kernel (HelloKernel.php for the sandbox) your registerBundles method should be something like this:
  
        [php]    
        public function registerBundles()
        {

          $bundles = array(
            new Symfony\Foundation\Bundle\KernelBundle(),
            new Symfony\Framework\WebBundle\Bundle(),

            // enable third-party bundles
            new Symfony\Framework\ZendBundle\Bundle(),
            new Symfony\Framework\DoctrineBundle\Bundle(),
            new Symfony\Framework\SwiftmailerBundle\Bundle(),

            // register your bundles here
            new Application\HelloBundle\Bundle(),
            new Bundle\TagHelperBundle\Bundle(),
          );

          if ($this->isDebug())
          {
            $bundles[] = new Symfony\Framework\ProfilerBundle\Bundle();
          }

          return $bundles;
        }
    

Usage
-----

In a template file, you'll have to declare that you will 'use' the corresponding class

    [php]
    use Bundle\TagHelperBundle\Helper\TagTemplateHelper;

Then just add the helper to the already existing helpers 

    [php]
    $view->set(new TagTemplateHelper());

You're done ! You can do something like this:

    [php]
    echo $view->tag->tag('div#test.toto', 'Foo');
    
It will output : <div id="test" class="toto">Foo</div> 
    
Detailed documentation
----------------------

This helper is a port of the Diem tag helper, the documentation can be found here: http://diem-project.org/diem-5-0/doc/en/reference-book/template-helpers#tag-helpers:_tag-create-a-tag

TODO
----

See how phpUnit works to write some unit tests ;-)
    