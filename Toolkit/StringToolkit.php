<?php

/*
 * This class is heaveliy inspired by the sfToolkit one of symfony 1.4
 * and by the dmString class of Diem
 */
namespace Bundle\TagHelperBundle\Toolkit;

class StringToolkit
{

   /**
   * Replaces constant identifiers in a scalar value.
   *
   * @param  string $value  the value to perform the replacement on
   *
   * @return string the value with substitutions made
   */
  public static function replaceConstants($value)
  {
    return is_string($value) ? preg_replace_callback('/%(.+?)%/', create_function('$v', 'return "%{$v[1]}%";'), $value) : $value;
  }

  /**
   * Finds the type of the passed value, returns the value as the new type.
   *
   * @param  string $value
   * @param  bool   $quoted  Quote?
   *
   * @return mixed
   */
  public static function literalize($value, $quoted = false)
  {
    // lowercase our value for comparison
    $value  = trim($value);
    $lvalue = strtolower($value);

    if (in_array($lvalue, array('null', '~', '')))
    {
      $value = null;
    }
    else if (in_array($lvalue, array('true', 'on', '+', 'yes')))
    {
      $value = true;
    }
    else if (in_array($lvalue, array('false', 'off', '-', 'no')))
    {
      $value = false;
    }
    else if (ctype_digit($value))
    {
      $value = (int) $value;
    }
    else if (is_numeric($value))
    {
      $value = (float) $value;
    }
    else
    {
      $value = self::replaceConstants($value);
      if ($quoted)
      {
        $value = '\''.str_replace('\'', '\\\'', $value).'\'';
      }
    }

    return $value;
  }
  
  /**
   * Converts string to array
   *
   * @param  string $string  the value to convert to array
   *
   * @return array
   */
  public static function stringToArray($string)
  {
    preg_match_all('/
      \s*(\w+)              # key                               \\1
      \s*=\s*               # =
      (\'|")?               # values may be included in \' or " \\2
      (.*?)                 # value                             \\3
      (?(2) \\2)            # matching \' or " if needed        \\4
      \s*(?:
        (?=\w+\s*=) | \s*$  # followed by another key= or the end of the string
      )
    /x', $string, $matches, PREG_SET_ORDER);

    $attributes = array();
    foreach ($matches as $val)
    {
      $attributes[$val[1]] = self::literalize($val[3]);
    }

    return $attributes;
  }

  /**
   * Transform css options to array options
   * e.g. "#an_id.a_class.another_class"
   * results in array(
   *    id => an_id
   *    class => array(a_class, another_class)
   *  )
   * only expressions before the first space are taken into account
   * @return array options
   */
  public static function retrieveCssFromString(&$string, &$opt)
  {
    if (empty($string))
    {
      return null;
    }

    $string = trim($string);

    $firstSpacePos = strpos($string, ' ');

    $firstSharpPos = strpos($string, '#');

    // if we have a # before the first space
    if (false !== $firstSharpPos && (false === $firstSpacePos || $firstSharpPos < $firstSpacePos))
    {
      // fetch id
      preg_match('/#([\w\-]*)/', $string, $id);
      if (isset($id[1]))
      {
        $opt['id'] = $id[1];
        $string = self::str_replace_once('#'.$id[1], '', $string);

        if (false != $firstSpacePos)
        {
          $firstSpacePos = $firstSpacePos - strlen($id[1]) - 1;
        }
      }
    }

    // while we find dots in the string
    while(false !== ($firstDotPos = strpos($string, '.')))
    {
      // if the string contains a space, and the dot is after this space, then it's not a class
      if (false !== $firstSpacePos && $firstDotPos > $firstSpacePos)
      {
        break;
      }

      // fetch class
      preg_match('/\.([\w\-]*)/', $string, $class);

      if (isset($class[1]))
      {
        if (isset($opt['class']))
        {
          $opt['class'][] = $class[1];
        }
        else
        {
          $opt['class'] = array($class[1]);
        }

        if (false != $firstSpacePos)
        {
          $firstSpacePos = $firstSpacePos - strlen($class[1]) - 1;
        }
      }

      $string = self::str_replace_once('.'.$class[1], '', $string);
    }
  }

  public static function retrieveOptFromString(&$string, &$opt)
  {
    if (empty($string))
    {
      return null;
    }

    $opt = array_merge($opt, self::stringToArray($string));

    $string = '';
  }

  /**
   * replace $search by $replace in $subject, only once
   */
  public static function str_replace_once($search, $replace, $subject)
  {
    $firstChar = strpos($subject, $search);

    if($firstChar !== false)
    {
      return substr($subject,0,$firstChar).$replace.substr($subject, $firstChar + strlen($search));
    }
    else
    {
      return $subject;
    }
  }
}