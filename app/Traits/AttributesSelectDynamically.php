<?php


namespace Gcr\Traits;


use ReflectionClass;

trait AttributesSelectDynamically
{
    public static function attributeAllowed($attribute, $value)
    {
        return in_array($value, self::attributeCodes($attribute));
    }

    public static function attributeCodes($attribute)
    {
        return array_keys(self::attributeOptions($attribute));
    }

    public static function attributeOptions($attribute)
    {
        $attributeUpper = strtoupper($attribute);
        $options = [];
        $constants = (new ReflectionClass(self::class))->getConstants();
        foreach ($constants as $constant => $value) {
            if (str_contains($constant, $attributeUpper)) {
                $options[] = $value;
            }
        }
        return array_combine($options, self::getLabels($attribute));
    }

    protected static function getLabels($attribute)
    {
        $labels = [];
        if (self::$labels && is_array(self::$labels)) {
            $labels = self::$labels[$attribute] ?? [];
        }
        return $labels;
    }
}
