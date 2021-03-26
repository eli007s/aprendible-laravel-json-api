<?php


namespace App\JsonApi;


use Illuminate\Support\Str;

class JsonApiBuilder
{
    /**
     * JsonApiBuilder constructor.
     */
    public function __construct()
    {

    }

    public function jsonPaginate()
    {
        return function() {
            return $this->paginate(
                request('page.size'),
                '*',
                'page[number]',
                request('page.number')
            )->appends(request()->except('page.number'));
        };
    }

    public function applySorts()
    {
        return function() {
            if (!property_exists($this->model, 'allowedSorts')) {
                abort(500, 'Please set the public property $allowedSorts inside ' . get_class($this->model));
            }

            if (is_null($sort = request('sort'))) {
                return $this;
            }

            $sortFields = Str::of($sort)->explode(',');

            foreach ($sortFields as $sortField) {
                $direction =  'asc';

                if (Str::of($sortField)->startsWith('-')) {
                    $direction = 'desc';
                    $sortField = Str::of($sortField)->substr(1);
                }

                if (!collect($this->model->allowedSorts)->contains($sortField)) {
                    abort(400, "Invalid Query Parameter {$sortField} is not allowed.");
                }

                $this->orderBy($sortField, $direction);
            }

            return $this;
        };
    }

    public function applyFilters()
    {
        return function() {
            foreach (request('filter', []) as $filter => $value) {
                abort_unless($this->hasNamedScope($filter), 400, 'The filter ' . $filter . ' is not allowed');

                $this->{$filter}($value);
            }

            return $this;
        };
    }
}
