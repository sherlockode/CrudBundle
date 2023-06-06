<?php

namespace Sherlockode\CrudBundle\View;


class View
{
    /**
     * @var Field[]
     */
    private $fields = [];

    /**
     * @param array $config
     * @param       $fieldTemplates
     *
     * @throws \ReflectionException
     */
    public function __construct(array $config = [], $fieldTemplates = [])
    {
        $className = strtolower((new \ReflectionClass($config['config']['class']))->getShortName());
        $config = $config['show'] ?? [];

        foreach ($config as $key => $data) {
            $field = new Field();

            $field->setKey($key);
            $field->setLabel($data['label'] ?? 'sherlockode_crud.' . $className . '.' . $this->camelCaseToSnakeCase($key));
            $field->setOptions($data['options'] ?? []);

            if (isset($data['type']) && isset($fieldTemplates[$data['type']])) {
                $field->setTemplate($fieldTemplates[$data['type']]);
            }

            $this->fields[$key] = $field;
        }
    }

    /**
     * @return Field[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param Field[] $fields
     *
     * @return $this
     */
    public function setFields(array $fields): self
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * @param string $string
     *
     * @return string
     */
    private function camelCaseToSnakeCase(string $string): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }
}
