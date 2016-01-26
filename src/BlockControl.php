<?php

namespace Falur\Blocks;

use Closure;

class BlockControl
{
    /**
     * Экземпляр класса выбранного блока
     *
     * @var \Falur\Blocks\BaseBlock
     */
    protected $block = null;

    /**
     * Массив со страницами и регулярными выражениями где не показывать блок
     *
     * @var array
     */
    protected $not = ['/'];

    /**
     * Массив со страницами и регулярными выражениями где показывать блок
     *
     * @var array
     */
    protected $show = ['\*'];

    /**
     * Конструктор
     *
     * @param \Falur\Blocks\BaseBlock $block
     */
    public function __construct(BaseBlock $block)
    {
        $this->block = $block;
    }

    /**
     * Устанавливает страницы для не показа блока
     *
     * @param array $list
     * @return \Falur\Blocks\BlockControl
     */
    public function not(array $list)
    {
        $this->not = $list;

        return $this;
    }

    /**
     * Устанавливает страницы для показа блока
     *
     * @param array $list
     * @return \Falur\Blocks\BlockControl
     */
    public function show(array $list)
    {
        $this->show = $list;

        return $this;
    }

    /**
     * Проверяет необходимо ли показывать блок и если да то возвращает отображение блока
     *
     * @param type $template
     */
    public function render($template = 'default')
    {
        if ($this->isShow()) {
            return $this->block->render($template);
        }

        return false;
    }

    /**
     * Принимает аргументом замыкание внутри которого доступны методы выбранного класса для блока
     *
     * @param Closure $closure
     * @return \Falur\Blocks\BlockControl
     */
    public function block(Closure $closure)
    {
        $closure($this->block);

        return $this;
    }

    /**
     * Проверяет нужно ли на данной странице показать блок
     *
     * @return boolean
     */
    protected function isShow()
    {
        $current = trim($this->getPathUrl(), '/');

        foreach ($this->not as $not) {
            if ($this->check($not, $current)) {
                return false;
            }
        }

        if (in_array('\*', $this->show)) {
            return true;
        }

        foreach ($this->show as $show) {
            if ($this->check($show, $current)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Проверяет совпадает ли $page и $current
     *
     * @param string $page
     * @param string $current
     * @return boolean
     */
    protected function check($page, $current)
    {
        $trmPage = trim($page, '/');
        $pattern = '#' . $trmPage . '#';
        return $current == $trmPage
               || (preg_match($pattern, $current) && $pattern != '##');
    }

    /**
     * Получает путь текущего адреса
     *
     * @return string
     */
    protected function getPathUrl()
    {
        $url = $_SERVER['REQUEST_URI'];

        return parse_url($url, \PHP_URL_PATH);
    }
}
