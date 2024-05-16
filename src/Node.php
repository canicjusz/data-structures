<?php declare(strict_types=1);

namespace Src;

abstract class AbstractNode
{
  public $value;
  public ?Node $next;
  public ?Node $prev;
}

class Node extends AbstractNode
{
  public $value;
  public ?Node $next;
  public ?Node $prev;
  public function __construct($value)
  {
    $this->value = $value;
    $this->next = $this->prev = null;
  }
}