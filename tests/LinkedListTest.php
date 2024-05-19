<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

use \Src\{LinkedList, Node};

class LinkedListTest extends TestCase
{

  private function assertNodesOrder(Node|null ...$nodes)
  {
    for ($i = 0; $i < count($nodes); $i++) {
      $curr_node = $nodes[$i];
      if ($curr_node === null)
        continue;
      if ($i < count($nodes) - 1) {
        $next_node = $nodes[$i + 1];
        $this->assertSame($curr_node->next, $next_node);
      }
      if ($i > 0) {
        $prev_node = $nodes[$i - 1];
        $this->assertSame($curr_node->prev, $prev_node);
      }
    }
  }

  private function assertOneNodeInList(LinkedList $linked_list, $head_value): void
  {
    $this->assertSame($linked_list->head, $linked_list->tail);
    $this->assertSame($linked_list->head->value, $head_value);
    $this->assertSame($linked_list->length(), 1);
  }
  public function testCreatingLinkedListWithOneValue(): void
  {
    $linked_list = new LinkedList('one');
    $this->assertOneNodeInList($linked_list, 'one');
  }

  public function testCreatingLinkedListWithMultipleValues(): void
  {
    $linked_list = new LinkedList('one', 'two', 'three');
    $head = $linked_list->head;

    $this->assertSame($head->value, 'one');
    $this->assertSame($head->next->value, 'two');
    $this->assertSame($head->next->next->value, 'three');
    $this->assertSame($head->next->next, $linked_list->tail);
    $this->assertSame($linked_list->length(), 3);
  }
  public function testPrepending(): void
  {
    $linked_list = new LinkedList('one', 'two', 'three');
    $old_head = $linked_list->head;
    $linked_list->prepend('zero');
    $new_head = $linked_list->head;

    $this->assertSame($new_head->value, 'zero');
    $this->assertNodesOrder(null, $new_head, $old_head);
    $this->assertSame($linked_list->length(), 4);
  }
  public function testAppending(): void
  {
    $linked_list = new LinkedList('one', 'two', 'three');
    $old_tail = $linked_list->tail;
    $linked_list->append('four');
    $new_tail = $linked_list->tail;

    $this->assertSame($new_tail->value, 'four');
    $this->assertNodesOrder($old_tail, $new_tail, null);
    $this->assertSame($linked_list->length(), 4);
  }
  public function testPopping(): void
  {
    $linked_list = new LinkedList('one', 'two', 'three');
    $old_tail = $linked_list->pop();
    $new_tail = $linked_list->tail;

    $this->assertSame($old_tail->value, 'three');
    $this->assertSame($new_tail->value, 'two');
    $this->assertNodesOrder($new_tail, null);
    $this->assertSame($linked_list->length(), 2);
  }
  public function testUnshift(): void
  {
    $linked_list = new LinkedList('one', 'two', 'three');
    $old_head = $linked_list->unshift();
    $new_head = $linked_list->head;

    $this->assertSame($old_head->value, 'one');
    $this->assertSame($new_head->value, 'two');
    $this->assertNodesOrder(null, $new_head);
    $this->assertSame($linked_list->length(), 2);
  }
  public function testRemoveAt(): void
  {
    $linked_list = new LinkedList('one', 'two', 'three');
    $second = $linked_list->removeAt(1);

    $this->assertNodesOrder($second->prev, $second->next);
    $this->assertSame($linked_list->length(), 2);
  }

  public function testGet(): void
  {
    $linked_list = new LinkedList('one', 'two', 'three');
    $second = $linked_list->get(1);

    $this->assertSame($second->value, 'two');
  }

  public static function provideIndexExceptionData(): array
  {
    return [
      [
        'insertAt',
        '',
        -1
      ],
      [
        'get',
        -1,
      ],
      [
        'removeAt',
        -1,
      ],
      [
        'insertAt',
        '',
        99,
      ],
      [
        'get',
        99,
      ],
      [
        'removeAt',
        99,
      ],
    ];

  }

  #[DataProvider('provideIndexExceptionData')]
  public function testIndexExceptions(string $method_name, ...$args): void
  {
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('The index exceedes the length of the list.');

    $linked_list = new LinkedList('one', 'two', 'three');
    call_user_func([$linked_list, $method_name], ...$args);
  }

  public function testRemove(): void
  {
    $linked_list = new LinkedList('one', 'two', 'three', 'four');
    $three = $linked_list->tail->prev;
    $linked_list->remove($three);

    $this->assertNodesOrder($three->prev, $three->next);


    $tail = $linked_list->tail;
    $linked_list->remove($tail);
    $new_tail = $linked_list->tail;

    $this->assertNodesOrder($new_tail, null);
    $this->assertSame($new_tail->value, 'two');


    $head = $linked_list->head;
    $linked_list->remove($head);
    $new_head = $linked_list->head;

    $this->assertNodesOrder(null, $new_head);
    $this->assertSame($new_head->value, 'two');
  }

  public function testInsertAt(): void
  {
    $linked_list = new LinkedList();
    $two = $linked_list->insertAt('two', 0);

    $this->assertOneNodeInList($linked_list, 'two');


    $four = $linked_list->insertAt('four', 1);
    $new_tail = $linked_list->tail;

    $this->assertSame($new_tail->value, 'four');
    $this->assertSame($linked_list->length(), 2);


    $one = $linked_list->insertAt('one', 0);
    $new_head = $linked_list->head;

    $this->assertSame($new_head->value, 'one');
    $this->assertSame($linked_list->length(), 3);


    $three = $linked_list->insertAt('three', 2);

    $this->assertSame($three->value, 'three');
    $this->assertSame($linked_list->length(), 4);
    $this->assertNodesOrder($one, $two, $three, $four);
  }

  public function testNodeNotBelonging(): void
  {
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage("The node doesn't belong to this list");

    $first_list = new LinkedList('one');
    $second_list = new LinkedList('one');
    $first_list->remove($second_list->head);
  }
}