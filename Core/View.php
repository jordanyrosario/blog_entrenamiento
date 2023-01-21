<?php

namespace Core;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;

class View
{
    private string $name;
    private array $data;

    public function __construct(string $name, array $data = [])
    {
        $this->data = $data;
        $this->name = $name;
    }

    public function addData(array $data): self
    {
        $this->data = array_merge($this->data, $data);

        return $this;
    }

    public function render(): ResponseInterface
    {
        extract($this->data);
        ob_start();

        require ROOT."/Views/{$this->name}.view.php";

        return new HtmlResponse(ob_get_clean());
    }
}
