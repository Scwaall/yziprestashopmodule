<?php
/**
 * NOTICE OF LICENSE
 *
 * @author Scwaall
 * @copyright Copyright (c) Scwaall - 2020
 * @package YziPrestaShopModule
 * @support scwaall@gmail.com
 */

namespace Scwaall\YziPrestaShopModule;

use Exception;

/**
 * Class Logger
 *
 * @package Scwaall\YziPrestaShopModule
 */
class Logger
{
    /** @var string $file The file's name with its path from the directory of "module/logs". */
    private $file;

    /**
     * Logger constructor.
     *
     * @param string $file The file's name with its path from the directory of "module/logs".
     * @throws Exception
     */
    public function __construct($file)
    {
        $this->setFile(Module::getPath() . '/logs/' . $file);
        $this->checkFilePath();
    }

    /**
     * Adds a log message.
     *
     * @param string $message The message to add to logs.
     * @param bool $toBreakLine Defines whether to break the line.
     * @return false|int The number of bytes that were written to the file, or false on failure.
     */
    public function addLog($message, $toBreakLine = true)
    {
        $fullMessage = file_exists($this->getFile()) && $toBreakLine ? "\n" : '';
        $fullMessage .= $toBreakLine ? '[' . date('Y-m-d H:i:s') . '] ' : ' ';
        $fullMessage .= $message;
        return file_put_contents($this->getFile(), $fullMessage, FILE_APPEND);
    }

    /**
     * Checks the file's path and creates directories if necessary.
     *
     * @throws Exception
     */
    private function checkFilePath()
    {
        $filePath = '';
        $filePathPartList = explode('/', $this->getFile());
        foreach ($filePathPartList as $filePathPart) {
            $filePath .= '/' . $filePathPart;
            if ($filePathPart != end($filePathPartList) && !is_dir($filePath) && !mkdir($filePath)) {
                throw new Exception(sprintf(
                    'This directory can not be created: %s!',
                    $filePath
                ));
            }
        }
    }

    /**
     * Gets the file's name with its path from the directory of "module/logs".
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Sets the file's name with its path from the directory of "module/logs".
     *
     * @param string $file The file's name with its path from the directory of "module/logs".
     * @return $this
     */
    private function setFile($file)
    {
        $this->file = $file;
        return $this;
    }
}
