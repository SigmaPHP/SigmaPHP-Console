<?php

namespace SigmaPHP\Console;

use SigmaPHP\Console\Interfaces\FileUtilityInterface;
use SigmaPHP\Console\Exceptions\PathNotFoundException;

/**
 * File Utility Class.
 */
class FileUtility implements FileUtilityInterface
{
    /**
     * List all files inside a path and its sub-directories.
     *
     * @param string $path
     * @param bool $includeFullPath
     * @param bool $withExtension
     * @return array<string>
     */
    public function list($path, $includeFullPath = false, $withExtension = true)
    {
        if (!file_exists($path)) {
            throw new PathNotFoundException("The path {$path} doesn't exist!");
        }

        if (!is_dir($path)) {
            return [$path];
        }

        $files = [];

        $directoryIterator = new \RecursiveDirectoryIterator(
            $path,
            \RecursiveDirectoryIterator::SKIP_DOTS
        );

        foreach (new \RecursiveIteratorIterator(
            $directoryIterator,
            \RecursiveIteratorIterator::CHILD_FIRST
        ) as $file) {
            $_file = $file->getRealPath();

            if (!$includeFullPath) {
                $_file = str_replace($path . '/', '', $_file);
            }

            if (!$withExtension) {
                $_file = str_replace('.php', '', $_file);
            }

            $files[] = $_file;
        }

        return $files;
    }

    /**
     * Check if a path exists.
     *
     * @param string $path
     * @return bool
     */
    public function exists($path)
    {
        return file_exists($path);
    }

    /**
     * Check if a directory has a file or sub-directory.
     *
     * @param string $path
     * @param string $target
     * @return bool
     */
    public function dirHas($path, $target)
    {
        return in_array($target, $this->list($path));
    }

    /**
     * Create file.
     *
     * @param string $path
     * @param int $permission
     * @return bool
     */
    public function create($path, $permission = 0755)
    {
        return touch($path) & chmod($path, $permission);
    }

    /**
     * Rename files and directories.
     *
     * @param string $old
     * @param string $new
     * @return bool
     */
    public function rename($old, $new)
    {
        return rename($old, $new);
    }

    /**
     * Copy files and directories.
     *
     * @param string $src
     * @param string $dest
     * @return bool
     */
    public function copy($src, $dest)
    {
        return copy($src, $dest);
    }

    /**
     * Move file.
     *
     * @param string $src
     * @param string $dest
     * @return bool
     */
    public function move($src, $dest)
    {
        return $this->rename($src, $dest);
    }

    /**
     * Remove file.
     *
     * @param string $path
     * @return bool
     */
    public function remove($path)
    {
        return unlink($path);
    }

    /**
     * Create directory.
     *
     * @param string $path
     * @param int $permission
     * @param bool $recursive
     * @return bool
     */
    public function createDir($path, $permission = 0755, $recursive = true)
    {
        return mkdir($path, $permission, $recursive);
    }

    /**
     * Remove directory and its content.
     *
     * @param string $path
     * @return bool
     */
    public function removeDir($path)
    {
        // delete content
        $files = $this->list($path, true);

        foreach ($files as $key => $file) {
            if (is_dir($file)) {
                rmdir($file);
            } else {
                unlink($file);
            }
        }

        // delete the dir
        return rmdir($path);
    }
}
