<?php

namespace SigmaPHP\Console\Interfaces;

/**
 * File Utility Interface.
 */
interface FileUtilityInterface
{
    /**
     * Create file.
     *
     * @param string $path
     * @param string $name
     * @param string $permission
     * @return bool
     */
    public function create($path, $name, $permission);

    /**
     * Copy file.
     *
     * @param string $path
     * @param string $old
     * @param string $new
     * @return bool
     */
    public function rename($path, $old, $new);

    /**
     * Copy file.
     *
     * @param string $src
     * @param string $dest
     * @return bool
     */
    public function copy($src, $dest);

    /**
     * Move file.
     *
     * @param string $src
     * @param string $dest
     * @return bool
     */
    public function move($src, $dest);

    /**
     * Remove file.
     *
     * @param string $path
     * @return bool
     */
    public function remove($path);

    /**
     * Create directory.
     *
     * @param string $path
     * @param string $name
     * @param string $permission
     * @return bool
     */
    public function createDir($path, $name, $permission);

    /**
     * Remove directory.
     *
     * @param string $path
     * @return bool
     */
    public function removeDir($path);
}
