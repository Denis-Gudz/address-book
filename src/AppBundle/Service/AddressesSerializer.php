<?php

namespace AppBundle\Service;

use AppBundle\Entity\Address;

class AddressesSerializer
{
    /**
     * @param $data
     * @return array
     * Serialize Addresses into array
     */
    public function serialize($data) : array
    {

        if (is_array($data) && !empty($data)) {

            $objectToArray = [];
            foreach ($data as $address) {

                //log possible errors
                if ($address instanceof Address) {

                    $objectToArray[] = $this->serializeObject($address);

                }
            }

            return $objectToArray;

        }

        //log possible errors
        if ($data !== null && $data instanceof Address) {

            return $this->serializeObject($data);
        }

        return [];

    }

    /**
     * @param Address $address
     * @return array
     */
    public function serializeObject(Address $address) : array
    {
        $addressArray = [
            'id'          => $address->getId(),
            'firstname'   => $address->getFirstname(),
            'lastname'    => $address->getLastname(),
            'birthday'    => ($address->getBirthday() != null) ? $address->getBirthday()->format('c') : null,
            'street'      => $address->getStreet(),
            'number'      => $address->getNumber(),
            'zip'         => $address->getZip(),
            'city'        => $address->getCity(),
            'country'     => $address->getCountry(),
            'phonenumber' => $address->getPhonenumber(),
            'email'       => $address->getEmail(),
            'createdAt'   => $address->getCreatedAt()->format('c'),
            'updatedAt'   => $address->getUpdatedAt()->format('c'),
        ];

        return $addressArray;
    }
}

