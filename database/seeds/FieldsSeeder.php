<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FieldsSeeder extends Seeder
{
    /**
     * @var \Carbon\Carbon
     */
    protected $now;

    /**
     * @var array
     */
    protected $fields = [
        [
            'id' => 1,
            'name' => 'Operating System',
            'values' => ['iOS', 'Android', 'Windows Phone'], // 1, 2, 3
        ],
        [
            'id' => 2,
            'name' => 'RAM Size',
            'values' => ['512MB', '1GB', '2GB'], // 4, 5, 6
        ],
        [
            'id' => 3,
            'name' => 'Storage Size',
            'values' => ['16GB', '32GB', '64GB', '128GB', '256GB'], // 7, 8, 9, 10, 11
        ],
        [
            'id' => 4,
            'name' => 'Screen Resolution',
            'values' => ['375x667', '414x736', '960x540', '1136x640', '1280x768', '1280x720', '1334x750', '1920x1080'], // 12, 13, 14, 15, 16, 17, 18, 19
        ],
        [
            'id' => 5,
            'name' => 'CPU Cores Count',
            'values' => ['1', '2', '4', '6', '8'], // 20, 21, 22, 23, 24
        ],
    ];

    /**
     * Run the fields seeder.
     */
    public function run()
    {
        $this->now = Carbon::now();

        $fieldValueId = 1;

        foreach ($this->fields as $field) {
            DB::table('fields')->insert($this->createField($field['id'], $field['name']));

            DB::table('field_values')->insert(
                $this->createFieldValues($field['id'], $fieldValueId, $field['values'])
            );

            $fieldValueId += count($field['values']);
        }
    }

    /**
     * @param int $id
     * @param string $name
     * @return array
     */
    protected function createField(int $id, string $name)
    {
        return [
            'id' => $id,
            'name' => $name,
            'created_at' => $this->now,
            'updated_at' => $this->now,
        ];
    }

    /**
     * @param int $fieldId
     * @param int $fieldValueId
     * @param array $values
     * @return array
     */
    protected function createFieldValues(int $fieldId, int $fieldValueId, array $values)
    {
        $fieldValues = [];

        foreach ($values as $value) {
            $fieldValues[] = [
                'id' => $fieldValueId,
                'field_id' => $fieldId,
                'value' => $value,
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ];

            ++$fieldValueId;
        }

        return $fieldValues;
    }
}
