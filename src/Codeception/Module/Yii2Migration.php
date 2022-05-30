<?php
namespace Codeception\Module;

use Codeception\Exception\ModuleException;
use Codeception\Module;
use InvalidArgumentException;

/**
 * class MigrationModule
 */
class Yii2Migration extends Module
{
    protected $config = [
        'phpPath' => 'php',
        'yiiBinPath' => '',
        'populate' => true,
        'cleanup' => true,
        'excludeClearTables' => []
    ];

    /**
     * @return void
     * @throws ModuleException
     */
    public function _initialize() {
        if (null === $this->getCli()) {
            throw new InvalidArgumentException("You need cli module");
        }

        if (null === $this->getYii()) {
            throw new InvalidArgumentException("You need Yii2 module");
        }

        if (empty($this->_getConfig('yiiBinPath'))) {
            throw new InvalidArgumentException("You need set yiiBinPath option");
        }
    }

    /**
     * @param $settings
     * @return void
     * @throws ModuleException
     */
    public function _beforeSuite($settings = [])
    {
        $this->debug('trigger before suite');
        if ($this->_getConfig('populate')) {
            $this->populate();
        }

        if ($this->_getConfig('cleanup')) {
            $this->cleanup();
        }
    }

    // Хук: перед началом теста
    public function _before(\Codeception\TestCase $test) {
        $this->debug('trigger before test');
        if ($this->_getConfig('cleanup')) {
            $this->cleanup();
        }
    }

    /**
     * @return string
     */
    protected function getYiiBinPath():string {
        return codecept_absolute_path($this->_getConfig('yiiBinPath'));
    }

    /**
     * @return string
     */
    protected function getPhpPath():string {
        return $this->_getConfig('phpPath');
    }

    /**
     * @return Cli
     * @throws ModuleException
     */
    protected function getCli()
    {
        return $this->getModule('Cli');
    }

    /**
     * @return Yii2
     * @throws ModuleException
     */
    protected function getYii() {
        return $this->getModule('Yii2');
    }

    protected function up() {
        $this->getCli()->runShellCommand("{$this->getPhpPath()} {$this->getYiiBinPath()} migrate/up --interactive=0");
    }

    /**
     * @return void
     * @throws ModuleException
     */
    protected function dropTables():void {
        $this->getCli()->runShellCommand("{$this->getPhpPath()} {$this->getYiiBinPath()} migrate/drop");
    }

    /**
     * @return void
     * @throws ModuleException
     */
    protected function clearTables():void {
        $clearTables = null;
        if ([] !== $configClearTables = $this->_getConfig('excludeClearTables')) {
            $clearTables = implode(",", $configClearTables);
        }
        $this->getCli()->runShellCommand("{$this->getPhpPath()} {$this->getYiiBinPath()} migrate/clear-tables {$clearTables} --interactive=0");
    }

    protected function cleanup():void {
        $this->up();
        $this->clearTables();
    }

    protected function populate():void {
        $this->dropTables();
        $this->up();
        $this->clearTables();
    }
}