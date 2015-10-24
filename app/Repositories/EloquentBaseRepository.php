<?php

namespace App\Repositories;

abstract class EloquentBaseRepository
{
    /**
     * Eloquent model
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    public function __construct($model = null)
    {
        $this->model = $model;
    }

    /**
     * Count of all elements
     * @return object  collection of object of model
     */
    public function count()
    {
        return $this->model->count();
    }

    /**
     *
     * @return object  collection of object of model
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * Destroy
     *
     * @return object  collection of object of model
     */
    public function delete($model)
    {
        return $model->delete();
    }

    /**
     * Create a new model
     *
     * @param array  Data to create a new object
     * @return boolean
     */
    public function create(array $data)
    {
        $model = $this->model->create($data);
        if (!$model) {
            return false;
        }
        return $model;
    }

    /**
     * Get single model by id
     *
     * @param string slug
     * @return object object of model
     */
    public function byId($id)
    {
        return $this->model->find($id);
    }

    /**
     * Get multi model by ids
     *
     * @param string slug
     * @return object object of model
     */
    public function byIds($ids)
    {
        return $this->model->whereIn('id', $ids)->get();
    }

    /**
     * Get single model by slug
     *
     * @param string slug
     * @return object object of model
     */
    public function byIdWithTrashed($id)
    {
        return $this->model->withTrashed()->find($id);
    }

    /**
     * Update model
     *
     * @param $model
     * @param array $input
     * @return object object of model
     */
    public function update($model, array $data)
    {
        $model->fill($data);
        return $model->save();
    }

    /**
     * Restore trashed model
     * @param $model
     * @return bool
     */
    public function restore($model)
    {
        if ($model->restore()){
            return true;
        }
        return false;
    }

}