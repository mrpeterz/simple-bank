<?php

namespace SimpleBank\Application\DataTransformer\User;

class UserDto implements Dto
{
    private string $id;
    private string $name;
    private string $branchId;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getBranchId(): string
    {
        return $this->branchId;
    }

    /**
     * @param string $branchId
     */
    public function setBranchId(string $branchId): void
    {
        $this->branchId = $branchId;
    }


}
