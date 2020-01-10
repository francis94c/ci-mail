<?php
defined('BASEPATH') OR exit('No direct script access allowed');

abstract class CIMail
{
  public $from;
  public $to;
  public $subject;
  public $view;
  public $text;
  public $replyTo;

  /**
   * [from description]
   * @date   2020-01-09
   * @param  string     $from     [description]
   * @param  string     $fromName [description]
   * @return CIMail               [description]
   */
  public function from(string $from, string $fromName):CIMail
  {
    $this->from = [$from, $fromName];
    return $this;
  }

  /**
   * [to description]
   * @date   2020-01-08
   * @param  string     $to [description]
   * @return CIMail         [description]
   */
  public function to(string $to):CIMail
  {
    $this->to = $to;
    return $this;
  }

  /**
   * [replyTo description]
   * @date   2020-01-09
   * @param  string     $replyTo     [description]
   * @param  string     $replyToName [description]
   * @return CIMail                  [description]
   */
  public function replyTo(string $replyTo, string $replyToName):CIMail
  {
    $this->replyTo = [$replyTo, $replyToName];
    return $this;
  }

  /**
   * [subject description]
   * @date   2020-01-08
   * @param  string     $subject [description]
   * @return CIMail              [description]
   */
  public function subject(string $subject):CIMail
  {
    $this->subject = $subject;
    return $this;
  }

  /**
   * [view description]
   * @date   2020-01-07
   * @param  string     $view [description]
   * @param  [type]     $data [description]
   * @return CIMail           [description]
   */
  public function view(string $view, array $data=[]):CIMail
  {
    $vars = get_object_vars($this);

    unset($vars['view']);
    unset($vars['text']);
    unset($vars['replyTo']);

    $this->view = get_instance()->load->view($view, array_merge($data, $vars), true);
    return $this;
  }

  /**
   * [text description]
   * @date   2020-01-07
   * @param  string     $text [description]
   * @return CIMail           [description]
   */
  public function text(string $text):CIMail
  {
    $this->text = $text;
    return $this;
  }

  /**
   * [abstract description]
   * @var [type]
   */
  abstract public function build();
}
