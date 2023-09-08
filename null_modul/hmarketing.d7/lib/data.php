<?

namespace Hmarketing\Data;

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\DatetimeField,
	Bitrix\Main\ORM\Fields\IntegerField,
	Bitrix\Main\ORM\Fields\TextField,
	Bitrix\Main\Type\DateTime;

Loc::loadMessages(__FILE__);


class DataTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'hmarketing_test';
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap()
	{
		return [
			new IntegerField(
				'ID',
				[
					'primary' => true,
					'autocomplete' => true,
					'title' => Loc::getMessage('TEST_ENTITY_ID_FIELD')
				]
			),
			new TextField(
				'TITLE',
				[
					'required' => true,
					'title' => Loc::getMessage('TEST_ENTITY_TITLE_FIELD')
				]
			),
			new IntegerField(
				'SORT',
				[
					'default' => 500,
					'title' => Loc::getMessage('TEST_ENTITY_SORT_FIELD')
				]
			),
		];
	}
}
