<?php
namespace app\modules\yboard\components;

use app\modules\yboard\models\YBoardMember;
use DateTime;
use DateTimeZone;

class DateTimeCalculation {
	static public function shortDate($timestamp) {
		$df = \Yii::$app->formatter;
		return $df->asDate(self::userTimestamp($timestamp), 'short');
	}
	
	static public function longDate($timestamp) {
		$df = \Yii::$app->formatter;
		return $df->asDate(self::userTimestamp($timestamp), 'long');
	}
	
	static public function medium($timestamp) { 
		$df = \Yii::$app->formatter;
		return $df->asDatetime(self::userTimestamp($timestamp), 'medium');
	}
    
	static public function short($timestamp) { 
		$df = \Yii::$app->formatter;
		return $df->asDatetime(self::userTimestamp($timestamp), 'short');
	}
	
	static public function long($timestamp) {
		$df = \Yii::$app->formatter;
		return $df->asDatetime(self::userTimestamp($timestamp), 'long');
	}
	
	static public function full($timestamp) {
		$df = \Yii::$app->formatter;
		return $df->asDatetime(self::userTimestamp($timestamp), 'full') . ' ' . self::userTimezoneNotation();
	}
	
	/**
	 * Precursor function to convert timestamp for user
	 * @param string $timestamp timestamp format 'yyyy-MM-dd hh:mm:ss'
	 * @return string timestamp format 'yyyy-MM-dd hh:mm:ss'
	 */
	static public function userTimestamp($timestamp) {  
        if(!is_numeric($timestamp))  //skip for unix timestamps      
        {
            $dt = new DateTime($timestamp);
            $timestamp = $dt->getTimestamp();
        }
        
		if(\Yii::$app->user->isGuest) {
			return $timestamp;
		}
		$timezone = YBoardMember::findOne(\Yii::$app->user->id);
		
        if($timezone==null||!isset($timezone->profile->timezone)) {
			return $timestamp;
		} 
        else if(empty($timezone->profile->timezone))
        {
			return $timestamp;            
        }else {
			return self::convertTimestamp($timestamp, $timezone->profile->timezone);
		}
	}
	
	/**
	 * Convert timestamp from server time to time in target timezone
	 * @param string $timestamp timestamp format 'yyyy-MM-dd hh:mm:ss'
	 * @param string $timezone e.g. 'Europe/Paris'
	 * @return string timestamp format 'yyyy-MM-dd hh:mm:ss'
	 */
	static public function convertTimestamp($timestamp, $timezone) {
        $datetime = new DateTime('', new DateTimeZone($timezone));
        $datetime->setTimeStamp($timestamp);
        return $datetime->format('Y-m-d H:i:s');
	}
	
	/**
	 * Return the timezone notation for the user
	 * @return string
	 */
	static public function userTimezoneNotation() {
		if(\Yii::$app->user->isGuest) {
			$timezone = date_default_timezone_get();
		} else {
			$timezone = YBoardMember::findOne(\Yii::$app->user->id);
            if(isset($timezone->profile->timezone))
                $timezone = $timezone->profile->timezone;
                
			if(empty($timezone)) {
				$timezone = date_default_timezone_get();
			}
		}
		$dateTime = new DateTime(); 
		$dateTime->setTimeZone(new DateTimeZone($timezone)); 
		return $dateTime->format('T'); 
	}
    
    /**
	 * Return the difference btn dates formatted
     * inputs are unix timestamps
	 * @return string
	 */
	static public function getDiff($past, $future) 
    {
        $days = floor(abs(($future-$past)/(60*60*24)));
        $secs = abs(($future-$past)%(60*60*24));        
        $hrs = ceil($secs/(60*60));
        
        return ['days'=>$days, 'hours'=>$hrs];
    }
}
