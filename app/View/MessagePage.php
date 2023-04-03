<?php

namespace App\View;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SimpleMessage;
use Illuminate\Support\HtmlString;

class MessagePage extends MailMessage implements Renderable
{
    public $view = 'message-page';

    public function viewData(array $data): self
    {
        $this->viewData = array_merge($this->viewData, $data);

        return $this;
    }

    public function action($text, $url, $method = 'GET')
    {
        parent::action($text, $url);
        $this->viewData(['method' => $method]);

        return $this;
    }

    /**
     * Make a line that has a link in it, put :link for where the link should go.
     *
     * @param string $line
     * @param string $url
     *
     * @return $this
     */
    public function line($line, string $linkText = null, string $url = null): self
    {
        if (isset($url)) {
            $formattedLine = str_replace(':link', "<a href=\"{$url}\" class=\"text-blue-500 underline\">{$linkText}</a>", $line);

            return parent::line(new HtmlString($formattedLine));
        }

        return parent::line($line);
    }

    public function statusText(string $code, string $displayText): self
    {
        $this->viewData([
            'statusCode' => $code,
            'statusText' => $displayText
        ]);

        return $this;
    }

    public function render()
    {
        return app(Factory::class)->make($this->view, $this->data());
    }
}
