<?php

namespace Clickbar\Magellan\Commands\Utils;

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
}
