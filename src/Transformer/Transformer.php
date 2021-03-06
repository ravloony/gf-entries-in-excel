<?php

namespace GFExcel\Transformer;

use GF_Field;
use GFExcel\Field\BaseField;
use GFExcel\Field\FieldInterface;

class Transformer implements TransformerInterface
{

    protected $fields = array(
        'address' => 'GFExcel\Field\AddressField',
        'section' => 'GFExcel\Field\SectionField',
        'fileupload' => 'GFExcel\Field\FileUploadField',
    );

    /**
     * Transform GF_Field instance to a GFExel Field (FieldInterface)
     * @param GF_Field $field
     * @return FieldInterface
     */
    public function transform(GF_Field $field)
    {
        $type = $field->get_input_type();

        if ($fieldClass = $this->getField($type, $field)) {
            return $fieldClass;
        }

        return new BaseField($field);
    }

    private function getField($type, GF_Field $field)
    {
        $fields = $this->getFields();
        if (array_key_exists($type, $fields)) {
            return new $fields[$type]($field);
        }
        return false;
    }

    private function getFields()
    {
        return gf_apply_filters(
            array(
                "gfexcel_transformer_fields",
            ),
            $this->fields
        );
    }
}