<?php

/**
 * Created by IntelliJ IDEA.
 * User: tri
 * Date: 9/20/16
 * Time: 2:55 PM
 */

/**
 * Class Pusher
 * Pushes notification to remote device
 *
 * v0.5: pushes to devices having apns_device_reg_id
 */
namespace app\helpers;
use ApnsPHP_Push;
use ApnsPHP_Abstract;
use ApnsPHP_Message;
use app\models\Cuser;
use app\models\Offer;
use Yii;

class Pusher
{
    private $push;
    public function __construct()
    {
// Adjust to your timezone
        date_default_timezone_set('America/New_York');

// Report all PHP errors
//        error_reporting(-1);

// Using Autoload all classes are loaded on-demand
//        require_once 'ApnsPHP/Autoload.php';

// Instantiate a new ApnsPHP_Push object
        $this->push = new ApnsPHP_Push(
            ApnsPHP_Abstract::ENVIRONMENT_PRODUCTION,
            '../config/Certificates.pem'
        );

// Set the Provider Certificate passphrase
// $this->push->setProviderCertificatePassphrase('test');

// Set the Root Certificate Autority to verify the Apple remote peer
        $this->push->setRootCertificationAuthority('../config/entrust_2048_ca.cer');

// Connect to the Apple Push Notification Service
        $this->push->connect();

    }

    /**
     * @param $rider Cuser
     * @param $offer Offer
     * @return mixed
     */
    public function actionPushOfferFound($rider, $offer, $is_dev = false)
    {
        if ($rider->apns_device_reg_id == null) {
            return false;
        }
        
        if ($is_dev){
            $this->push->disconnect();
            $this->push = new ApnsPHP_Push(
                ApnsPHP_Abstract::ENVIRONMENT_SANDBOX,
                '../config/DEV_server_certificates_bundle.pem'
            );
            $this->push->setRootCertificationAuthority('../config/entrust_2048_ca.cer');
            $this->push->connect();
        }
        
// Instantiate a new Message with a single recipient
        $message = new ApnsPHP_Message($rider->apns_device_reg_id);

// Set a custom identifier. To get back this identifier use the getCustomIdentifier() method
// over a ApnsPHP_Message object retrieved with the getErrors() message.
        $message->setCustomIdentifier("Request_Offered");

// Set badge icon to "3"
        $message->setBadge(1);

// Set a simple welcome text
        $rider_name = $offer->cuser->first_name;
        $rider_names=explode(' ',$rider_name);
        if (count($rider_names) >= 2){
            $rider_names[1] = strtoupper($rider_names[1][0]) . '.';
        }
        $rider_name=implode(' ', $rider_names);

        $message->setText("You have been matched with $rider_name! Click here to return to the app and approve your ridematch.");

// Play the default sound
        $message->setSound();

// Set another custom property
//        $message->setCustomProperty('offer', $offer->toArray());

// Set the expiry value to 30 seconds
//        $message->setExpiry(30);

// Add the message to the message queue
        $this->push->add($message);

// Send all messages in the message queue
        $this->push->send();

// Examine the error message container
        $aErrorQueue = $this->push->getErrors();
        if (!empty($aErrorQueue)) {
             Yii::error($aErrorQueue);
            return json_encode($aErrorQueue);
        }
        return [true];
    }

    /**
     * @param $rider Cuser
     * @param $offer Offer
     * @return mixed
     */
    public function actionPushDirect($rider, $is_dev = false)
    {
        if ($rider->apns_device_reg_id == null) {
            return false;
        }

        if ($is_dev){
            $this->push->disconnect();
            $this->push = new ApnsPHP_Push(
                ApnsPHP_Abstract::ENVIRONMENT_SANDBOX,
                '../config/DEV_server_certificates_bundle.pem'
            );
            $this->push->setRootCertificationAuthority('../config/entrust_2048_ca.cer');
            $this->push->connect();
        }

// Instantiate a new Message with a single recipient
        $message = new ApnsPHP_Message($rider->apns_device_reg_id);

// Set a custom identifier. To get back this identifier use the getCustomIdentifier() method
// over a ApnsPHP_Message object retrieved with the getErrors() message.
        $message->setCustomIdentifier("Request_Offered");

// Set badge icon to "3"
        $message->setBadge(1);

// Set a simple welcome text

        $message->setText("You have been idle for a long time! Click here to return to the app.");

// Play the default sound
        $message->setSound();

// Add the message to the message queue
        $this->push->add($message);

// Send all messages in the message queue
        $this->push->send();

// Examine the error message container
        $aErrorQueue = $this->push->getErrors();
        if (!empty($aErrorQueue)) {
            Yii::error($aErrorQueue);
            return json_encode($aErrorQueue);
        }
        return [true];
    }
    
    public function __destruct()
    {
        // Disconnect from the Apple Push Notification Service
        $this->push->disconnect();
    }
}