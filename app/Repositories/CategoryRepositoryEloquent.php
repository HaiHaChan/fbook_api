<?php

namespace App\Repositories;

use App\Eloquent\Category;
use App\Contracts\Repositories\CategoryRepository;
use Illuminate\Pagination\Paginator;
use App\Exceptions\Api\ActionException;
use App\Exceptions\Api\NotFoundException;
use App\Exceptions\Api\UnknownException;
use Log;

class CategoryRepositoryEloquent extends AbstractRepositoryEloquent implements CategoryRepository
{
    public function model()
    {
        return new Category;
    }

    public function getData($data = [], $with = [], $dataSelect = ['*'])
    {
        $categories = $this->model()
            ->select($dataSelect)
            ->with($with)
            ->get();

        return $categories;
    }

    public function store(array $data)
    {
        return $this->model()->create($data);
    }

    public function update($categoryId, array $data)
    {
        try {
            $result = $this->model()
                ->where('id', $categoryId)
                ->update($data);
        } catch (Execption $e) {
            throw new QueryException($e->getMessage());
        }

        return $result;
    }

    public function countRecord()
    {
        return $this->model()->count();
    }

    public function getByPage( $limit = '')
    {
        return $this->model()
            ->withCount('books')
            ->latest()
            ->paginate($limit ?: config('paginate.default'));
    }

    public function searchByName(array $data, $limit = '')
    {
        Paginator::currentPageResolver(function() use ($data) {
            return $data['page'];
        });

        return $this->model()
            ->where('name', 'like', '%' . $data['key'] . '%')
            ->latest()
            ->paginate($limit ?: config('paginate.default'));
    }

    public function show($categoryId)
    {
        try {
            return $this->model()->findOrFail($categoryId);
        } catch (ModelNotFoundException $e) {
            Log::error($e->getMessage());

            throw new NotFoundException();
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            throw new UnknownException($e->getMessage(), $e->getCode());
        }
    }
}
