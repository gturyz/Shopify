<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) 2015-2019 Splash Sync  <www.splashsync.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Splash\Connectors\Shopify\Objects;

use Splash\Connectors\Shopify\Services\ShopifyConnector;
use Splash\Models\AbstractObject;
use Splash\Models\Objects\IntelParserTrait;
use Splash\Models\Objects\ListsTrait;
use Splash\Models\Objects\ObjectsTrait;
use Splash\Models\Objects\PricesTrait;
use Splash\Models\Objects\SimpleFieldsTrait;

/**
 * Shopify Implementation of Customer Invoice
 *
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 */
class Invoice extends AbstractObject
{
    // Splash Php Core Traits
    use IntelParserTrait;
    use SimpleFieldsTrait;
    use PricesTrait;
    use ObjectsTrait;
    use ListsTrait;

    // Shopify Core Traits
    use Core\DatesTrait;
    
    // Shopify Orders Traits
    use Order\CRUDTrait;
    use Order\ObjectsListTrait;
    use Order\CoreTrait;
    use Order\MainTrait;
    use Order\StatusTrait;
    use Order\ItemsTrait;
    use Order\PaymentsTrait;

    //====================================================================//
    // Object Definition Parameters
    //====================================================================//
    
    /**
     *  Object Disable Flag. Uncomment this line to Override this flag and disable Object.
     */
//    protected static    $DISABLED        =  True;
    
    /**
     *  Object Name (Translated by Module)
     */
    protected static $NAME            =  "Customer Invoice";
    
    /**
     *  Object Description (Translated by Module)
     */
    protected static $DESCRIPTION     =  "Shopify Customers Invoice Object";
    
    /**
     *  Object Icon (FontAwesome or Glyph ico tag)
     */
    protected static $ICO     =  "fa fa-money";
    
    /**
     * Object Synchronistion Limitations
     *
     * This Flags are Used by Splash Server to Prevent Unexpected Operations on Remote Server
     *
     * @codingStandardsIgnoreStart
     */
    protected static $ALLOW_PUSH_CREATED         =  false;       // Allow Creation Of New Local Objects
    protected static $ALLOW_PUSH_UPDATED         =  false;       // Allow Update Of Existing Local Objects
    protected static $ALLOW_PUSH_DELETED         =  false;       // Allow Delete Of Existing Local Objects
    
    /**
     *  Object Synchronistion Recommended Configuration
     */
    protected static $ENABLE_PUSH_CREATED       =  false;         // Enable Creation Of New Local Objects when Not Existing
    protected static $ENABLE_PUSH_UPDATED       =  false;         // Enable Update Of Existing Local Objects when Modified Remotly
    protected static $ENABLE_PUSH_DELETED       =  false;         // Enable Delete Of Existing Local Objects when Deleted Remotly

    protected static $ENABLE_PULL_CREATED       =  true;         // Enable Import Of New Local Objects
    protected static $ENABLE_PULL_UPDATED       =  true;         // Enable Import of Updates of Local Objects when Modified Localy
    protected static $ENABLE_PULL_DELETED       =  true;         // Enable Delete Of Remotes Objects when Deleted Localy
    
    /**
     * @codingStandardsIgnoreEnd
     *
     * @var ShopifyConnector
     */
    protected $connector;
    
    /**
     * Class Cosntructor
     *
     * @param ShopifyConnector $connector
     */
    public function __construct(ShopifyConnector $connector)
    {
        $this->connector  =   $connector;
    }
}
