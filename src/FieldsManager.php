<?php

namespace Laravel\ProductFields;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class FieldsManager
{
    /**
     * @var ModelsResolver
     */
    protected $modelsResolver;

    /**
     * @var ResourcesResolver
     */
    protected $resourcesResolver;

    /**
     * FieldsManager constructor.
     *
     * @param ModelsResolver    $modelsResolver
     * @param ResourcesResolver $resourcesResolver
     */
    public function __construct(ModelsResolver $modelsResolver, ResourcesResolver $resourcesResolver)
    {
        $this->modelsResolver = $modelsResolver;
        $this->resourcesResolver = $resourcesResolver;
    }

    /**
     * Attach field values to the given resource.
     *
     * @param Model $resource
     * @param array $fieldValues
     *
     * @return Model
     */
    public function attachFields(Model $resource, array $fieldValues)
    {
        $fields = $this->createAttachableArray($fieldValues);

        $resource->fields()->attach($fields);

        return $resource;
    }

    /**
     * Sync field values with given resource.
     *
     * @param Model $resource
     * @param array $fieldValues
     * @param bool  $detach
     *
     * @return Model
     */
    public function syncFields(Model $resource, array $fieldValues, bool $detach = true)
    {
        $fields = $this->createAttachableArray($fieldValues);

        if ($detach === true) {
            $resource->fields()->sync($fields);
        } else {
            $resource->fields()->syncWithoutDetaching($fields);
        }

        return $resource;
    }

    /**
     * Detach field values from the given model.
     *
     * @param Model $resource
     * @param array $fieldIds
     *
     * @return Model
     */
    public function detachFields(Model $resource, array $fieldIds)
    {
        $resource->fields()->detach($fieldIds);

        return $resource;
    }

    /**
     * Transforms fieldId=>fieldValueId array to attachable list.
     *
     * @param array $fieldValues
     *
     * @return array
     */
    protected function createAttachableArray(array $fieldValues)
    {
        $fields = [];

        foreach ($fieldValues as $fieldId => $fieldValueId) {
            $fields[$fieldId] = ['field_value_id' => $fieldValueId];
        }

        return $fields;
    }

    /**
     * Loads resources that have exact field values attached.
     *
     * @param Collection $fieldValues
     *
     * @return Collection
     */
    public function getResourcesByFieldValues(Collection $fieldValues)
    {
        $valuesId = $this->resolveValuesId($fieldValues);
        $rows = $this->loadRowsByValuesId($valuesId);

        if (!count($rows)) {
            return collect([]);
        }

        return $this->resolveModels($rows);
    }

    /**
     * Loads resources by given field=>value pairs.
     *
     * @param Collection $filter
     *
     * @return Collection
     */
    public function filterResources(Collection $filter)
    {
        $rows = $this->loadRowsByFilter($filter);

        if (!count($rows)) {
            return collect([]);
        }

        return $this->resolveModels($rows);
    }

    /**
     * Resolves actual models via ResourceResolver and groups them by short key or model class name.
     *
     * @param Collection $rows
     *
     * @return Collection
     */
    protected function resolveModels($rows)
    {
        $groupped = $this->groupRowsByModelType($rows);
        $models = collect([]);

        $groupped->each(function (Collection $ids, string $model) use ($models) {
            $results = $this->resourcesResolver->resolve($model, $ids->toArray());

            if (!count($results['resources'])) {
                return;
            }

            $models->put($results['short_key'], collect($results['resources']));
        });

        return $models;
    }

    /**
     * Loads filtered rows from `filerables` table.
     *
     * @param Collection $filter
     *
     * @return Collection
     */
    protected function loadRowsByFilter(Collection $filter)
    {
        $query = DB::table($this->modelsResolver->morphName().'s');
        $fieldValueModel = $this->modelsResolver->fieldValue();

        $filter->each(function ($value, int $fieldId) use ($query, $fieldValueModel) {
            $query->orWhere(function ($q) use ($fieldId, $value, $fieldValueModel) {
                $q->where('field_id', $fieldId)
                    ->whereIn('field_value_id', $this->resolveValuesId($value, $fieldValueModel));
            });
        });

        return $query->get();
    }

    /**
     * Groups DB rows by attached model type.
     *
     * @param Collection $rows
     *
     * @return Collection
     */
    protected function groupRowsByModelType(Collection $rows)
    {
        $typeColumn = $this->modelsResolver->morphName().'_type';
        $idColumn = $this->modelsResolver->morphName().'_id';

        return collect($rows)->groupBy($typeColumn)->map->pluck($idColumn);
    }

    /**
     * Load rows that have given field_value_id list attached.
     *
     * @param array $valuesId
     *
     * @return Collection
     */
    protected function loadRowsByValuesId(array $valuesId)
    {
        $table = $this->modelsResolver->morphName().'s';

        return DB::table($table)->whereIn('field_value_id', $valuesId)->get();
    }

    /**
     * Creates array of value IDs from Collection or array of models/integers (or from single model/integer).
     *
     * @param \Illuminate\Database\Eloquent\Model|int|Collection|array $fieldValues
     * @param string                                                   $fieldValueModel = null
     *
     * @return array
     */
    protected function resolveValuesId($fieldValues, string $fieldValueModel = null): array
    {
        $fieldValueModel = $fieldValueModel ?? $this->modelsResolver->fieldValue();

        if ($fieldValues instanceof $fieldValueModel) {
            return [$fieldValues->id];
        } elseif (is_numeric($fieldValues)) {
            return [intval($fieldValues)];
        } elseif ($fieldValues instanceof Collection) {
            return $this->mapIdsFromCollection($fieldValues, $fieldValueModel);
        } elseif (is_array($fieldValues)) {
            return $this->mapIdsFromCollection(collect($fieldValues), $fieldValueModel);
        }

        return [];
    }

    /**
     * Extracts value IDs from collection.
     *
     * @param Collection $fieldValues
     * @param $fieldValueModel
     *
     * @return array
     */
    protected function mapIdsFromCollection(Collection $fieldValues, $fieldValueModel)
    {
        $values = $fieldValues->map(function ($value) use ($fieldValueModel) {
            if ($value instanceof $fieldValueModel) {
                return $value->id;
            } elseif (is_numeric($value)) {
                return intval($value);
            }

            return null;
        });

        return $values->reject(null)->toArray();
    }
}
