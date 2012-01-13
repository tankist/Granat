<?php
class Sch_Filter_Array_EntitiesCollection extends Sch_Filter_Array_Map
{

    public function filter($value)
    {
        if ($value instanceof \Doctrine\ORM\PersistentCollection) {
            $value = $value->getValues();
        }
        return parent::filter($value);
    }

    protected function _filterMapArray($element, $index)
    {
        if (is_array($element)) {
            return parent::_filterMapArray($element, $index);
        }
        $key = $index;
        $keyKey = $this->getKeyKey();
        $getterName = 'get' . ucwords($keyKey);
        if (method_exists($element, $getterName)) {
            $key = call_user_func(array($element, $getterName));
        }
        $valueKey = $this->getValueKey();
        $getterName = 'get' . ucwords($valueKey);
        if (method_exists($element, $getterName)) {
            $element = call_user_func(array($element, $getterName));
        }
        return array($key, $element);
    }

}
