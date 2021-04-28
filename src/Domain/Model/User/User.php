<?php

namespace SimpleBank\Domain\Model\User;

class User
{
    private UserId $id;
    private string $name;

    public function __construct(
      UserId $id,
      $name
    ) {
        $this->id = $id;
        $this->name = $name;
    }
}
