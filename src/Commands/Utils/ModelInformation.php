<?php

namespace Clickbar\Magellan\Commands\Utils;

class ModelInformation
{
    public function __construct(
        protected string $namespace,
        protected string $relativePath,
        protected string $tableName,
    ) {
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
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
