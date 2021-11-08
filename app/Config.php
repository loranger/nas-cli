<?php
namespace App;

use \Storage;

class Config
{
    private $file = ".nas";
    private $config;

    public function __construct()
    {
        $this->config = collect([]);
        if (\Storage::exists($this->file)) {
            $this->config = collect(json_decode(\Storage::get($this->file)));
        }
    }

    public function get($label = null)
    {
        if ($label) {
            return $this->config->get($label);
        }
        return $this->config;
    }

    public function getLabels()
    {
        return $this->config->keys();
    }

    public function add($label, $local_path, $remote_path, $ignore_list = [])
    {
        $this->config->put($label, [
            'from' => $local_path,
            'to' => $remote_path,
            'ignore' => $ignore_list
        ]);
        return $this->save();
    }

    public function remove($label)
    {
        $this->config->pull($label);
        return $this->save();
    }

    public function save()
    {
        return \Storage::put(
            $this->file,
            $this->config->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );
    }
}
