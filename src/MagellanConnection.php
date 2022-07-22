<?php

namespace Clickbar\Magellan;

use Clickbar\Magellan\Schema\Builder;
use Clickbar\Magellan\Schema\Grammars\PostgisGrammar;
use Illuminate\Database\PostgresConnection;

class MagellanConnection extends PostgresConnection
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
