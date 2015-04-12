<?php

/**
 * MailForm class.
 */
class MailForm extends \yii\base\Model
{
	public $member_id;
	public $member_name;
	public $subject;
	public $body;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return [
			// name, email, subject and body are required
			['member_id, subject, body', 'required'],
			['body','filter','filter'=>[$obj=new CHtmlPurifier(), 'purify']],
			['member_name', 'safe'],
		];
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'subject'=>YBoard::t('yboard','Subject'),
		);
	}
}
