<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use \Src\LinkedList;

class LinkedListTest extends TestCase
{
  public function testIsHeadTailForOneElement(): void
  {
    $linked_list = new LinkedList('hi');
    $this->assertSame($linked_list->head, $linked_list->tail);
  }
}