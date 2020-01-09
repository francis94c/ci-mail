<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('CIMail.php');

class CIMailer
{
  private $email;
  private $from = [];

  function __construct(?array $config=null) {
    if ($config != null) {
      $this->from = $config['from'] ?? null;
    }

    get_instance()->load->library('email');
    get_instance()->email->initialize($this->get_mail_config());

    spl_autoload_register(function ($name) {
      if (file_exists(APPPATH."mails/$name.php")) {
        require(APPPATH."mails/$name.php");
      }
    });
  }

  /**
   * [create description]
   * @date   2020-01-08
   * @param  [type]     $email [description]
   * @param  [type]     $args  [description]
   * @return CIMail            [description]
   */
  public function &create($email, ...$args):CIMail
  {
    if (is_string($email)) $email = new $email(...$args);
    $this->email = $email;
    return $this->email;
  }

  /**
   * [reset description]
   * @date  2020-01-09
   * @param bool       $clearAttatchments [description]
   */
  public function reset(bool $clearAttatchments)
  {
    get_instance()->email->clear($clearAttatchments);
  }

  /**
   * [get_mail_config description]
   * @date   2020-01-08
   * @return array      [description]
   */
  private function get_mail_config():array
  {
    return [
      'protocol'  => 'smtp',
      'mailtype'  => 'html',
      'charset'   => 'utf-8',
      'wordwrap'  => TRUE,
      'newline'   => "\r\n"
    ];
  }

  /**
   * [send description]
   * @date   2020-01-08
   * @return bool       [description]
   */
  public function send():bool
  {
    if ($this->email == null) {
      throw new Exception('No Email to send.');
    }

    $this->email->build();

    $from = $this->from;
    $from = $this->email->from ?? $from;

    if (is_string($from)) $from = [$from, $from];

    if (!$from || !is_array($from)) {
      throw new Exception("Email source 'from' not specified.");
    }

    get_instance()->email->from($from[0], $from[1]);
    get_instance()->email->to($this->email->to);
    get_instance()->email->subject($this->email->subject);
    get_instance()->email->message($this->email->view);
    if ($this->email->text) {
      get_instance()->email->set_alt_message($this->email->text);
    }

    if ($this->email->replyTo) get_instance()->email->reply_to($this->replyTo[0], $this->replyTo[1]);

    return get_instance()->email->send();
  }
}
