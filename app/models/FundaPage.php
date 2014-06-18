<?php

/**
 * This is the model class for table "funda_page".
 *
 * The followings are the available columns in table 'funda_page':
 * @property string $id
 * @property string $funda_filter_id
 * @property integer $number
 * @property string $fetch_time
 *
 * The followings are the available model relations:
 * @property FundaFilter $fundaFilter
 */
class FundaPage extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'funda_page';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('funda_filter_id, number', 'required'),
            array('number', 'numerical', 'integerOnly' => true),
            array('funda_filter_id', 'length', 'max' => 10),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, funda_filter_id, number, fetch_time', 'safe', 'on' => 'search'),
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
            'fundaFilter' => array(self::BELONGS_TO, 'FundaFilter', 'funda_filter_id'),
            'realties' => array(self::MANY_MANY, 'Realty', 'FundaPageRealtyLink(funda_page_id, realty_id)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'funda_filter_id' => 'Funda Filter',
            'number' => 'Number',
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
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('funda_filter_id', $this->funda_filter_id, true);
        $criteria->compare('number', $this->number);
        $criteria->compare('fetch_time', $this->fetch_time, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return FundaPage the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
