<?php

namespace Kyoushu\CommonBundle\Meta;

class PropertyTypeDetector
{

    const REGEX_DOC_VAR_ANNOTATION = '/@var (?<types>[^\n]+)/';

    protected static $typeMap = array(
        'DateTime' => '\DateTime',
        'bool' => 'boolean',
        'null' => 'NULL',
        'int' => 'integer',
        'float' => 'double'
    );

    public static function detect($class, $propertyName)
    {

        $classRef = new \ReflectionClass($class);
        $propRef = $classRef->getProperty($propertyName);

        if(!preg_match(self::REGEX_DOC_VAR_ANNOTATION, $propRef->getDocComment(), $match)){
            return null;
        }

        $types = explode('|', $match['types']);
        if(count($types) === 0) return null;

        $types = self::normaliseTypes($types, $classRef);

        foreach($types as $type){
            if($type === 'NULL') continue;
            return $type;
        }

        if(in_array('NULL', $types)){
            return 'NULL';
        }

        return null;

    }

    /**
     * @param string $type
     * @param \ReflectionClass $classRef
     * @return null|string
     */
    protected static function resolveTypeClass($type, \ReflectionClass $classRef)
    {
        if(substr($type, 0, 1) === '/') return $type;

        $class = sprintf('\\%s\\%s', $classRef->getNamespaceName(), $type);
        if(class_exists($class)) return $class;

        $class = sprintf('\\%s', $type);
        if(class_exists($class)) return $class;

        return null;
    }

    /**
     * @param array $types
     * @param \ReflectionClass $classRef
     * @return array
     */
    protected static function normaliseTypes(array $types, \ReflectionClass $classRef)
    {
        array_walk($types, function(&$type) use ($classRef){
            $type = trim($type);

            if(substr($type, -2) === '[]'){
                $type = 'array';
            }

            if(isset(self::$typeMap[$type])){
                $type = self::$typeMap[$type];
            }

            $class = self::resolveTypeClass($type, $classRef);
            if($class !== null) $type = $class;

        });

        $types = array_filter($types);

        return $types;
    }

}