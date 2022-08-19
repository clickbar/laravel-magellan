<?php

namespace Clickbar\Magellan\Commands\Utils;

class TableColumnsCollection
{
    /** @var PostgisColumnInformation[][] */
    protected array $columns = [];

    public function __construct()
    {
        $this->columns = [];
    }

    public function add(string $tableName, PostgisColumnInformation $columnInformation): void
    {
        if (! isset($this->columns[$tableName])) {
            $this->columns[$tableName] = [];
        }
        $this->columns[$tableName][] = $columnInformation;
    }

    /**
     * @return PostgisColumnInformation[][]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }
}
