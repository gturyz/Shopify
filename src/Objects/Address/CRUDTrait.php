<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) 2015-2021 Splash Sync  <www.splashsync.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Splash\Connectors\Shopify\Objects\Address;

use ArrayObject;
use Splash\Connectors\Shopify\Models\ShopifyHelper as API;
use Splash\Core\SplashCore      as Splash;

/**
 * Shopify Contacts Address CRUD Functions
 */
trait CRUDTrait
{
    /**
     * Load Request Object
     *
     * @param string $objectId Object id
     *
     * @return ArrayObject|false
     */
    public function load($objectId)
    {
        //====================================================================//
        // Stack Trace
        Splash::log()->trace();
        //====================================================================//
        // Get Customer Address from Api
        $object = API::get(self::getUri($objectId), null, array(), "customer_address");
        //====================================================================//
        // Fetch Object
        if (null === $object) {
            return Splash::log()->errTrace("Unable to load Customer Address (".$objectId.").");
        }
        //====================================================================//
        // Unset Full Name to Avoid Data Duplicates
        unset($object['name']);

        return new ArrayObject($object, ArrayObject::ARRAY_AS_PROPS);
    }

    /**
     * Create Request Object
     *
     * @return ArrayObject|false
     */
    public function create()
    {
        //====================================================================//
        // Stack Trace
        Splash::log()->trace();
        //====================================================================//
        // Check Customer Name is given
        if (empty($this->in["customer_id"])) {
            return Splash::log()->err("ErrLocalFieldMissing", __CLASS__, __FUNCTION__, "customer_id");
        }
        //====================================================================//
        // Explode Storage Id
        $customerId = self::objects()->id($this->in["customer_id"]);
        //====================================================================//
        // Create New Customer Address
        $this->object = new ArrayObject(array( "id" => null ), ArrayObject::ARRAY_AS_PROPS);
        $this->setSimple("customer_id", $customerId);
        $this->setSimple("first_name", $this->in["first_name"]);
        $this->setSimple("last_name", $this->in["last_name"]);
        //====================================================================//
        // Create Customer from Api
        $newAddress = API::post(
            'customers/'.$customerId."/addresses",
            array("address" => $this->object),
            "customer_address"
        );
        if (null === $newAddress) {
            return Splash::log()->errTrace("Unable to Create Customer Address.");
        }

        return new ArrayObject($newAddress, ArrayObject::ARRAY_AS_PROPS);
    }

    /**
     * Update Request Object
     *
     * @param bool $needed Is This Update Needed
     *
     * @return false|string Object Id
     */
    public function update($needed)
    {
        //====================================================================//
        // Stack Trace
        Splash::log()->trace();
        //====================================================================//
        // Encode Object Id
        $objectId = $this->getObjectId($this->object->customer_id, $this->object->id);
        //====================================================================//
        // Check if Needed
        if (!$needed) {
            return $objectId;
        }
        //====================================================================//
        // Update Customer Address from Api
        if (null === API::put(self::getUri($objectId), array("customer_address" => $this->object))) {
            return Splash::log()->errTrace("Unable to Update Customer Address (".$objectId.").");
        }

        return $objectId;
    }

    /**
     * Delete requested Object
     *
     * @param null|string $objectId Object Id.  If NULL, Object needs to be created.
     *
     * @return bool
     */
    public function delete($objectId = null)
    {
        //====================================================================//
        // Stack Trace
        Splash::log()->trace();
        //====================================================================//
        // Delete Customer from Api
        if (null === API::delete(self::getUri($objectId))) {
            Splash::log()->errTrace("Unable to Delete Customer Address (".$objectId.").");
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getObjectIdentifier()
    {
        if (!isset($this->object->customer_id) || !isset($this->object->id)) {
            return false;
        }

        //====================================================================//
        // Encode Object Id
        return $this->getObjectId($this->object->customer_id, $this->object->id);
    }

    /**
     * Get Object CRUD Base Uri
     *
     * @param string $objectId Splash Encoded ObjectId
     *
     * @return string
     */
    private static function getUri(string $objectId = null) : string
    {
        $baseUri = 'customers/'.self::getCustomerId((string) $objectId);
        $baseUri .= "/addresses/".self::getAddressId((string) $objectId);

        return $baseUri;
    }
}
