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
  abstract public function insertAt($value, int $index): Node|\Exception;
  abstract public function remove(Node $item): ?\Exception;
  abstract public function removeAt(int $index): Node;
  abstract public function prepend($value): Node;
  abstract public function append($value): Node;
  abstract public function pop(): Node|null;
  abstract public function unshift(): Node|null;
  abstract public function get(int $index): Node|\Exception;
}

class LinkedList extends AbstractLinkedList
{
  public ?Node $head = null;
  public ?Node $tail = null;
  private int $length = 0;
  public readonly string $uid;
  public function __construct(...$values)
  {
    $this->uid = uniqid();
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
    $this->tail = $this->head = $node;
  }

  public function append($value): Node
  {
    $this->length++;
    $node = new Node($value, $this->uid);
    if (!$this->tail) {
      $this->addFirstElement($node);
      return $node;
    }
    $old_tail = $this->tail;
    $node->prev = $old_tail;
    $old_tail->next = $node;
    $this->tail = $node;
    return $node;
  }

  public function prepend($value): Node
  {
    $this->length++;
    $node = new Node($value, $this->uid);
    if (!$this->tail) {
      $this->addFirstElement($node);
      return $node;
    }
    $old_head = $this->head;
    $node->next = $old_head;
    $old_head->prev = $node;
    $this->head = $node;
    return $node;
  }

  private function checkIndex(int $index, bool $allowSameAsLength = false): ?\Exception
  {
    $rightBoundary = $allowSameAsLength ? $index > $this->length : $index >= $this->length;
    if ($index < 0 || $rightBoundary) {
      throw new \Exception("The index exceedes the length of the list.");
    }
    return null;
  }

  public function insertAt($value, int $index): Node|\Exception
  {
    $this->checkIndex($index, true);
    if ($index === 0) {
      return $this->prepend($value);
    } else if ($index === $this->length) {
      return $this->append($value);
    }
    $curr_node = new Node($value, $this->uid);
    $next_node = $this->get($index);
    $previous_node = $next_node->prev;

    $previous_node->next = $curr_node;
    $curr_node->prev = $previous_node;

    $next_node->prev = $curr_node;
    $curr_node->next = $next_node;

    $this->length++;

    return $curr_node;
  }
  public function remove(Node $item): ?\Exception
  {
    if ($item->list_uid !== $this->uid) {
      throw new \Exception("The node doesn't belong to this list");
    }
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
    return null;
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