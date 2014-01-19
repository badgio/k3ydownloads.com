<?php

// https://github.com/salathe/spl-examples/wiki/Sorting-Iterators
class SortableIterator extends ArrayIterator
{
    public function __construct(Traversable $iterator, $callback)
    {
        if (!is_callable($callback)) {
            throw new InvalidArgumentException(sprintf('Callback must be callable (%s given).', $callback));
        }

        parent::__construct(iterator_to_array($iterator));
        $this->uasort($callback);
    }
}
