<?php

namespace app\modules\yboard\components;
 
use yii\helpers\HtmlPurifier;

class Utils {
	static public function cleanHtml($html) {
		
        return HtmlPurifier::process($html, [
            'Attr.EnableID' => false, 
        ]);
	} 
}
?>
