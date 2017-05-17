<?php

namespace Laravel\ProductFields;

use Illuminate\Contracts\Config\Repository;

class ModelsResolver
{
    /**
     * @var Repository
     */
    protected $settings;

    /**
     * ModelsResolver constructor.
     *
     * @param Repository $settings
     */
    public function __construct(Repository $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Get the Field model name.
     *
     * @return string
     */
    public function field(): string
    {
        return $this->settings->get('product-fields.models.field');
    }

    /**
     * Get the FieldValue model name.
     *
     * @return string
     */
    public function fieldValue(): string
    {
        return $this->settings->get('product-fields.models.field_value');
    }

    /**
     * Get the polymorphic relation name.
     *
     * @return string
     */
    public function morphName(): string
    {
        return $this->settings->get('product-fields.morph_name', 'fieldable');
    }
}
