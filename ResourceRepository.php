<?php
namespace App\Repositories;


use Illuminate\Support\Str;

abstract class ResourceRepository
{
    protected $model;

    /**
     * @param int $n
     * @return mixed
     */
    public function getPaginate(int $n)
    {
        return $this->model->latest('id')->paginate($n);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function store(Array $data)
    {
        return $this->model->create($data);
    }

    public function storeWithFile($file_name, $public_path, Array $data)
    {

    }

    /**
     * @param $column
     * @param $attribute
     * @param array $data
     * @return mixed
     */
    public function updateOrCreate($column, $attribute, Array $data)
    {
        $get = $this->model->where($column, '=', $attribute)->first();

        if ($get) {
            $this->updateWithId($get->id, $data);
            return $this->getById($get->id);
        }

        return $this->store($data);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @param string $slug
     * @return mixed
     */
    public function getBySlug(string $slug)
    {
        return $this->model->where('slug', $slug)->first();
    }

    /**
     * @param string $email
     * @return mixed
     */
    public function getByEmail(string $email)
    {
        return $this->model->where('email', $email)->first();
    }

    /**
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateWithId(int $id, Array $data)
    {
        return $this->getById($id)->update($data);
    }

    public function updateWithSlug(string $slug, Array $data)
    {
        return $this->getBySlug($slug)->update($data);
    }

    /**
     * @param string $slug
     */
    public function destroyWithSlug(string $slug)
    {
        $this->getBySlug($slug)->delete();
    }

    /**
     * @param int $id
     */
    public function destroyWithId(int $id)
    {
        $this->getById($id)->delete();
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        return $this->model->latest('id')->get();
    }

    /**
     * @return mixed
     */
    public function count()
    {
        return $this->model->count();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function next(int $id)
    {
        $id = $this->model->where('id', '>', $id)->min('id');

        if ($id) {
            return $this->model->findOrFail($id);
        }

        return null;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function previous(int $id)
    {
        $id = $this->model->where('id', '<', $id)->max('id');

        if ($id) {
            return $this->model->findOrFail($id);
        }

        return null;
    }
}
