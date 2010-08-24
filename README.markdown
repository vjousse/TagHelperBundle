TagHelperBundle
========================================================

Overview
--------

Just a simple Bundle which allow you to use the Diem tag helper in a Symfony2 project. See http://diem-project.org/diem-5-0/doc/en/reference-book/template-helpers#tag-helpers:_tag-create-a-tag for more details.

Basically this code :

    [php]
    echo $view['tag']->tag('div#test.toto', 'Foo');

Will output : 

`<div id="test" class="toto">Foo</div>`

Requirements
------------

You will need the PR3 version of Symfony2. You can get the sandbox from github here: http://github.com/symfony/symfony-sandbox/tree/PR3

Installation
------------

          
  * Install the bundle in src/Bundle
      $ cd src/Bundle
      $ git clone git://github.com/vjousse/TagHelperBundle.git
      
  * In your Application kernel (HelloKernel.php for the sandbox) your registerBundles method should be something like this:
  
        [php]    
        public function registerBundles()
        {
            $bundles = array(
                new Symfony\Framework\KernelBundle(),
                new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
                new Symfony\Bundle\ZendBundle\ZendBundle(),
                new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
                new Symfony\Bundle\DoctrineBundle\DoctrineBundle(),
                //new Symfony\Bundle\DoctrineMigrationsBundle\DoctrineMigrationsBundle(),
                //new Symfony\Bundle\DoctrineMongoDBBundle\DoctrineMongoDBBundle(),
                //new Symfony\Bundle\PropelBundle\PropelBundle(),
                //new Symfony\Bundle\TwigBundle\TwigBundle(),
                new Application\HelloBundle\HelloBundle(),
                new Bundle\TagHelperBundle\TagHelperBundle(),
            );

            if ($this->isDebug()) {
            }

            return $bundles;
        }
    

Usage
-----

In your config.yml file, you'll have to declare that you will 'use' the helper

    tag.config: ~

You're done ! You can do something like this in a template:

    [php]
    echo $view['tag']->tag('div#test.toto', 'Foo');
    
It will output : `<div id="test" class="toto">Foo</div>`
    
Detailed documentation
----------------------

This helper is a port of the Diem tag helper, the documentation can be found here: http://diem-project.org/diem-5-0/doc/en/reference-book/template-helpers#tag-helpers:_tag-create-a-tag
