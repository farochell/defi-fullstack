<?php
/**
 * @author Emile Camara <camara.emile@gmail.com>
 * @project  defi-fullstack
 */
declare(strict_types=1);

namespace App\Shared\Domain;

use ArrayIterator;
use Countable;
use IteratorAggregate;

/**
 * @template T of object
 * @implements IteratorAggregate<int, T>
 */
abstract class Collection implements Countable, IteratorAggregate
{
    /** @var array<int, T> */
    private array $items;

    /**
     * @param array<int, T> $items
     */
    public function __construct(array $items)
    {
        Assert::arrayOf($this->type(), $items);
        $this->items = $items;
    }

    /** @return class-string<T> */
    abstract protected function type(): string;

    /** @return ArrayIterator<int, T> */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }

    /** @param T $item */
    public function add(object $item): void
    {
        Assert::instanceOf($this->type(), $item);

        $this->items[] = $item;
    }

    public function isEmpty(): bool
    {
        return $this->items === [];
    }

    /** @return array<int, T> */
    protected function items(): array
    {
        return $this->items;
    }
}
