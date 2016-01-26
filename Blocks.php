<?php

namespace Falur\Blocks;

class Blocks
{
    /**
     * Массив с инициализированными классами имя_блока => имя_класса
     *
     * @var array
     */
    protected static $names = [];

    /**
     * Массив с создаными экземплярами классов блоков имя_блка => объект
     *
     * @var array
     */
    protected static $instances = [];

    /**
     * Метод инициализации, принимает массив с параметрами имя_блока => имя_класса
     *
     * @param array $names
     */
    public static function init(array $names)
    {
        self::$names = $names;
    }

    /**
     * Получает блок по его имени
     *
     * @param string $name
     * @return \Falur\Blocks\BaseBlock
     * @throws NotInitBlockException
     */
    public static function get($name)
    {
        if (!isset(self::$names[$name])) {
            throw new NotInitBlockException('Not init block with name = ' . $name);
        }

        if (!isset(self::$instances[$name])) {
            $classname = self::$names[$name];
            self::$instances[$name] = new BlockControl(new $classname);
        }

        return self::$instances[$name];
    }
}
