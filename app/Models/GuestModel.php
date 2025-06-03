<?php

namespace App\Models;

class GuestModel extends Model
{
    public string|null $name = null;

    public int|null $age = null;

    protected static $table = 'guests';

    public function __construct(?string $name = null, ?int $age = null)
    {
        parent::__construct();

        if ($name) {
            $this->name = $name;
        }

        if ($age) {
            $this->age = $age;
        }
    }
}
