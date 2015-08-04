<?php
namespace API;

class Hydrator
{
    
    public static function configure($object, array $data = [])
    {
        if(!is_object($object)) {
            throw new \InvalidArgumentException('The param object is not an object');
        }
        
        $methods = get_class_methods(get_class($object));
        
        foreach($methods as $method) {
            if(strtolower(substr($method, 0, 3)) == 'set') {
                $property = strtolower(substr($method, 3));
                
                if(array_key_exists($property, $data)) {
                    $object->$method($data[$property]);
                }
            }
            
        }
        
        return $object;
    }
}