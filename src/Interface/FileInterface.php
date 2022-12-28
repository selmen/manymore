<?php

namespace App\Interface;

interface FileInterface 
{
    public function remove(?string $filename): void;
}
