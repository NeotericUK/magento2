<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Magento
 * @package     Magento
 * @copyright   Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Magento\Filesystem;

class DriverFactory
{
    /**
     * @var \Magento\Filesystem\DriverInterface[]
     */
    protected $drivers = array();

    /**
     * @var DirectoryList
     */
    protected $directoryList;

    /**
     * @param DirectoryList $directoryList
     */
    public function __construct(DirectoryList $directoryList)
    {
        $this->directoryList = $directoryList;
    }

    /**
     * Get a driver instance according the given scheme.
     *
     * @param null|string $protocolCode
     * @param DriverInterface $driver
     * @return DriverInterface
     * @throws FilesystemException
     */
    public function get($protocolCode = null, DriverInterface $driver = null)
    {
        $driverClass = '\Magento\Filesystem\Driver\File';
        if ($protocolCode !== null) {
            $driverClass = $this->directoryList->getProtocolConfig($protocolCode)['driver'];
        }
        if (!isset($this->drivers[$driverClass])) {
            $this->drivers[$driverClass] = new $driverClass($driver);
            if (!$this->drivers[$driverClass] instanceof DriverInterface) {
                throw new FilesystemException("Invalid filesystem driver class: " . $driverClass);
            }
        }
        return $this->drivers[$driverClass];
    }
}
