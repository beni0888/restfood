<?php

namespace Restfood\Json;

use Restfood\Exception\InvalidDataException;
use Webmozart\Assert\Assert;
use Webmozart\Json\DecodingFailedException;
use Webmozart\Json\JsonDecoder as Decoder;

class JsonDecoder
{
    /** @var Decoder */
    private $decoder;

    /**
     * @param string $json
     * @return array
     */
    public function decode($json)
    {
        $this->sanitize($json);
        return $this->doDecode($json);
    }

    /**
     * Ensure that the given data can be handled by the decoder.
     *
     * @param mixed $json
     * @throws InvalidDataException
     */
    private function sanitize($json)
    {
        try {
            Assert::string($json);
        } catch (\InvalidArgumentException $e) {
            throw InvalidDataException::invalidJson();
        }
    }

    /**
     * Return the given data decode.
     *
     * @param string $json
     * @return array
     * @throws \Webmozart\Json\ValidationFailedException
     */
    private function doDecode($json)
    {
        try {
            $decoder = $this->obtainDecoder();
            return (array)$decoder->decode($json);
        } catch (DecodingFailedException $e) {
            throw InvalidDataException::invalidJson();
        }
    }

    /**
     * Return a decoder instance.
     *
     * @return Decoder
     */
    private function obtainDecoder()
    {
        if (is_null($this->decoder)) {
            $this->decoder = new Decoder();
        }
        return $this->decoder;
    }
}