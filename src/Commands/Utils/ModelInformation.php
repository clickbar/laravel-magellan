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

    /**
     * @return string
     */
    public function getModelClassName(): string
    {
        return $this->modelClassName;
    }

    /**
     * @return string
     */
    public function getRelativePath(): string
    {
        return $this->relativePath;
    }

    /**
     * @return string
     */
    public function getTableName(): string
    {
        return $this->tableName;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getInstance(): Model
    {
        return new $this->modelClassName();
    }
}
