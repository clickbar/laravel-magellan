<?php

namespace Clickbar\Postgis;

use Clickbar\Postgis\Schema\Builder;
use Clickbar\Postgis\Schema\Grammars\PostgisGrammar;
use Illuminate\Database\PostgresConnection;

class PostgisConnection extends PostgresConnection
{
    protected function getDefaultSchemaGrammar(): PostgisGrammar
    {
        $grammar = new PostgisGrammar();
        $grammar->setTablePrefix($this->tablePrefix);

        return $grammar;
    }

    public function getSchemaGrammar(): ?PostgisGrammar
    {
        /** @var PostgisGrammar|null $grammar */
        $grammar = parent::getSchemaGrammar();

        return $grammar;
    }

    public function getSchemaBuilder(): Builder
    {
        if ($this->schemaGrammar === null) {
            $this->useDefaultSchemaGrammar();
        }

        return new Builder($this);
    }
}
