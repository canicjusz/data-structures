<?php declare(strict_types=1);

namespace Src;

require 'helpers.php';

use Src\Node;

abstract class AbstractLinkedList
{
  public ?Node $head;
  public ?Node $tail;
  private int $length;

  abstract public function length(): int;
  abstract public function insertAt($value, int $index): ?\Exception;
  abstract public function remove(Node $item): void;
  abstract public function removeAt(int $index): Node;
  abstract public function prepend($value): void;
  abstract public function append($value): void;
  abstract public function pop(): Node|null;
  abstract public function unshift(): Node|null;
  abstract public function get(int $index): Node|\Exception;
}

class LinkedList extends AbstractLinkedList
{
  public ?Node $head;
  public ?Node $tail;
  private int $length;
  public function __construct(...$values)
  {
    $this->length = 0;
    $this->head = $this->tail = null;

    foreach ($values as $value) {
      $this->append($value);
    }
  }

  public function length(): int
  {
    return $this->length;
  }

  private function addFirstElement(Node $node): void
  {
    if (!$this->tail) {
      $this->tail = $this->head = $node;
    }
  }

  public function append($value): void
  {
    $this->length++;
    $node = new Node($value);
    if (!$this->tail) {
      $this->addFirstElement($node);
      return;
    }
    $old_tail = $this->tail;
    $node->prev = $old_tail;
    $old_tail->next = $node;
    $this->tail = $node;
  }

  public function prepend($value): void
  {
    $this->length++;
    $node = new Node($value);
    if (!$this->tail) {
      $this->addFirstElement($node);
      return;
    }
    $old_head = $this->head;
    $node->next = $old_head;
    $old_head->prev = $node;
    $this->head = $node;
  }

  private function checkIndex(int $index): ?\Exception
  {
    if ($index >= $this->length || $index < 0) {
      throw new \Exception("The index exceedes the length of the list.");
    }
    return null;
  }

  public function insertAt($value, int $index): ?\Exception
  {
    $this->checkIndex($index);
    if ($index === 0) {
      $this->prepend($value);
      return null;
    } else if ($index === $this->length - 1) {
      $this->append($value);
      return null;
    }
    $curr_node = new Node($value);
    $next_node = $this->get($index);
    $previous_node = $next_node->prev;

    $previous_node->next = $curr_node;
    $curr_node->prev = $previous_node;

    $next_node->prev = $curr_node;
    $curr_node->next = $next_node;
  }
  public function remove(Node $item): void
  {
    if (isset($item->prev)) {
      $item->prev->next = $item->next;
    } else {
      $this->head = $item->next;
    }
    if (isset($item->next)) {
      $item->next->prev = $item->prev;
    } else {
      $this->tail = $item->prev;
    }
    $this->length--;
  }

  public function get(int $index): Node|\Exception
  {
    $this->checkIndex($index);
    $curr_node = $this->head;
    while ($index-- > 0) {
      $curr_node = $curr_node->next;
    }
    return $curr_node;
  }

  public function removeAt(int $index): Node
  {
    $node_to_delete = $this->get($index);
    $this->remove($node_to_delete);
    return $node_to_delete;
  }

  public function pop(): Node|null
  {
    $prev_tail = $this->tail;
    if (!$prev_tail) {
      return null;
    }
    $this->remove($prev_tail);
    return $prev_tail;
  }

  public function unshift(): Node|null
  {
    $prev_head = $this->head;
    if (!$prev_head) {
      return null;
    }
    $this->remove($prev_head);
    return $prev_head;
  }

}