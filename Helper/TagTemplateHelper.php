<?php
namespace Bundle\TagHelperBundle\Helper;

use Symfony\Components\Templating\Helper\HelperInterface;
use Bundle\TagHelperBundle\Toolkit\StringToolkit;

class TagTemplateHelper implements HelperInterface
{
  protected $charset = 'UTF-8';
  protected $options;

  public function __construct(array $options = array())
  {
    $this->configure($options);
  }

  /**
   * Configures the current object.
   *
   * @param array $options     An array of options
   */
  public function configure(array $options = array())
  {
    $this->options = array_merge(
      null !== $this->options ? $this->options : $this->getDefaultOptions(),
      $options
    );
  }

  public function getDefaultOptions()
  {
    return array(
      'empty_elements'  => array('br', 'hr', 'img', 'input')
    );
  }

  /**
   * Sets the default charset.
   *
   * @param string $charset The charset
   */
  public function setCharset($charset)
  {
    $this->charset = $charset;
  }

  /**
   * Gets the default charset.
   *
   * @return string The default charset
   */
  public function getCharset()
  {
    return $this->charset;
  }

  public function getName()
  {
    return 'tag';
  }

  public function tag($tagName, $opt = array(), $content = false, $openAndClose = true)
  {
    if (!($tagName = trim($tagName)))
    {
      return '';
    }

    $tagOpt = array();

    // separate tag name from attribues in $tagName
    $firstSpacePos = strpos($tagName, ' ');

    if ($firstSpacePos)
    {
      $tagNameOpt = substr($tagName, $firstSpacePos + 1);
      $tagName = substr($tagName, 0, $firstSpacePos);

      // DMS STYLE - string opt in name
      StringToolkit::retrieveOptFromString($tagNameOpt, $tagOpt);
    }

    // JQUERY STYLE - css expression
    StringToolkit::retrieveCssFromString($tagName, $tagOpt);

    // ARRAY STYLE - array opt
    if (is_array($opt) && !empty($opt))
    {
      if (isset($opt['json']))
      {
        $tagOpt['class'][] = json_encode($opt['json']);
        unset($opt['json']);
      }
      if (isset($opt['class']))
      {
        $tagOpt['class'][] = is_array($opt['class']) ? implode(' ', $opt['class']) : $opt['class'];
        unset($opt['class']);
      }

      $tagOpt = array_merge($tagOpt, $opt);
    }
    // SYMFONY STYLE - string opt
    elseif (is_string($opt) && $content)
    {
      $opt = StringToolkit::stringToArray($opt);
      if (isset($opt['class']))
      {
        $tagOpt['class'][] = explode(' ', $opt['class']);
        unset($opt['class']);
      }

      $tagOpt = array_merge($tagOpt, $opt);
    }

    if (!$content)
    {
      if (!is_array($opt))
      {
        $content = $opt;
      }
      else // No opt
      {
        $content = null;
      }
    }

    $class = isset($tagOpt['class']) ? $tagOpt['class'] : array();

    if(isset($tagOpt['lang']))
    {
      if($tagOpt['lang'] === $this->context->getUser()->getCulture())
      {
        unset($tagOpt['lang']);
      }
    }
    
    if (isset($tagOpt['class']) && is_array($tagOpt['class']))
    {
      $tagOpt['class'] = implode(' ', array_unique($tagOpt['class']));
    }

    $optHtml = '';
    foreach ($tagOpt as $key => $val)
    {
      $optHtml .= ' '.$key.'="'.htmlentities($val, ENT_COMPAT, 'UTF-8').'"';
    }

    if(in_array($tagName, $this->options['empty_elements']))
    {
      $tag = '<'.$tagName.$optHtml.' />';
    }
    elseif ($openAndClose)
    {
      $tag = '<'.$tagName.$optHtml.'>'.$content.'</'.$tagName.'>';
    }
    else
    {
      $tag = '<'.$tagName.$optHtml.'>';
    }

    return $tag;
  }
}