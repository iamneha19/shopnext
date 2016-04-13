<?php

/**
 * This is the model class for table "shop_visit_statistics".
 *
 * The followings are the available columns in table 'shop_visit_statistics':
 * @property integer $id
 * @property integer $shop_id
 * @property integer $total_count
 * @property string $count_type
 * @property string $count_date
 * @property string $month_week
 * @property string $month
 * @property integer $year
 * @property string $last_updated_on
 *
 * The followings are the available model relations:
 * @property Shop $shop
 */
class ShopVisitStatistics extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ShopVisitStatistics the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'shop_visit_statistics';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('shop_id, total_count, count_type, last_updated_on', 'required'),
			array('shop_id, total_count, year', 'numerical', 'integerOnly'=>true),
			array('count_type', 'length', 'max'=>7,'min'=>5),
			array('month_week', 'length', 'max'=>5,'min'=>5),
			array('month', 'length', 'max'=>2,'min'=>2),
			array('year', 'length', 'max'=>4,'min'=>4),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, shop_id, total_count, count_type, count_date, month_week, month, year, last_updated_on', 'safe', 'on'=>'search'),
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
			'shop' => array(self::BELONGS_TO, 'Shop', 'shop_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'shop_id' => 'Shop',
			'total_count' => 'Total Count',
			'count_type' => 'Count Type',
			'count_date' => 'Count Date',
			'month_week' => 'Month Week',
			'month' => 'Month',
			'year' => 'Year',
			'last_updated_on' => 'Last Updated On',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('shop_id',$this->shop_id);
		$criteria->compare('total_count',$this->total_count);
		$criteria->compare('count_type',$this->count_type,true);
		$criteria->compare('count_date',$this->count_date,true);
		$criteria->compare('month_week',$this->month_week,true);
		$criteria->compare('month',$this->month,true);
		$criteria->compare('year',$this->year);
		$criteria->compare('last_updated_on',$this->last_updated_on,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}