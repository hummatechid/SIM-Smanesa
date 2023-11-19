<?php 

namespace App\Interfaces;

interface RepositoryInterface {

    // function for get all data
    public function getAll(bool $history = null): array;

    // function for get one data by id data
    public function getOneById(string $id, bool $fail = false, bool $history = null): object | null;

    // function for get one data by other column in table data
    public function getOneByOther(string $column, mixed $data, bool $fail = false, bool $history = null): object | null;

    // function where has one condition for data
    public function whereOneCondition(string $column, mixed $data, string $column2 =  "id", string $method = "get"): mixed;

    // function where has one condition for data history
    public function whereOneConditionHistory(string $column, mixed $data, string $column2 =  "id", string $method = "get"): mixed;

    // function for relationship
    public function relationship(array $relation, string $method = "get"): mixed;

    // function for relationship data history
    public function relationshipHistory(array $relation, string $method = "get"): mixed;

    // function for create data
    public function create(array $data): object | null;

    // function for update data by id
    public function update(string $id, array $data): object | null;

    // function for soft delete
    public function softDelete(string $id): object | null;

    // function for delete permanent
    public function delete(string $id): object | null;

    /**
     * Get data that has been sorted
     *
     * @param string $column
     * @param string $value
     * @return object | null
     */
    public function getOrderedData(string $column, string $value): object | null;
}