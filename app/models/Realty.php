<?php

/**
 * This is the model class for table "realty".
 *
 * The followings are the available columns in table 'realty':
 * @property string $id
 * @property string $agent_id
 * @property string $GlobalId
 * @property string $update_time
 *
 * The followings are the available model relations:
 * @property FundaPageRealtyLink[] $fundaPageRealtyLinks
 * @property Agent $agent
 */
class Realty extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'realty';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('agent_id, GlobalId, update_time', 'required'),
			array('agent_id, GlobalId', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, agent_id, GlobalId, update_time', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'fundaPageRealtyLinks' => array(self::HAS_MANY, 'FundaPageRealtyLink', 'realty_id'),
			'agent' => array(self::BELONGS_TO, 'Agent', 'agent_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'agent_id' => 'Agent',
			'GlobalId' => 'Global',
			'update_time' => 'update_time',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('agent_id',$this->agent_id,true);
		$criteria->compare('GlobalId',$this->GlobalId,true);
		$criteria->compare('update_time',$this->update_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Realty the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
