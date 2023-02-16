<?php

namespace Clickbar\Magellan\Commands\Utils;

use Illuminate\Database\Eloquent\Model;

class ModelInformation
{
    public function __construct(
        protected string $modelClassName,
        protected string $relativePath,
        protected string $tableName,
    ) {
    }

    public function getModelClassName(): string
    {
        return $this->modelClassName;
    }

    public function getRelativePath(): string
    {
        return $this->relativePath;
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function getInstance(): Model
    {
        return new $this->modelClassName();
    }
}
