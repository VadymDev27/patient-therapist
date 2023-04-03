<?php

namespace App;

class Bootstrapper extends \Illuminate\Foundation\Application
{
    public function publicPath()
    {
        $path = $this->basePath . DIRECTORY_SEPARATOR . 'public_html';

        return $path;
    }
}
