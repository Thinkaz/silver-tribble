<?php

namespace App\Contracts;

use Illuminate\Database\ConnectionInterface;

interface Importer
{
    public function __construct(ConnectionInterface $connection);

    public function handle();
}