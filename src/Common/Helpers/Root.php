<?php

namespace Thinktomorrow\Chief\Common\Helpers;

/**
 * Class Root
 *
 * Taken from the thinktomorrow/locale package
 * @ref https://github.com/thinktomorrow/locale/blob/master/src/Values/Root.php
 */
class Root
{
    private $valid = false;

    private $scheme;
    private $schemeless = false;
    private $defaultScheme = 'http';
    private $secure = false;

    private $host;
    private $port;

    private function __construct(string $host)
    {
        $this->parse($host);

        if (false !== filter_var($this->get(), FILTER_VALIDATE_URL)) {
            $this->valid = true;
        }
    }

    public static function fromString(string $host)
    {
        return new self($host);
    }

    public function get()
    {
        $scheme = (!is_null($this->scheme)) ? $this->scheme.'://' : ($this->schemeless ? '//' : $this->defaultScheme.'://');
        $port = (!is_null($this->port)) ? ':'.$this->port : null;

        if ($scheme == 'http://' && $this->secure) {
            $scheme = 'https://';
        }

        return $scheme.$this->host.$port;
    }

    public function valid(): bool
    {
        return $this->valid;
    }

    public function secure(): self
    {
        $this->secure = true;
        $this->scheme = 'https';

        return $this;
    }

    public function host(): string
    {
        return $this->host;
    }

    public function scheme(): ?string
    {
        return $this->scheme;
    }

    private function parse(string $host)
    {
        // Sanitize url input a bit to remove double slashes, but do not remove first slashes
        if ($host == '//') {
            $host = '/';
        }

        $parsed = parse_url($host);

        if (false === $parsed) {
            throw new \InvalidArgumentException('Failed to parse url. Invalid url ['.$host.'] passed as parameter.');
        }

        // If a schemeless url is passed, parse_url will ignore this and strip the first tags
        // so we keep a reminder to explicitly reassemble the 'anonymous scheme' manually
        $this->schemeless = !isset($parsed['scheme']) && (0 === strpos($host, '//') && isset($parsed['host']));

        $this->scheme = $parsed['scheme'] ?? null;
        if ($this->scheme == 'https') {
            $this->secure();
        }

        $this->host = $this->parseHost($parsed);
        $this->port = $parsed['port'] ?? null;
    }

    public function __toString(): string
    {
        return $this->get();
    }

    private function parseHost(array $parsed): string
    {
        if (isset($parsed['host'])) {
            return $parsed['host'];
        }

        if (!isset($parsed['path'])) {
            return null;
        }

        return (0 < strpos($parsed['path'], '/'))
            ? substr($parsed['path'], 0, strpos($parsed['path'], '/'))
            : $parsed['path'];
    }
}
