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

    public function getWhereIn(string $column, array $arrayIn) : object|null
    {
        return $this->model->whereIn($column, $arrayIn)->get();
    }

    public function getWhereInHistory(string $column, array $arrayIn, bool $history = false) : object|null
    {
        if($history == true){
            return $this->model->whereNotNull("deleted_at")->whereIn($column, $arrayIn)->get();
        } else {
            return $this->model->whereNull("deleted_at")->whereIn($column, $arrayIn)->get();
        }
    }

    public function getWhereNotIn(string $column, array $arrayNotIn) : object|null
    {
        return $this->model->whereNotIn($column, $arrayNotIn)->get();
    }

    public function getWhereNotInHistory(string $column, array $arrayNotIn, bool $history = false) : object|null
    {
        if($history == true){
            return $this->model->whereNotNull("deleted_at")->whereNotIn($column, $arrayNotIn)->get();
        } else {
            return $this->model->whereNull("deleted_at")->whereNotIn($column, $arrayNotIn)->get();
        }
    }

    public function oneConditionOneRelation(string $column, string $value, array $relation, string $method = 'get', bool $history = false)
    {
        switch($method) {
            case 'first' :
                if($history){
                    return $this->model->with($relation)->where($column, $value)->whereNotNull("deleted_at")->first();
                } else {
                    return $this->model->with($relation)->where($column, $value)->whereNull("deleted_at")->first();
                }
            default :
                if($history){
                    return $this->model->with($relation)->where($column, $value)->whereNotNull("deleted_at")->get();
                } else {
                    return $this->model->with($relation)->where($column, $value)->whereNull("deleted_at")->get();
                }
        }
    }

    public function oneNullConditionOneRelation(string $column, array $relation, string $method = 'get', bool $history = false)
    {
        switch($method) {
            case 'first' :
                if($history){
                    return $this->model->with($relation)->whereNull($column)->whereNotNull("deleted_at")->first();
                } else {
                    return $this->model->with($relation)->whereNull($column)->whereNull("deleted_at")->first();
                }
            default :
                if($history){
                    return $this->model->with($relation)->whereNull($column)->whereNotNull("deleted_at")->get();
                } else {
                    return $this->model->with($relation)->whereNull($column)->whereNull("deleted_at")->get();
                }
        }
    }

    public function oneNotNullConditionOneRelation(string $column, array $relation, string $method = 'get', bool $history = false)
    {
        switch($method) {
            case 'first' :
                if($history){
                    return $this->model->with($relation)->whereNotNull($column)->whereNotNull("deleted_at")->first();
                } else {
                    return $this->model->with($relation)->whereNotNull($column)->whereNull("deleted_at")->first();
                }
            default :
                if($history){
                    return $this->model->with($relation)->whereNotNull($column)->whereNotNull("deleted_at")->get();
                } else {
                    return $this->model->with($relation)->whereNotNull($column)->whereNull("deleted_at")->get();
                }
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

    public function getDataDate($date, array $relations = [], bool $history = false): object | null
    {
        if($history == true){
            return $this->model->with($relations)->whereNotNull("deleted_at")->whereDate("created_at",$date)->get(); 
        } else {
            return $this->model->with($relations)->whereNull("deleted_at")->whereDate("created_at",$date)->get(); 
        } 
    }

    public function getDataCustomDate($date_from, $date_to, array $relations = [], bool $history = false): object | null
    {
        if($history == true){
            return $this->model->with($relations)->whereNotNull("deleted_at")->whereBetween("created_at",[$date_from, $date_to])->get(); 
        } else {
            return $this->model->with($relations)->whereNull("deleted_at")->whereBetween("created_at",[$date_from, $date_to])->get(); 
        } 
    }

    public function getDataDateWithCondition($date, array $relations = [], string $column, mixed $data ,string $type = "get", bool $history = false): object | null
    {
        switch($type){
            case "get":
                if($history == true){
                    return $this->model->with($relations)->whereNotNull("deleted_at")->whereDate("created_at",$date)->where($column, $data)->get(); 
                } else {
                    return $this->model->with($relations)->whereNull("deleted_at")->whereDate("created_at",$date)->where($column, $data)->get(); 
                } 
                break;
            case "first":
                if($history == true){
                    return $this->model->with($relations)->whereNotNull("deleted_at")->whereDate("created_at",$date)->where($column, $data)->first(); 
                } else {
                    return $this->model->with($relations)->whereNull("deleted_at")->whereDate("created_at",$date)->where($column, $data)->first(); 
                } 
                break;
            default:
                if($history == true){
                    return $this->model->with($relations)->whereNotNull("deleted_at")->whereDate("created_at",$date)->where($column, $data)->get(); 
                } else {
                    return $this->model->with($relations)->whereNull("deleted_at")->whereDate("created_at",$date)->where($column, $data)->get(); 
                } 
                break;
        }
    }

    public function getYearFirstData()
    {
        return $this->model->selectRaw('YEAR(created_at) as year')->orderBy('created_at', 'asc')->first();
    }

    public function limitOrderBy(string $column, string $sort, int $limit, array $relation = [])
    {
        return $this->model->with($relation)->orderBy($column, $sort)->limit($limit);
    }

    // function for summary data
    public function randomData(string $method, string $column = "id"): mixed
    {
        switch ($method) {
            case 'first':
                return $this->model->whereNull('deleted_at')->first();
            case 'count':
                return $this->model->whereNull('deleted_at')->count();
            case 'summary':
                return $this->model->whereNull('deleted_at')->sum($column);
            case 'average':
                return $this->model->whereNull('deleted_at')->avg($column);
            default:
                return $this->model->whereNull('deleted_at')->get();
        }
    }
}