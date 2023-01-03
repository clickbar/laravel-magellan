<?php

namespace Clickbar\Magellan\IO\Generator\WKB;

use Clickbar\Magellan\IO\Parser\WKB\ByteOrder;

class ByteStringBuilder
{
    protected ByteOrder $byteOrder;

    protected string $byteString;

    protected string $unsignedLongType;

    protected string $doubleType;

    protected string $byteType;

    public function __construct(ByteOrder $byteOrder)
    {
        $this->setByteOrder($byteOrder);
        $this->byteString = '';
    }

    public function setByteOrder(ByteOrder $byteOrder): self
    {
        $this->byteOrder = $byteOrder;
        if ($this->byteOrder === ByteOrder::bigEndian) {
            $this->unsignedLongType = 'N';
            $this->doubleType = 'E';
            $this->byteType = 'h*';
        } else {
            $this->unsignedLongType = 'V';
            $this->doubleType = 'e';
            $this->byteType = 'H*';
        }

        return $this;
    }

    public function addByteOrder(): self
    {
        if ($this->byteOrder === ByteOrder::littleEndian) {
            $this->addByte(1);
        } else {
            $this->addByte(0);
        }

        return $this;
    }

    public function addByte(int $value): self
    {
        $this->byteString .= pack('c', $value);

        return $this;
    }

    public function addUnsignedLong(int $value): self
    {
        $this->byteString .= pack($this->unsignedLongType, $value);

        return $this;
    }

    public function addDouble(float $value): self
    {
        if (is_nan($value)) {
            $this->byteString .= pack($this->byteType, '000000000000F87F');
        } else {
            $this->byteString .= pack($this->doubleType, $value);
        }

        return $this;
    }

    public function toByteString(bool $hex = false): string
    {
        if ($hex) {
            $unpacked = unpack('H*', $this->byteString);

            return strtoupper((string) $unpacked[1]);
        }

        return $this->byteString;
    }
}
