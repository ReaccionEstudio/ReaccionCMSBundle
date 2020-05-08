<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Services\Config;

interface ConfigServiceInterface
{
    public function get(string $key);

    public function set(string $key, $value);
}
