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
  public ?Node $next = null;
  public ?Node $prev = null;
  public function __construct(public $value, public readonly string $list_uid)
  {
  }
}