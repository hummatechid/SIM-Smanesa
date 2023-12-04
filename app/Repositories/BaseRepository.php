<?php 
namespace App\Repositories;

use App\Interfaces\RepositoryInterface;

class BaseRepository implements RepositoryInterface {

    public $model;

    // function for get all data
    public function getAll(bool $history = null): object
    {
        switch ($history){
            case true: 
                return $this->model->whereNotNull('deleted_at')->get();
            case false: 
                return $this->model->whereNull('deleted_at')->get();
            default:
                return $this->model->whereNull('deleted_at')->get();
        }
    }

    // function for get one data by id data
    public function getOneById(string $id, bool $fail = false, bool $history = null): object | null
    {
        switch($history)
        {
            case true: 
                if($fail == true){
                    return $this->model->whereNotNull('deleted_at')->where('id',$id)->firstOrFail();
                } else {
                    return $this->model->whereNotNull('deleted_at')->where('id',$id)->first();
                }
            case false:
                if($fail == true){
                    return $this->model->whereNull('deleted_at')->where('id',$id)->firstOrFail();
                } else {
                    return $this->model->whereNull('deleted_at')->where('id',$id)->first();
                }
            default:
                if($fail == true){
                    return $this->model->whereNull('deleted_at')->where('id',$id)->firstOrFail();
                } else {
                    return $this->model->whereNull('deleted_at')->where('id',$id)->first();
                }
        }
    }

    // function for get one data by other column in table data
    public function getOneByOther(string $column, mixed $data, bool $fail = false, bool $history = null): object | null
    {
        switch($history)
        {
            case true: 
                if($fail == true){
                    return $this->model->whereNotNull('deleted_at')->where($column, $data)->firstOrFail();
                } else {
                    return $this->model->whereNotNull('deleted_at')->where($column, $data)->first();
                }
            case false:
                if($fail == true){
                    return $this->model->whereNull('deleted_at')->where($column, $data)->firstOrFail();
                } else {
                    return $this->model->whereNull('deleted_at')->where($column, $data)->first();
                }
            default:
                if($fail == true){
                    return $this->model->whereNull('deleted_at')->where($column, $data)->firstOrFail();
                } else {
                    return $this->model->whereNull('deleted_at')->where($column, $data)->first();
                }
        }
    }

    // function where has one condition for data
    public function whereOneCondition(string $column, mixed $data, string $column2 =  "id", string $method = "get"): mixed
    {
        switch ($method) {
            case 'first':
                return $this->model->whereNull('deleted_at')->where($column, $data)->first();
            case 'count':
                return $this->model->whereNull('deleted_at')->where($column, $data)->count();
            case 'summary':
                return $this->model->whereNull('deleted_at')->where($column, $data)->sum($column2);
            case 'average':
                return $this->model->whereNull('deleted_at')->where($column, $data)->avg($column2);
            default:
                return $this->model->whereNull('deleted_at')->where($column, $data)->get();
        }
    }

    // function where has one condition for data history
    public function whereOneConditionHistory(string $column, mixed $data, string $column2 =  "id", string $method = "get"): mixed
    {
        switch ($method) {
            case 'first':
                return $this->model->whereNotNull('deleted_at')->where($column, $data)->first();
            case 'count':
                return $this->model->whereNotNull('deleted_at')->where($column, $data)->count();
            case 'summary':
                return $this->model->whereNotNull('deleted_at')->where($column, $data)->sum($column2);
            case 'average':
                return $this->model->whereNotNull('deleted_at')->where($column, $data)->avg($column2);
            default:
                return $this->model->whereNotNull('deleted_at')->where($column, $data)->get();
        }
    }

    // function for relationship
    public function relationship(array $relation, string $method = "get"): mixed
    {
        switch ($method) {
            case 'first': 
                return $this->model->with($relation)->whereNull('deleted_at')->first();
            default: 
                return $this->model->with($relation)->whereNull('deleted_at')->get();
        }
    }

    // function for relationship data history
    public function relationshipHistory(array $relation, string $method = "get"): mixed
    {
        switch ($method) {
            case 'first': 
                return $this->model->with($relation)->whereNotNull('deleted_at')->first();
            default: 
                return $this->model->with($relation)->whereNotNull('deleted_at')->get();
        }
    }

    // function for create data
    public function create(array $data): object | null
    {
        return $this->model->create($data);
    }

    // function for update data by id
    public function update(string $id, array $data): object | null | bool
    {
        return $this->model->find($id)->update($data);
    } 

    // function for soft delete
    public function softDelete(string $id): object | null | bool
    {
        return $this->model->find($id)->update(['deleted_at' => now()]);
    }

    // function for soft delete
    public function backData(string $id): object | null | bool
    {
        return $this->model->find($id)->update(['deleted_at' => null]);
    }

    // function for delete permanent
    public function delete(string $id): object | null | bool
    {
        return $this->model->find($id)->delete();
    }

    /**
     * Get data that has been sorted
     *
     * @param string $column
     * @param string $value
     * @return object | null
     */
    public function getOrderedData(string $column, string $value): object | null
    {
        return $this->model->orderBy($column, $value)->get();
    }

    public function getCustomColumnValue(string $column, string $value): object|null
    {
        return $this->model->where($column, $value)->get();
    }

    public function getWhereIn(string $column, array $arrayIn, bool $history = false) : object|null
    {
        if($history == true){
            return $this->model->whereNotNull("deleted_at")->whereIn($column, $arrayIn)->get();
        } else {
            return $this->model->whereNull("deleted_at")->whereIn($column, $arrayIn)->get();
        }
    }

    public function getWhereNotIn(string $column, array $arrayNotIn, bool $history = false) : object|null
    {
        if($history == true){
            return $this->model->whereNotNull("deleted_at")->whereNotIn($column, $arrayNotIn)->get();
        } else {
            return $this->model->whereNull("deleted_at")->whereNotIn($column, $arrayNotIn)->get();
        }
    }

    public function oneConditionOneRelation(string $column, string $value, string $relation, string $method = 'get')
    {
        switch($method) {
            case 'first' :
                return $this->model->where($column, $value)->with($relation)->first();
            default :
                return $this->model->where($column, $value)->with($relation)->get();
        }
    }

    public function getDataYears(int $year, array $relations = [], bool $history = false): object | null
    {
        if($history == true){
            return $this->model->with($relations)->whereNotNull("deleted_at")->whereYear("created_at",$year)->get(); 
        } else {
            return $this->model->with($relations)->whereNull("deleted_at")->whereYear("created_at",$year)->get(); 
        }
    }

    public function getDataMonth(int $year, int $month, array $relations = [], bool $history = false): object | null
    {
        if($history == true){
            return $this->model->with($relations)->whereNotNull("deleted_at")->whereYear("created_at",$year)->whereMonth("created_at",$month)->get(); 
        } else {
            return $this->model->with($relations)->whereNull("deleted_at")->whereYear("created_at",$year)->whereMonth("created_at",$month)->get(); 
        } 
    }
}