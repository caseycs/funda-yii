<?php

/**
 * This is the model class for table "funda_filter".
 *
 * The followings are the available columns in table 'funda_filter':
 * @property string $id
 * @property string $location
 * @property string $type
 * @property integer $is_garden
 * @property string $fetch_time
 */
class FundaFilter extends CActiveRecord
{
    const ID_AMSTERDAM_ALL = 1;
    const ID_AMSTERDAM_GARDEN = 2;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'funda_filter';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('is_garden, fetch_time', 'required'),
            array('is_garden', 'numerical', 'integerOnly' => true),
            array('location', 'length', 'max'=>50),
            array('type', 'length', 'max'=>50),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, location, type, is_garden, fetch_time', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        return array(
            'fundaPages' => array(self::HAS_MANY, 'FundaPage', 'funda_filter_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'is_garden' => 'Is Garden',
            'fetch_time' => 'Fetch Time',
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
     *                             based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('location', $this->location);
        $criteria->compare('type', $this->type);
        $criteria->compare('is_garden', $this->is_garden);
        $criteria->compare('fetch_time', $this->fetch_time, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param  string      $className active record class name.
     * @return FundaFilter the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
