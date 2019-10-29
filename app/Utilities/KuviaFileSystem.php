<?php

namespace App\Utilities;

use League\Flysystem\FileExistsException;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;

class KuviaFileSystem
{
    /** @var \Illuminate\Filesystem\Filesystem */
    private $fs;

    public function __construct()
    {
        $this->fs = new \Illuminate\Filesystem\Filesystem();
    }

    public function exists(string $path): bool
    {
        return is_dir($path) || is_file($path);
    }

    public function mustExist(string $path): self
    {
        if (!$this->exists($path)) {
            throw new DirectoryNotFoundException("Requested directory `{$path}` does not exist");
        }
        return $this;
    }

    public function move(string $from, string $to, bool $overwrite = false, bool $createPathIfDoesntExist = true): self
    {
        $this->mustExist($from);

        if ($createPathIfDoesntExist) {
            $path = dirname($to);
            if (!is_dir($path)) {
                mkdir($path);
            }
        }

        if ($this->exists($to)) {
            if ($overwrite) {
                $this->fs->delete($to);
            } else {
                throw new FileExistsException($to);
            }
        }
        $this->fs->move($from, $to);
        return $this;
    }

    public function delete(string $path, bool $mustExist = true): self
    {
        if ($mustExist) {
            $this->mustExist($path);
        }
        if ($this->exists($path)) {
            if ($this->fs->isDirectory($path)) {
                $this->fs->deleteDirectory($path);
            } else {
                $this->fs->delete($path);
            }
        }
        return $this;
    }

    public static function __callStatic($name, $arguments)
    {
        $kfs = new static();
        return $kfs->{$name}(... $arguments);
    }
}
