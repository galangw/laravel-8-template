<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Repository extends RepositoryAbstract
{

    protected Model $model;

    /**
     * get all data
     *
     * @return Collection
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * get all data order by created at desc
     *
     * @return Collection
     */
    public function getLatest()
    {
        return $this->model->latest()->get();
    }

    /**
     * get all data order by created at desc
     *
     * @param string $column
     * @param string $method
     * @return Collection
     */
    public function getOrderBy(string $column, string $method = 'asc')
    {
        return $this->model->orderBy($column, $method)->get();
    }

    /**
     * store data to db
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * store data to db
     *
     * @param array $data
     * @return Model
     */
    public function store(array $data)
    {
        return $this->create($data);
    }

    /**
     * find data by id
     *
     * @param mixed $id
     * @return Model
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * find or fail data by id
     *
     * @param mixed $id
     * @return Model
     */
    public function findOrFail($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * find with data by id
     *
     * @param mixed $id
     * @param array $with
     * @return Model
     */
    public function findWith($id, $with = [])
    {
        return $this->model->where('id', $id)->with($with)->first();
    }

    /**
     * find with or fail data by id
     *
     * @param mixed $id
     * @param array $with
     * @return Model
     */
    public function findWithOrFail($id, $with = [])
    {
        return $this->model->where('id', $id)->with($with)->firstOrFail();
    }

    /**
     * update data by id
     *
     * @param array $data
     * @param int $id
     * @return Model
     */
    public function update(array $data, int $id)
    {
        $model = $this->find($id);
        if ($model) {
            $model->update($data);
            return $this->find($id);
        }
        return 0;
    }

    /**
     * update data by key
     *
     * @param array $data
     * @param string $key
     * @return Model
     */
    public function updateByKey(array $data, string $key)
    {
        $model = $this->model->where('key', $key);
        if ($model) {
            $model->update($data);
            return $model;
        }
        return 0;
    }

    /**
     * delete data by id
     *
     * @param int $id
     * @return Model
     */
    public function delete(int $id)
    {
        $model = $this->find($id);
        if ($model) {
            return $model->delete();
        }
        return 0;
    }

    /**
     * delete data by id
     *
     * @param int $id
     * @return Model
     */
    public function destroy(int $id)
    {
        return $this->delete($id);
    }

    /**
     * get data as pagination
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginate()
    {
        $perPage = request('perPage', 20);
        return $this->model->query()
            ->when(request('sort') === 'oldest', function ($query) {
                $query->sortBy('id', 'asc');
            })
            ->when(request('sort') === 'latest' || request('sort') === null, function ($query) {
                $query->latest();
            })
            ->paginate($perPage);
    }
}
