<?php

namespace app\modules\yboard\components\rbac;

use yii\rbac\Rule;

/**
 * Checks if blog owner matches user passed via params
 */
class PrivateMessageRule extends Rule
{
    public $name = 'isMessageYours';

    /**
     * @param string|integer $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return boolean a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        return isset($params['message']) ? $params['message']->sendfrom == $user || $params['message']->sendto == $user : false;
    }
}
