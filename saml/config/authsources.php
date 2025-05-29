<?php

$config = array(

    'admin' => array(
        'core:AdminPassword',
    ),

    'example-userpass' => array(
        'exampleauth:UserPass',
        'user1:user1pass' => array(
            'uid' => array('1'),
            'eduPersonAffiliation' => array('group1'),
            'email' => 'user1@example.com',
            'user_name' => 'user1',
            'first_name' => 'User1',
            'last_name' => 'One',
            'urn:oid:0.9.2342.19200300.100.1.1' => 'user1',
        ),
        'user2:user2pass' => array(
            'uid' => array('2'),
            'eduPersonAffiliation' => array('group2'),
            'email' => 'user2@example.com',
            'user_name' => 'user2',
            'first_name' => 'User2',
            'last_name' => 'Two',
            'urn:oid:0.9.2342.19200300.100.1.1' => 'user2',
        ),
    ),

    // New SAML SP configuration with signing
    'default-sp' => array(
        'saml:SP',
        'privatekey' => 'saml.pem',
        'certificate' => 'saml.crt',
        'signature.algorithm' => 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256',
    ),

);
