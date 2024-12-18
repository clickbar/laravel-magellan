<?php

namespace Clickbar\Magellan\IO\Parser\WKB;

class Scanner
{
    private $data;

    private $len;

    private $pos;

    private ?ByteOrder $byteOrder;

    public function __construct($data)
    {
        if (preg_match('/[0-9a-fA-F]+/', $data[0])) {
            $data = pack('H*', $data);
        }

        $this->data = $data;
        $this->len = strlen($data);
        $this->pos = 0;
    }

    public function setByteOrder(ByteOrder $byteOrder)
    {
        $this->byteOrder = $byteOrder;
    }

    public function getByteOrder(): ?ByteOrder
    {
        return $this->byteOrder;
    }

    public function remaining(): int
    {
        return $this->len - $this->pos;
    }

    public function byte(): int
    {
        if ($this->pos + 1 > $this->len) {
            throw new \RuntimeException('Not enough bytes left to fulfill 1 byte.');
        }

        $str = substr($this->data, $this->pos, 1);
        $this->pos += 1;

        $result = unpack('C', $str);

        return $result[1];
    }

    public function integer(?ByteOrder $byteOrder = null): int
    {
        if ($this->pos + 4 > $this->len) {
            throw new \RuntimeException('Not enough bytes left to fulfill 1 integer.');
        }

        $str = substr($this->data, $this->pos, 4);
        $this->pos += 4;

        $byteOrder = $this->assertByteOrder($byteOrder);
        $result = unpack($byteOrder === ByteOrder::littleEndian ? 'V' : 'N', $str);

        return $result[1];
    }

    public function double(?ByteOrder $byteOrder = null): float
    {
        if ($this->pos + 8 > $this->len) {
            throw new \RuntimeException('Not enough bytes left to fulfill 1 double.');
        }

        $str = substr($this->data, $this->pos, 8);
        $this->pos += 8;

        $byteOrder = $this->assertByteOrder($byteOrder);

        if ($byteOrder === ByteOrder::bigEndian) {
            $str = strrev($str);
        }

        $double = unpack('d', $str);

        return $double[1];
    }

    private function assertByteOrder(?ByteOrder $byteOrder): ByteOrder
    {
        return $byteOrder ?? $this->byteOrder ?? throw new \RuntimeException('No byte order specified');
    }
}
